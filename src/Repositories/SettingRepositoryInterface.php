<?php

namespace Codedor\FilamentSettings\Repositories;

interface SettingRepositoryInterface
{
    public function get(string $key, $default = null): mixed;

    public function set(string|array $key, mixed $value): void;

    public function has(string $key): bool;

    public function forget(string $key): bool;
}
