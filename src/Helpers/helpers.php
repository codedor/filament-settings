<?php


use Codedor\FilamentSettings\Repositories\SettingRepositoryInterface;

if (! function_exists('setting')) {
    function setting($key, $default)
    {
        /** @var SettingRepositoryInterface $settingRepository */
        $settingRepository = app(SettingRepositoryInterface::class);

        dd($settingRepository);
    }
}
