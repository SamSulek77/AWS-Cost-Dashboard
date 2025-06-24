<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BlogPostsChart extends ChartWidget
{
    protected static ?string $heading = 'Cost by Account Name';

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $rawRecords = DB::table('account_names')
            ->selectRaw("accName, STR_TO_DATE(date, '%d/%m/%Y') as parsed_date, cost")
            ->orderByRaw("STR_TO_DATE(date, '%d/%m/%Y')")
            ->get();

        $grouped = [];

        foreach ($rawRecords as $record) {
            $date = Carbon::parse($record->parsed_date)->format('d M Y');
            $grouped[$record->accName][$date] = $record->cost;
        }

        // Get all unique, sorted dates as labels
        $allDates = collect($rawRecords)
            ->map(fn($r) => Carbon::parse($r->parsed_date)->format('d M Y'))
            ->unique()
            ->sort()
            ->values();

        $datasets = [];

        foreach ($grouped as $accName => $dateCostMap) {
            $datasets[] = [
                'label' => $accName,
                'data' => $allDates->map(fn($date) => $dateCostMap[$date] ?? null)->toArray(), // use null to show gaps instead of 0
                'fill' => false,
                'borderColor' => '#' . substr(md5($accName), 0, 6), // consistent unique color
                'tension' => 0.3,
            ];
        }

        return [
            'labels' => $allDates->toArray(),
            'datasets' => $datasets,
        ];
    }
}
