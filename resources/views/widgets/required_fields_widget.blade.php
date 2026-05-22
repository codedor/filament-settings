<x-filament-widgets::widget>
    @if ($requiredKeys?->count())
        <x-filament::card>
            <h2 class="flex-1 text-lg font-bold pb-4">
                {{ __('filament-settings::widget.required fields title') }}
            </h2>

            <ul class="space-y-2">
                @foreach($requiredKeys as $key => $data)
                    <li class="flex gap-3 w-full">
                        <x-filament::icon
                            icon="heroicon-o-exclamation-circle"
                            class="h-6 w-6 text-custom-400"
                            style="{{ \Filament\Support\get_color_css_variables('danger', shades: [400]) }}"
                        />

                        <a href="{{ \Wotz\FilamentSettings\Pages\Settings::getUrl([
                            'tab' => $data['tab'] ?? '',
                            'focus' => $key
                        ]) }}" class="flex-1">
                            {{ $data['label'] }} - {{ __('filament-settings::widget.setting needs check') }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </x-filament::card>
    @endif
</x-filament-widgets::widget>
