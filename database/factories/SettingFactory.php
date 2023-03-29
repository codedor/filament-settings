<?php

namespace Codedor\FilamentSettings\Database\Factories;

use Codedor\FilamentSettings\Models\Setting;
use Illuminate\Database\Eloquent\Factories\Factory;

class SettingFactory extends Factory
{
    protected $model = Setting::class;

    public function definition()
    {
        return [
            'key' => $this->faker->word,
            'value' => $this->faker->sentence,
        ];
    }
}
