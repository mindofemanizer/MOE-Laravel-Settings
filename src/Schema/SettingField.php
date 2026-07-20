<?php

namespace Moe\Settings\Schema;

/**
 * Deklarasi satu field setting untuk UI generic.
 * App cukup mendeklarasikan field sekali, lalu SettingsManager
 * me-render input + menyimpannya secara otomatis.
 */
class SettingField
{
    public const TYPE_TEXT = 'text';
    public const TYPE_TEXTAREA = 'textarea';
    public const TYPE_TOGGLE = 'toggle';
    public const TYPE_SELECT = 'select';
    public const TYPE_CHECKBOX_GROUP = 'checkbox_group';
    public const TYPE_PASSWORD = 'password'; // disimpan sebagai encrypted
    public const TYPE_NUMBER = 'number';
    public const TYPE_IMAGE = 'image'; // upload file, disimpan sebagai path string
    public const TYPE_LIVEWIRE_COMPONENT = 'livewire_component'; // render komponen kustom host app

    public ?string $componentName = null; // nama komponen livewire yang akan dirender

    public function __construct(
        public string $key,
        public string $label,
        public string $type = self::TYPE_TEXT,
        public mixed $default = null,
        public ?string $group = null,
        public ?string $description = null,
        public array $options = [],      // untuk select / checkbox_group
        public bool $sensitive = false,   // paksa encrypted
        public string $placeholder = '',
    ) {}

    public function component(string $name): self
    {
        $this->componentName = $name;
        return $this;
    }

    /** Tipe penyimpanan di DB (encrypted bila password/sensitive). */
    public function storageType(): string
    {
        if ($this->type === self::TYPE_PASSWORD || $this->sensitive) {
            return 'encrypted';
        }
        if ($this->type === self::TYPE_IMAGE) {
            return 'string';
        }
        // TYPE_SELECT dengan options tetap string (bukan json — json merusak string skalar).
        // JSON hanya untuk multi-value: checkbox_group & livewire_component.
        if ($this->type === self::TYPE_CHECKBOX_GROUP || $this->type === self::TYPE_LIVEWIRE_COMPONENT) {
            return 'json';
        }
        if ($this->type === self::TYPE_TOGGLE || $this->type === self::TYPE_NUMBER) {
            return $this->type;
        }
        return 'string';
    }

    /** Apakah butuh decryption saat ditampilkan di form. */
    public function isEncrypted(): bool
    {
        return $this->storageType() === 'encrypted';
    }
}
