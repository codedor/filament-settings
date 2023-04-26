<x-filament::widget>
    <x-filament::card>
        <div class="flex gap-3 w-full">
            <x-notifications::icon icon="heroicon-o-exclamation-circle" color="danger" />

            <div class="flex-1">{{ __('filament-settings::widget.required fields title') }}</div>
        </div>

        @if ($requiredKeys?->count())
            <div>
                @foreach($requiredKeys as $requiredKey)
                    <div>
                        {{ $requiredKey }} - {{ setting($requiredKey) ? __('filament-settings::widget.setting ok') : __('filament-settings::widget.setting needs check') }}
                    </div>
                @endforeach
            </div>
        @endif
    </x-filament::card>
</x-filament::widget>
