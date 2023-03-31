<?php

namespace Codedor\FilamentSettings\Pages;

use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;

class Settings extends Page
{
    protected static string $view = 'filament-settings::pages.settings';

    public function mount()
    {
        $this->form->fill();
    }

    public function submit()
    {
        $state = $this->form->getState();
        dd($state);
    }

    protected function getFormSchema(): array
    {
        return [
            Tabs::make('Settings')
                ->persistTabInQueryString()
                ->tabs([
                    Tabs\Tab::make('Site')
                        ->schema([
                            TextInput::make('site.name'),
                        ]),
                    Tabs\Tab::make('Mail')
                        ->schema([
                            TextInput::make('mail.from')
                                ->required(),
                        ]),
                    Tabs\Tab::make('Mollie')
                        ->schema([
                            TextInput::make('mollie.client-secret')
                                ->required(),
                        ]),
                ]),
        ];
    }
}
