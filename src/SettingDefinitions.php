<?php

namespace Moe\Settings;

use Moe\Settings\Schema\SettingField;
use Moe\Settings\Schema\SettingGroup;

/**
 * Registry grup setting yang bisa didaftarkan oleh tiap app.
 * App mendaftarkan definisi lewat ServiceProvider:
 *
 *   SettingDefinitions::register(new SettingGroup('payment', 'Pembayaran', 'payments', [
 *       new SettingField('pg.midtrans.server_key', 'Server Key', SettingField::TYPE_PASSWORD, group: 'payment'),
 *   ]));
 */
class SettingDefinitions
{
    /** @var array<string, SettingGroup> */
    protected static array $groups = [];

    public static function register(SettingGroup $group): void
    {
        static::$groups[$group->key] = $group;
    }

    /** @return array<string, SettingGroup> */
    public static function groups(): array
    {
        return static::$groups;
    }

    public static function group(string $key): ?SettingGroup
    {
        return static::$groups[$key] ?? null;
    }

    /** Flat list semua field dari semua grup. */
    /** @return SettingField[] */
    public static function allFields(): array
    {
        $fields = [];
        foreach (static::$groups as $group) {
            foreach ($group->fields as $field) {
                $fields[$field->key] = $field;
            }
        }
        return $fields;
    }
}
