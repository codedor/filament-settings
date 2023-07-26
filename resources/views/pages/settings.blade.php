<x-filament::page :header-widgets-columns="4">

    <form wire:submit.prevent="submit" class="space-y-6">
        {{ $this->form }}

        <x-filament::button type="submit">
            {{ __('filament-settings::save') }}
        </x-filament::button>
    </form>

</x-filament::page>
