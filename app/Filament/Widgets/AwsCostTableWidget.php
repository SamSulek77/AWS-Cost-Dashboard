<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\AwsCost;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AwsCostTableWidget extends BaseWidget
{
    public ?string $selectedMonth = null;

    public function getHeading(): string
    {
        return 'AWS Cost Table - ' . (
            $this->selectedMonth
                ? Carbon::createFromFormat('Y-m', $this->selectedMonth)->format('F Y')
                : 'Latest Month'
        );
    }

    public function getTableFilters(): array
    {
        $months = AwsCost::query()
            ->selectRaw("DISTINCT DATE_FORMAT(UsageEndDate, '%Y-%m') as month")
            ->orderByDesc('month')
            ->pluck('month')
            ->mapWithKeys(fn($month) => [
                $month => Carbon::createFromFormat('Y-m', $month)->format('F Y')
            ])
            ->toArray();

        return [
            Tables\Filters\SelectFilter::make('selectedMonth')
                ->label('Filter by Month')
                ->options($months)
                ->default(array_key_first($months))
                ->afterStateUpdated(function (callable $set, $state) {
                    $set('selectedMonth', $state); // Sync the Livewire property
                }),
        ];
    }

    public function table(Table $table): Table
    {
        $month = $this->selectedMonth ?? AwsCost::query()
            ->selectRaw("MAX(DATE_FORMAT(UsageEndDate, '%Y-%m')) as latest")
            ->value('latest');

        return $table
            ->query(
                AwsCost::query()
                    ->selectRaw("LinkedAccountName, SUM(totalCost) as total_cost")
                    ->whereRaw("DATE_FORMAT(UsageEndDate, '%Y-%m') = ?", [$month])
                    ->groupBy('LinkedAccountName')
                    ->orderByRaw('SUM(totalCost) DESC')
            )
            ->columns([
                TextColumn::make('LinkedAccountName')->label('Account Name'),
                TextColumn::make('total_cost')
                    ->label('Total Cost (USD)')
                    ->formatStateUsing(fn($state) => '$' . number_format($state, 2)),
            ]);
    }

    public function getTableRecordKey(Model $record): string
    {
        return md5($record->LinkedAccountName); // Ensures unique key
    }

    public function getFooter(): ?\Illuminate\Contracts\View\View
    {
        return view('filament.widgets.exports.aws-cost-table-footer');
        ([
            'widget' => $this,
        ]);
    }

        public function exportCsv()
    {
        $selectedMonth = $this->filter ?? AwsCost::query()
            ->selectRaw("MAX(DATE_FORMAT(UsageEndDate, '%Y-%m')) as latest")
            ->value('latest');

        $records = AwsCost::query()
            ->selectRaw("LinkedAccountName, SUM(totalCost) as total_cost")
            ->whereRaw("DATE_FORMAT(UsageEndDate, '%Y-%m') = ?", [$selectedMonth])
            ->groupBy('LinkedAccountName')
            ->orderByDesc('total_cost')
            ->get();

        $filename = 'aws-costs-' . now()->format('Y-m-d_H-i-s') . '.csv';

        return response()->streamDownload(function () use ($records) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['LinkedAccountName', 'Total Cost (USD)']);
            foreach ($records as $record) {
                fputcsv($handle, [$record->LinkedAccountName, number_format($record->total_cost, 2)]);
            }
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    


}
