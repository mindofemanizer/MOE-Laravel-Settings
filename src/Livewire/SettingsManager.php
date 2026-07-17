<?php

namespace Moe\Settings\Livewire;

use Illuminate\Support\Facades\Cache;
use Livewire\Component;
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
    public string $activeTab = '';

    /** Nilai form key => value, di-load dari DB saat mount. */
    public array $values = [];

    /** Field password yang dikosongkan di form (jangan tampilkan ciphertext). */
    public array $passwordMask = [];

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

        foreach ($group->fields as $field) {
            $this->persistField($field);
        }

        Cache::flush();
        session()->flash('settings_saved', 'Pengaturan berhasil disimpan.');
    }

    protected function persistField(SettingField $field): void
    {
        $key = $field->key;
        $raw = $this->values[$key] ?? null;

        // Password kosong + sudah ada nilai → jangan overwrite (biarkan nilai lama)
        if ($field->isEncrypted() && $raw === '' && ($this->passwordMask[$key] ?? false)) {
            return;
        }

        if ($field->type === SettingField::TYPE_TOGGLE) {
            $raw = ! empty($raw);
        }

        if ($field->type === SettingField::TYPE_CHECKBOX_GROUP && is_array($raw)) {
            $raw = array_values(array_filter($raw));
        }

        \Setting::set($key, $raw, $field->storageType(), $field->group, $field->description);
    }

    public function render()
    {
        return view('moe-settings::settings-manager')
            ->layout('moe-settings::layouts.app');
    }
}
