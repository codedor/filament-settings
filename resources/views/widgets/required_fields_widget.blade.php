<x-filament::widget>
    <x-filament::card>
        <h2 class="flex-1 text-lg font-bold">{{ __('filament-settings::widget.required fields title') }}</h2>

        @if ($requiredKeys?->count())
            <ul class="space-y-2">
                @foreach($requiredKeys as $requiredKey)
                    <li class="flex gap-3 w-full">
                        {{-- TODO BE: Update on save (in settings view) --}}
                        @if (setting($requiredKey))
                            <x-notifications::icon icon="heroicon-o-check-circle" color="success" />
                        @else
                            <x-notifications::icon icon="heroicon-o-exclamation-circle" color="danger" />
                        @endif

                        {{-- TODO BE: Check route --}}
                        {{-- TODO BE: Open the correct tab (and focus the correct field) --}}
                        <a href="/admin/settings" class="flex-1">
                            {{ $requiredKey }} - {{ setting($requiredKey) ? __('filament-settings::widget.setting ok') : __('filament-settings::widget.setting needs check') }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </x-filament::card>
</x-filament::widget>
