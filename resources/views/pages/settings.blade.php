<x-filament::page :header-widgets-columns="4">

    <form wire:submit.prevent="submit">
        {{ $this->form }}

        <x-filament-support::button type="submit">
            Submit
        </x-filament-support::button>
    </form>

</x-filament::page>
