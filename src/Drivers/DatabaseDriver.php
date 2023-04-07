<?php

namespace Codedor\FilamentSettings\Drivers;

use Codedor\FilamentSettings\Models\Setting;

class DatabaseDriver implements DriverInterface
{
    public function get(string $key, $default = null): mixed
    {
        return Setting::query()->key($key)->first()?->value ?: $default;
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
}
