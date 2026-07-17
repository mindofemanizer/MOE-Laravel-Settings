<?php

namespace Moe\Settings\Livewire;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Moe\Settings\Schema\SettingField;
use Moe\Settings\Schema\SettingGroup;
use Moe\Settings\SettingDefinitions;

/**
 * Livewire component GENERIC untuk mengelola settings.
 * Merender tab per grup yang didaftarkan lewat SettingDefinitions.
 * App cukup menaruh <livewire:settings-manager /> di halaman admin.
 */
class SettingsManager extends Component
{
    use WithFileUploads;

    public string $activeTab = '';

    /** Nilai form key => value, di-load dari DB saat mount. */
    public array $values = [];

    /** Field password yang dikosongkan di form (jangan tampilkan ciphertext). */
    public array $passwordMask = [];

    /** Temporary upload per field image (key => TemporaryUploadedFile). */
    public array $imageUploads = [];

    public function mount(): void
    {
        $groups = SettingDefinitions::groups();
        $this->activeTab = array_key_first($groups) ?? '';

        foreach (SettingDefinitions::allFields() as $key => $field) {
            $stored = \Setting::get($key, $field->default);
            // Password/encrypted: jangan muat ciphertext ke form
            if ($field->isEncrypted()) {
                $this->values[$key] = '';
                $this->passwordMask[$key] = ! empty($stored);
            } else {
                $this->values[$key] = $stored;
            }
        }
    }

    public function getGroupsProperty(): array
    {
        return SettingDefinitions::groups();
    }

    public function getActiveGroupProperty(): ?SettingGroup
    {
        return SettingDefinitions::group($this->activeTab);
    }

    public function save(): void
    {
        $group = $this->getActiveGroupProperty();
        if (! $group) {
            return;
        }

        // Proses upload image dulu (sebelum persist)
        foreach ($group->fields as $field) {
            if ($field->type === SettingField::TYPE_IMAGE && ! empty($this->imageUploads[$field->key])) {
                $this->values[$field->key] = $this->storeImage($field->key, $this->imageUploads[$field->key]);
                unset($this->imageUploads[$field->key]);
            }
        }

        $changes = [];
        foreach ($group->fields as $field) {
            $changes = array_merge($changes, $this->persistField($field));
        }

        Cache::flush();

        // Panccarkan event agar app bisa mencatat ke activity/audit log.
        if (! empty($changes) && class_exists(\Moe\Settings\Events\SettingsSaved::class)) {
            event(new \Moe\Settings\Events\SettingsSaved($changes, $group->key));
        }

        session()->flash('settings_saved', 'Pengaturan berhasil disimpan.');
    }

    /**
     * Simpan file image. App bisa override behaviour lewat config
     * 'moe-settings.image_uploader' (closure: fn($key, $file) => $path).
     */
    protected function storeImage(string $key, TemporaryUploadedFile $file): string
    {
        $uploader = config('moe-settings.image_uploader');
        if (is_callable($uploader)) {
            return $uploader($key, $file);
        }

        $disk = config('moe-settings.image_disk', 'public');
        $dir = config('moe-settings.image_dir', 'settings');
        $path = $file->store($dir, $disk);

        return $path;
    }

    protected function persistField(SettingField $field): array
    {
        $key = $field->key;
        $raw = $this->values[$key] ?? null;

        // Password kosong + sudah ada nilai → jangan overwrite (biarkan nilai lama)
        if ($field->isEncrypted() && $raw === '' && ($this->passwordMask[$key] ?? false)) {
            return [];
        }

        // Image kosong → biarkan nilai lama (jangan hapus)
        if ($field->type === SettingField::TYPE_IMAGE && ($raw === '' || $raw === null)) {
            return [];
        }

        if ($field->type === SettingField::TYPE_TOGGLE) {
            $raw = ! empty($raw);
        }

        if ($field->type === SettingField::TYPE_CHECKBOX_GROUP && is_array($raw)) {
            $raw = array_values(array_filter($raw));
        }

        $old = \Setting::get($key);
        $changed = $old != $raw; // loose compare: jangan log bila nilai sama

        \Setting::set($key, $raw, $field->storageType(), $field->group, $field->description);

        if (! $changed) {
            return [];
        }

        // Jangan ekspos nilai secret/encrypted ke log
        $oldLog = $field->isEncrypted() ? ($old !== null && $old !== '' ? '••••••••' : null) : $old;
        $newLog = $field->isEncrypted() ? ($raw !== null && $raw !== '' ? '••••••••' : null) : $raw;

        return [$key => ['old' => $oldLog, 'new' => $newLog]];
    }

    public function render()
    {
        return view('moe-settings::settings-manager')
            ->layout('moe-settings::layouts.app');
    }
}
