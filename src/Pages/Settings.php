<?php

namespace Codedor\FilamentSettings\Pages;

use Codedor\FilamentSettings\SettingTabRepository;
use Codedor\FilamentSettings\Widgets\RequiredFieldsWidget;
use Filament\Forms\Components\Tabs;
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
        /** @var SettingTabRepository $rep */
        $rep = app(SettingTabRepository::class);

        return [
            Tabs::make('Settings')
                ->persistTabInQueryString()
                ->tabs($rep->toTabsSchema()),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            RequiredFieldsWidget::class,
        ];
    }
}
