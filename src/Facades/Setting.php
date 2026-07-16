<?php

namespace Moe\Settings\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed get(string $key, mixed $default = null)
 * @method static void set(string $key, mixed $value, ?string $type = null, ?string $group = null, ?string $description = null)
 * @method static bool has(string $key)
 * @method static void forget(string $key)
 * @method static \Illuminate\Support\Collection getGroup(string $group)
 * @method static int syncDefaults(?array $defaults = null)
 */
class Setting extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'moe.settings';
    }
}
