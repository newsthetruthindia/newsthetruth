<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}

        <div class="mt-6">
            {{-- Form actions if needed --}}
        </div>
    </form>
</x-filament-panels::page>
