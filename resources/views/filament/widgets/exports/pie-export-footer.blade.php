{{-- resources/views/filament/widgets/exports/pie-export-footer.blade.php --}}
<div class="px-6 py-3 border-t border-gray-200 dark:border-gray-700">
    <div class="flex items-center justify-between">
        <div class="text-sm text-gray-500 dark:text-gray-400">
            Data for {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}
        </div>
        
        <div class="flex gap-2">
            <button 
                wire:click="exportCsv"
                class="px-3 py-1 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700"
                type="button"
            >
                📄 Export CSV
            </button>
            
            <button 
                wire:click="exportExcel"
                class="px-3 py-1 text-xs font-medium text-white bg-green-600 rounded hover:bg-green-700"
                type="button"
            >
                📊 Export Excel
            </button>
        </div>
    </div>
</div>