<?php

namespace Codedor\FilamentSettings\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SettingMustBeFilledIn implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        dd('sdfsdf');
        // Do nothing since it MAY not fail.
    }
}
