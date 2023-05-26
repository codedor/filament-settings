<?php

namespace Codedor\FilamentSettings\Pages;

use Codedor\FilamentSettings\Drivers\DriverInterface;
use Codedor\FilamentSettings\Repositories\SettingTabRepository;
use Codedor\FilamentSettings\Widgets\RequiredFieldsWidget;
use Filament\Forms\Components\Tabs;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class Settings extends Page
{
    protected static string $view = 'filament-settings::pages.settings';

    public string $focus = '';

    protected $queryString = [
        'focus' => ['except' => ''],
    ];

    public function mount()
    {
        $this->form->fill();
    }

    public function submit()
    {
        /** @var \Codedor\FilamentSettings\Drivers\DriverInterface $repository */
        $repository = app(DriverInterface::class);

        $data = [];

         foreach ($this->form->getState() as $prepend => $value) {
            foreach ($value as $key => $item) {
                $data["$prepend.$key"] = is_array($item) ? json_encode($item) : $item;
            }
         }

        collect($data)->each(fn ($value, $key) => $repository->set($key, $value));

        Notification::make()
            ->title('Settings')
            ->body(__('filament-settings::admin.saved'))
            ->success()
            ->send();

        $this->emit('filament-settings::refresh-widget');
    }

    protected function getFormSchema(): array
    {
        /** @var SettingTabRepository $rep */
        $rep = app(SettingTabRepository::class);

        return [
            Tabs::make('Settings')
                ->persistTabInQueryString()
                ->tabs($rep->toTabsSchema($this->focus)),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            RequiredFieldsWidget::class,
        ];
    }
}
