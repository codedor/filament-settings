# Filament Settings

## Introduction

## Installation

Installation is pretty easy. Just run the following command.

```bash
composer require codedor/filament-settings
```

## Usage

### Register settings

Add the following file `Tab.php` to the `App/Settings` folder.

```php
<?php

namespace App\Settings;

use Codedor\FilamentSettings\Settings\SettingsInterface;

class Tab implements SettingsInterface
{
    public static function schema(): array
    {
        return [];
    }
}
```

and register the tab in the `filament-settings.php` file.

```php
<?php

...
use App\Settings\Tab;

return [
    ...
    'tabs' => [
        Tab::class,
    ],
];
```

### Defining settings

This package uses the Filament fields to enable easy form building. Just return the wanted fields with all it's options
in the `schema` method.

```php

use Filament\Forms\Components\TextInput;

public static function schema(): array
{
    return [
        TextInput::make('site.name')
            ->rules([
                ...
            ]),  
    ];
}
```

### Validation

#### Required fields

To have a better user experience, it is highly recommended to use
the `Codedor\FilamentSettings\Rules\SettingsMustBeFilledIn` validation rule instead of the default `required` rule.

This way, users are still able to save the settings when 'required' settings are not yet in possession.
All rules that have the SettingsMustBeFilledIn attached will appear in the RequiredFields widget on the dashboard and
settings page. This way, users will be reminded to fill in the required settings. 

