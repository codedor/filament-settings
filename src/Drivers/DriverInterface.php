<?php

namespace Codedor\FilamentSettings\Drivers;

interface DriverInterface
{
    public function get(string $key, $default = null): mixed;

    public function set(string|array $key, mixed $value = null): void;

    public function has(string $key): bool;

    public function forget(string $key): void;
}
