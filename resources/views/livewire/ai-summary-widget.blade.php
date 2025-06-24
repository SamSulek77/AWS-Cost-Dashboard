<div class="flex items-center justify-center space-x-2 space-y-4">
    <x-filament::button wire:click="summarize">
        Generate AI Summary
    </x-filament::button>

    @if ($summary)
    <div class="mt-6 p-6 bg-white dark:bg-gray-900 rounded-xl shadow-lg border border-blue-500/20">
        <div class="flex items-center gap-2 mb-3">
            <x-heroicon-o-sparkles class="w-6 h-6 text-blue-600" />
            <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-400">AI Summary Report</h3>
        </div>
        <p class="text-sm text-gray-800 dark:text-gray-100 leading-relaxed whitespace-pre-line">
            {{ $summary }}
        </p>
        <div class="class= flex items-center justify-end space-x-2">
        <x-filament::button wire:click="exportSummary" color="gray" icon="heroicon-m-arrow-down-tray">
        Export Summary (.txt)
        </x-filament::button>
        </div>

      

    </div>
    @endif
</div>
