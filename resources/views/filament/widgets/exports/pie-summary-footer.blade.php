<div class="flex items-center justify-between">
    <x-filament::button wire:click="summarize">
        Generate AI Summary
    </x-filament::button>

    @if ($summary)
        <div class="ml-4 p-2 bg-gray-100 text-sm rounded-md w-full mt-2">
            <strong>Summary:</strong> {{ $summary }}
            @livewire("App\\Filament\\Widgets\\AwsCostPieChart")
        </div>
    @endif
</div>
