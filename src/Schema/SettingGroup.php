<?php

namespace Moe\Settings\Schema;

/**
 * Kumpulan field dalam satu tab/group settings.
 */
class SettingGroup
{
    /**
     * @param string $key    slug group (mis. 'payment')
     * @param string $label  nama tab yang tampil di UI
     * @param string $icon   material symbol (opsional)
     * @param SettingField[] $fields
     */
    public function __construct(
        public string $key,
        public string $label,
        public string $icon = 'settings',
        public array $fields = [],
    ) {}

    public function addField(SettingField $field): static
    {
        $this->fields[] = $field;
        return $this;
    }
}
