<x-filament-panels::page>
    {{-- Metrics Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <x-filament::card>
    <div class="flex items-center justify-between">
        <div x-data="{ count: 0 }" x-init="let target = 25333245.22;
            let duration = 99999999;
            let stepTime = Math.abs(Math.floor(duration / target));
            let counter = setInterval(() => {
                if (count < target) {
                    count += 100000; // You can adjust this step to be smoother
                    if (count > target) count = target;
                } else {
                    clearInterval(counter);
                }
            }, stepTime);"
        >
            <h2 class="text-xl font-bold">Total AWS Cost</h2>
            <p class="text-3xl text-green-600 mt-2">
                $<span x-text="count.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })"></span>
            </p>
        </div>
        <img src="{{ asset('images/z_aws.png') }}" alt="Total AWS" class="w-16 h-16 object-contain ml-4">
    </div>
</x-filament::card>


<x-filament::card>
    <div class="flex items-center justify-between">
        <div x-data="{ count: 0 }" x-init="
            let target = 1210.44;
            let duration = 1500;
            let stepTime = Math.abs(Math.floor(duration / target));
            let counter = setInterval(() => {
                if (count < target) {
                    count += 10;
                    if (count > target) count = target;
                } else {
                    clearInterval(counter);
                }
            }, stepTime);"
        >
            <h2 class="text-xl font-bold">Top Service</h2>
            <p class="text-lg mt-2">
                Amazon EC2 - $<span x-text="count.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })"></span>
            </p>
        </div>
        <img src="{{ asset('images/ZEC2_aws.png') }}" alt="EC2" class="w-16 h-16 object-contain ml-4">
    </div>
</x-filament::card>


<x-filament::card>
    <div class="flex items-center justify-between">
        <div x-data="{ count: 0 }" x-init="
            let target = 4800.00;
            let duration = 1500;
            let stepTime = Math.abs(Math.floor(duration / target));
            let counter = setInterval(() => {
                if (count < target) {
                    count += 20;
                    if (count > target) count = target;
                } else {
                    clearInterval(counter);
                }
            }, stepTime);"
        >
            <h2 class="text-xl font-bold">Forecast</h2>
            <p class="text-lg text-orange-500 mt-2">
                $<span x-text="count.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })"></span>
            </p>
        </div>
        <img src="{{ asset('images/zf_aws.png') }}" alt="Forecast" class="w-16 h-16 object-contain ml-4">
    </div>
</x-filament::card>

 

 
</div>

    


    {{-- Line Chart Widget --}}
    <div class="mt-8">
        @livewire("App\\Filament\\Widgets\\AwsCostLineChart")
        <div class="flex justify-end mb-2">
    
    </div>
    </div>

    {{-- Pie Chart Widget --}}
<div class="mt-8" class="spinning-chart-wrapper">
    @livewire("App\\Filament\\Widgets\\AwsCostPieChart")
   
</div>



<div x-data="{ showAlert: false, alertMessage: '' }"
     x-on:csv-exported.window="
        alertMessage = $event.detail.message;
        showAlert = true;
        setTimeout(() => showAlert = false, 4000);
    "
     class="relative"
>
    @livewire('app.filament.widgets.aws-cost-table-widget')

    <div x-show="showAlert"
         x-transition
         class="fixed bottom-6 right-6 max-w-sm bg-blue-100 border border-blue-300 text-blue-900 text-sm rounded-lg shadow-lg p-4"
         style="display: none;"
    >
        <div class="flex items-center gap-2">
            <x-heroicon-o-check-circle class="w-5 h-5 text-blue-600" />
            <span x-text="alertMessage"></span>
        </div>
    </div>
</div>

    <div class="text-right mt-4">
    @livewire('aws-cost-csv-export')
    </div>


<div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        @livewire('ai-summary-widget')
        <div x-data
            x-on:notify.window="alert($event.detail.message)">
        </div>
    </div>

    <div>
        @livewire('chatbot')
    </div>
</div>

</x-filament-panels::page>
