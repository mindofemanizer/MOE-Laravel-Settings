<?php

namespace Moe\Settings\Events;

use Illuminate\Foundation\Events\Dispatchable;

/**
 * Dipancarkan setelah SettingsManager menyimpan perubahan setting.
 * App melisten event ini untuk menulis ke activity/audit log masing-masing.
 */
class SettingsSaved
{
    use Dispatchable;

    /**
     * @param array<string, array{old: mixed, new: mixed}> $changes  Daftar perubahan per key.
     * @param string|null $group  Grup yang disimpan (null = semua).
     */
    public function __construct(
        public array $changes = [],
        public ?string $group = null,
    ) {}
}
