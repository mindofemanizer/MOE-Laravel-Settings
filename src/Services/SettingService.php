<?php

namespace Moe\Settings\Services;

use Illuminate\Cache\CacheManager;
use Illuminate\Contracts\Config\Repository;
use Moe\Settings\Models\Setting;

class SettingService
{
    protected string $cachePrefix = 'moe.settings.';

    public function __construct(
        protected CacheManager $cache,
        protected Repository $config,
    ) {}

    public function get(string $key, mixed $default = null): mixed
    {
        $cached = $this->cache->rememberForever(
            $this->cachePrefix . $key,
            function () use ($key) {
                $setting = Setting::query()->where('key', $key)->first(['value', 'type']);
                if (!$setting) {
                    return null;
                }

                $value = $setting->value;

                if ($setting->type === 'encrypted' && $value !== null) {
                    $value = $this->tryDecrypt($value);
                }

                return ['value' => $value, 'type' => $setting->type];
            },
        );

        if ($cached === null) {
            return $default;
        }

        return $this->castToPhp($cached['value'], $cached['type']);
    }

    public function set(string $key, mixed $value, ?string $type = null, ?string $group = null, ?string $description = null): void
    {
        $type = $type ?? $this->inferType($value);

        $raw = $this->formatForDb($value, $type);

        Setting::query()->updateOrCreate(
            ['key' => $key],
            [
                'value' => $raw,
                'type' => $type,
                'group' => $group,
                'description' => $description,
            ],
        );

        $this->cache->forget($this->cachePrefix . $key);
    }

    public function has(string $key): bool
    {
        return Setting::query()->where('key', $key)->exists();
    }

    public function forget(string $key): void
    {
        Setting::query()->where('key', $key)->delete();

        $this->cache->forget($this->cachePrefix . $key);
    }

    public function getGroup(string $group): \Illuminate\Support\Collection
    {
        return Setting::query()
            ->where('group', $group)
            ->get()
            ->mapWithKeys(function (Setting $setting) {
                $value = $setting->value;

                if ($setting->type === 'encrypted' && $value !== null) {
                    $value = $this->tryDecrypt($value);
                }

                return [$setting->key => $this->castToPhp($value, $setting->type)];
            });
    }

    public function syncDefaults(?array $defaults = null): int
    {
        $defaults = $defaults ?? $this->config->get('moe-settings.defaults', []);
        $inserted = 0;

        foreach ($defaults as $key => $options) {
            $exists = Setting::query()->where('key', $key)->exists();
            if (!$exists) {
                $type = $options['type'] ?? 'string';
                Setting::query()->create([
                    'key' => $key,
                    'value' => $this->formatForDb($options['value'] ?? '', $type),
                    'type' => $type,
                    'group' => $options['group'] ?? null,
                    'description' => $options['description'] ?? null,
                ]);
                $inserted++;
            }
        }

        return $inserted;
    }

    protected function formatForDb(mixed $value, string $type): string
    {
        $stringValue = match ($type) {
            'integer' => (string) (int) $value,
            'float' => (string) (float) $value,
            'boolean' => $value ? '1' : '0',
            'json' => is_string($value) ? $value : json_encode($value),
            default => (string) $value,
        };

        if ($type === 'encrypted') {
            $stringValue = encrypt($stringValue);
        }

        return $stringValue;
    }

    protected function tryDecrypt(string $value): string
    {
        try {
            return decrypt($value);
        } catch (\Exception) {
            return $value;
        }
    }

    protected function castToPhp(mixed $value, string $type): mixed
    {
        return match ($type) {
            'integer' => (int) $value,
            'float' => (float) $value,
            'boolean' => filter_var($value, \FILTER_VALIDATE_BOOLEAN, \FILTER_NULL_ON_FAILURE) ?? false,
            'json' => is_string($value) ? json_decode($value, true) ?? [] : $value,
            'encrypted' => $value,
            default => $value,
        };
    }

    protected function inferType(mixed $value): string
    {
        return match (true) {
            is_bool($value) => 'boolean',
            is_int($value) => 'integer',
            is_float($value) => 'float',
            is_array($value) => 'json',
            default => 'string',
        };
    }
}
