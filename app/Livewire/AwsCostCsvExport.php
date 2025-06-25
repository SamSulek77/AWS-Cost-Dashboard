<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\AwsCost;
use Illuminate\Support\Facades\Response;
use Filament\Notifications\Notification;


class AwsCostCsvExport extends Component
{
    public function exportCsv()
    {
        $selectedMonth = AwsCost::query()
            ->selectRaw("MAX(DATE_FORMAT(UsageEndDate, '%Y-%m')) as latest")
            ->value('latest');

        $records = AwsCost::query()
            ->selectRaw("LinkedAccountName, SUM(totalCost) as total_cost")
            ->whereRaw("DATE_FORMAT(UsageEndDate, '%Y-%m') = ?", [$selectedMonth])
            ->groupBy('LinkedAccountName')
            ->get();

        $csvContent = "LinkedAccountName,TotalCost\n";
        foreach ($records as $record) {
            $csvContent .= "\"{$record->LinkedAccountName}\",\"{$record->total_cost}\"\n";
        }

        $filename = 'aws-cost-' . now()->format('Ymd_His') . '.csv';

        Notification::make()
        ->title('Exported')
        ->body('Table exported as CSV file.')
        ->success()
        ->send();

        return Response::streamDownload(function () use ($csvContent) {
            echo $csvContent;
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function render()
    {
        return view('livewire.aws-cost-csv-export');
    }
}
