<?php

namespace Codedor\FilamentSettings\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mixed get(string $key, $default = null)
 * @method static void set(string|array $key, mixed $value = null)
 * @method static bool has(string $key)
 * @method static void forget(string $key)
 *
 * @see \Codedor\FilamentSettings\Drivers\DatabaseDriver
 */
class Setting extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'setting';
    }
}
