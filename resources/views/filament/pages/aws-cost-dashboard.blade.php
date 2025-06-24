<x-filament-panels::page>
    {{-- Metrics Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <x-filament::card>
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold">Total AWS Cost</h2>
                <p class="text-3xl text-green-600 mt-2">$25,333,245.22</p>
            </div>
            <img src="{{ asset('images/z_aws.png') }}" alt="Total AWS" class="w-16 h-16 object-contain ml-4">
        </div>
    </x-filament::card>

    <x-filament::card>
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold">Top Service</h2>
                <p class="text-lg mt-2">Amazon EC2 - $1,210.44</p>
            </div>
            <img src="{{ asset('images/ZEC2_aws.png') }}" alt="EC2" class="w-16 h-16 object-contain ml-4">
        </div>
    </x-filament::card>

    <x-filament::card>
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold">Forecast</h2>
                <p class="text-lg text-orange-500 mt-2">$4,800.00</p>
            </div>
            <img src="{{ asset('images/zf_aws.png') }}" alt="Forecast" class="w-16 h-16 object-contain ml-4">
        </div>
    </x-filament::card>

 

 
</div>

    


    {{-- Line Chart Widget --}}
    <div class="mt-8">
        @livewire("App\\Filament\\Widgets\\AwsCostLineChart")
        <div class="flex justify-end mb-2">
    <x-filament::button color="gray" onclick="downloadChartImage('my-chart-id', 'chart.png')">
        Export Chart as PNG
    </x-filament::button>
    <script>
    function downloadChartImage(chartId, filename) {
        const canvas = document.querySelector(`#${chartId} canvas`);
        if (!canvas) {
            alert("Chart canvas not found.");
            return;
        }

        const link = document.createElement('a');
        link.href = canvas.toDataURL('image/png');
        link.download = filename;
        link.click();
    }
    </script>
    </div>
    </div>

    {{-- Pie Chart Widget --}}
<div class="mt-8" class="spinning-chart-wrapper">
    @livewire("App\\Filament\\Widgets\\AwsCostPieChart")
    <div class="flex justify-end mb-2">
    <x-filament::button color="gray" onclick="downloadChartImage('my-chart-id', 'chart.png')">
        Export Chart as PNG
    </x-filament::button>
    <script>
    function downloadChartImage(chartId, filename) {
        const canvas = document.querySelector(`#${chartId} canvas`);
        if (!canvas) {
            alert("Chart canvas not found.");
            return;
        }

        const link = document.createElement('a');
        link.href = canvas.toDataURL('image/png');
        link.download = filename;
        link.click();
    }
    </script>

    <style>
        .spinning-chart-wrapper {   
        display: inline-block;
        animation: spin 1s linear infinite;
        }

        .spinning-chart-wrapper:hover {
        animation-play-state: paused;
        }

        @keyframes spin {
        0% {
            transform: rotate(0deg);
            }

        100% {
            transform: rotate(360deg);
        }
        }
    </style>

    </div>
</div>

<div class="mt-6">
    @livewire('ai-summary-widget')
</div>


@livewire('chatbot')




</x-filament-panels::page>
