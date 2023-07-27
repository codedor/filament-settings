<x-filament-widgets::widget>
    <x-filament::card>
        <h2 class="flex-1 text-lg font-bold">{{ __('filament-settings::widget.required fields title') }}</h2>

        @if ($requiredKeys?->count())
            <ul class="space-y-2">
                @foreach($requiredKeys as $key => $data)
                    <li class="flex gap-3 w-full">
                        @php
                            $color = setting($key) ? 'success' : 'danger';
                            $icon = setting($key) ? 'heroicon-o-check-circle' : 'heroicon-o-exclamation-circle';
                        @endphp
                        <x-filament::icon
                            :icon="$icon"
                            class="h-6 w-6 text-custom-400"
                            style="{{ \Filament\Support\get_color_css_variables($color, shades: [400]) }}"
                        />

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
