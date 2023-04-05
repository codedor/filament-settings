<x-filament::page>

    <form wire:submit.prevent="submit">
        {{ $this->form }}

        <button type="submit">
            Submit
        </button>
    </form>

</x-filament::page>
