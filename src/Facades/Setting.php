<?php

namespace Wotz\FilamentSettings\Facades;

use Illuminate\Support\Facades\Facade;
use Wotz\FilamentSettings\Drivers\DatabaseDriver;

/**
 * @method static mixed get(string $key, mixed $default = null, bool $useCache = true)
 * @method static void set(string|array $key, mixed $value = null)
 * @method static bool has(string $key)
 * @method static void forget(string $key)
 *
 * @see DatabaseDriver
 */
class Setting extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'setting';
    }
}
