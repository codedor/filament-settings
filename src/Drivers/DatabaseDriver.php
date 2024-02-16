<?php

namespace Codedor\FilamentSettings\Drivers;

use Codedor\FilamentSettings\Models\Setting;
use Illuminate\Support\Facades\Cache;

class DatabaseDriver implements DriverInterface
{
    public function get(string $key, mixed $default = null, $useCache = true): mixed
    {
        if (! $useCache) {
            return $this->fetch($key) ?? $default;
        }

        return Cache::rememberForever($this->cacheKey($key), fn () => $this->fetch($key))
            ?? $default;
    }

    public function set(array|string $key, mixed $value = null): void
    {
        if (! is_array($key)) {
            $key = [
                [
                    'key' => $key,
                    'value' => $value,
                ],
            ];
        }

        foreach ($key as $setting) {
            Cache::forget($this->cacheKey($setting['key']));

            Setting::query()->updateOrCreate([
                'key' => $setting['key'],
            ], [
                'value' => $setting['value'],
            ]);
        }
    }

    public function has(string $key): bool
    {
        return Setting::query()->key($key)->exists();
    }

    public function forget(string $key): void
    {
        Setting::query()->key($key)->delete();
    }

    private function cacheKey(string $key)
    {
        return "setting.{$key}";
    }

    private function fetch(string $key)
    {
        return Setting::query()->key($key)->first()?->value;
    }
}
