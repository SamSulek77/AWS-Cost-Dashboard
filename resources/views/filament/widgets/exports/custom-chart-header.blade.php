{{-- resources/views/filament/widgets/custom-chart-header.blade.php --}}
<div class="fi-wi-chart-header flex items-center justify-between gap-x-3 px-6 py-4">
    <div>
        <h3 class="fi-wi-chart-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">
            {{ $heading }}
        </h3>
        @if($description)
            <p class="fi-wi-chart-description text-sm text-gray-500 dark:text-gray-400 mt-1">
                {{ $description }}
            </p>
        @endif
    </div>
    
    <div class="flex items-center gap-x-3">
        {{-- Month Filter --}}
        @if($filters)
            <div class="fi-wi-chart-filter">
                <select 
                    wire:model.live="filter"
                    class="fi-select-input block w-full rounded-lg border-none bg-white py-1.5 pe-8 ps-3 text-sm text-gray-950 shadow-sm ring-1 ring-inset ring-gray-950/10 transition duration-75 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 disabled:bg-gray-50 disabled:text-gray-500 disabled:ring-gray-200 dark:bg-white/5 dark:text-white dark:ring-white/20 dark:placeholder:text-gray-500 dark:focus:ring-primary-500 dark:disabled:bg-transparent dark:disabled:ring-white/10"
                >
                    @foreach($filters as $value => $label)
                        <option value="{{ $value }}" {{ $selectedFilter == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif
        
        {{-- Export Buttons --}}
        <div class="flex items-center gap-x-2">
            <button 
                wire:click="exportCsv"
                class="inline-flex items-center justify-center gap-x-1 rounded-lg px-3 py-2 text-sm font-medium text-gray-700 bg-white ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-300 dark:ring-gray-600 dark:hover:bg-gray-700"
                type="button"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                CSV
            </button>
            
            <button 
                wire:click="exportExcel"
                class="inline-flex items-center justify-center gap-x-1 rounded-lg px-3 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500"
                type="button"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Excel
            </button>
        </div>
    </div>
</div>