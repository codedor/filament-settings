<?php


use Codedor\FilamentSettings\Repositories\SettingRepositoryInterface;

if (! function_exists('setting')) {
    function setting($key, $default = null)
    {
        /** @var SettingRepositoryInterface $settingRepository */
        $settingRepository = app('settings');

        return $settingRepository->get($key);
    }
}
