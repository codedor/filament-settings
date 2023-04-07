<?php

use Codedor\FilamentSettings\Drivers\DriverInterface;
use Codedor\FilamentSettings\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

uses(RefreshDatabase::class);

it('fetches value from database', function () {
    $setting = Setting::factory([
        'key' => fake()->word . '.' . fake()->word,
        'value' => fake()->text,
    ])->create();

    /** @var \Codedor\FilamentSettings\Drivers\DriverInterface $repo */
    $repo = app(DriverInterface::class);

    expect($repo->get($setting->key))
        ->toBeString($setting->value);
});

it('returns default value', function () {
    /** @var \Codedor\FilamentSettings\Drivers\DriverInterface $repo */
    $repo = app(DriverInterface::class);

    expect($repo->get('no.setting', 'default-value'))
        ->toBeString('default-value');
});

it('return true if setting exists', function () {
    $setting = Setting::factory([
        'key' => fake()->word . '.' . fake()->word,
        'value' => fake()->text,
    ])->create();

    /** @var \Codedor\FilamentSettings\Drivers\DriverInterface $repo */
    $repo = app(DriverInterface::class);

    expect($repo->has($setting->key))
        ->toBeTrue();
});

it('return false if setting does not exist', function () {
    Setting::factory([
        'key' => fake()->word . '.' . fake()->word,
        'value' => fake()->text,
    ])->create();

    /** @var \Codedor\FilamentSettings\Drivers\DriverInterface $repo */
    $repo = app(DriverInterface::class);

    expect($repo->has(fake()->word))
        ->toBeFalse();
});

it('forgets a setting', function () {
    $setting = Setting::factory([
        'key' => fake()->word . '.' . fake()->word,
        'value' => fake()->text,
    ])->create();

    /** @var \Codedor\FilamentSettings\Drivers\DriverInterface $repo */
    $repo = app(DriverInterface::class);
    $repo->forget($setting->key);

    assertDatabaseMissing(Setting::class, ['id' => $setting->id]);
});

it('saves setting provided with key value', function () {
    $key = fake()->word;
    $value = fake()->word;

    /** @var \Codedor\FilamentSettings\Drivers\DriverInterface $repo */
    $repo = app(DriverInterface::class);
    $repo->set($key, $value);

    assertDatabaseCount(Setting::class, 1);
    assertDatabaseHas(Setting::class, [
        'key' => $key,
        'value' => $value,
    ]);
});

it('saves setting provided with key value array', function () {
    $data = [];

    for ($i = 0; $i < 5; $i++) {
        $data[] = [
            'key' => fake()->word,
            'value' => fake()->word,
        ];
    }

    /** @var \Codedor\FilamentSettings\Drivers\DriverInterface $repo */
    $repo = app(DriverInterface::class);
    $repo->set($data);

    assertDatabaseCount(Setting::class, count($data));
    foreach ($data as $setting) {
        assertDatabaseHas(Setting::class, $setting);
    }
});
