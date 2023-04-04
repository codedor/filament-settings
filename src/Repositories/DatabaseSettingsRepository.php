<?php

namespace Codedor\FilamentSettings\Repositories;

class DatabaseSettingsRepository implements SettingRepositoryInterface
{
    public function get(string $key, $default = null): mixed
    {
        // TODO: Implement get() method.
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
