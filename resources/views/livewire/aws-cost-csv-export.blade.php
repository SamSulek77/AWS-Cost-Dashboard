<div class="text-right mt-4">
    <form wire:submit.prevent="exportCsv">
        <x-filament::button type="submit" color="success" icon="heroicon-m-arrow-down-tray">
            Export CSV
        </x-filament::button>
    </form>
</div>