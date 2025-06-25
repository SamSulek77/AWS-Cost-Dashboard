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
        Tables\Filters\SelectFilter::make('month')
            ->label('Filter by Month')
            ->options($months)
            ->default(array_key_first($months))
            ->query(function (Builder $query, $value) {
                $query->whereRaw("DATE_FORMAT(UsageEndDate, '%Y-%m') = ?", [$value]);
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
               TextColumn::make('LinkedAccountName')->label('Account Name')->sortable()->searchable(),

                TextColumn::make('total_cost')
                    ->label('Total Cost (USD)')
                    ->formatStateUsing(fn($state) => '$' . number_format($state, 2)),
            ]);
    }

    public function getTableRecordKey(Model $record): string
    {
        return md5($record->LinkedAccountName); // ensures uniqueness
    }

    public function exportCsv()
    {
        $selectedMonth = $this->selectedMonth ?? AwsCost::query()
            ->selectRaw("MAX(DATE_FORMAT(UsageEndDate, '%Y-%m')) as latest")
            ->value('latest');

        $records = AwsCost::query()
            ->selectRaw("MIN(id) as id, LinkedAccountName, SUM(totalCost) as total_cost")
            ->whereRaw("DATE_FORMAT(UsageEndDate, '%Y-%m') = ?", [$selectedMonth])
            ->groupBy('LinkedAccountName')
            ->get();

        $csvContent = "LinkedAccountName,TotalCost\n";
        foreach ($records as $record) {
            $csvContent .= "\"{$record->LinkedAccountName}\",\"{$record->total_cost}\"\n";
        }

        $filename = 'aws-cost-' . now()->format('Ymd_His') . '.csv';

        // Fire notification to browser (Alpine listener)
        $this->dispatchBrowserEvent('csv-exported', [
            'message' => 'AWS Cost CSV has been exported!',
        ]);

        return response()->streamDownload(function () use ($csvContent) {
            echo $csvContent;
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function getFooter(): ?\Illuminate\Contracts\View\View
    {
        return view('filament.widgets.exports.aws-cost-table-footer', [
            'widget' => $this,
        ]);
    }
}
