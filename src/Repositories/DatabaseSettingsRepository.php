<?php

namespace Codedor\FilamentSettings\Repositories;

use Codedor\FilamentSettings\Models\Setting;

class DatabaseSettingsRepository implements SettingRepositoryInterface
{
    public function get(string $key, $default = null): mixed
    {
        return Setting::query()->where('key', $key)->first();
    }

    public function set(array|string $key, mixed $value): void
    {
        // TODO: Implement set() method.
    }

    public function has(string $key): bool
    {
        // TODO: Implement has() method.
    }

    public function forget(string $key): bool
    {
        // TODO: Implement forget() method.
    }
}
