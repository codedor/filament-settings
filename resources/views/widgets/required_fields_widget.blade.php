<x-filament-widgets::widget>
    <x-filament::card>
        <h2 class="flex-1 text-lg font-bold">{{ __('filament-settings::widget.required fields title') }}</h2>

        @if ($requiredKeys?->count())
            <ul class="space-y-2">
                @foreach($requiredKeys as $key => $data)
                    <li class="flex gap-3 w-full">
                        @if (setting($key))
                            <x-filament::icon icon="heroicon-o-check-circle" class="success" />
                        @else
                            <x-filament::icon icon="heroicon-o-exclamation-circle" class="danger" />
                        @endif

                        <a href="{{ \Codedor\FilamentSettings\Pages\Settings::getUrl([
                            'tab' => $data['tab'] ?? '',
                            'focus' => $key
                        ]) }}" class="flex-1">
                            {{ $key }} - {{ setting($key) ? __('filament-settings::widget.setting ok') : __('filament-settings::widget.setting needs check') }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </x-filament::card>
</x-filament-widgets::widget>
