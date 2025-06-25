<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\LineChartWidget;
use Illuminate\Support\Facades\DB;

class AwsCostLineChart extends LineChartWidget
{
    protected static ?string $heading = 'AWS Cost Over Time';

    public ?string $filter = 'all';

    public function getFilters(): ?array
    {
        $accounts = DB::table('aws_costs')
            ->select('LinkedAccountName')
            ->distinct()
            ->pluck('LinkedAccountName')
            ->toArray();

        return [
            'all' => 'All Accounts',
            ...collect($accounts)->mapWithKeys(fn($acc) => [$acc => $acc])->toArray(),
        ];
    }

    protected function getData(): array
    {
        $months = DB::table('aws_costs')
            ->selectRaw("DISTINCT DATE_FORMAT(UsageEndDate, '%Y-%m') as month")
            ->orderBy('month')
            ->pluck('month')
            ->toArray();

        $query = DB::table('aws_costs')
            ->selectRaw("LinkedAccountName, DATE_FORMAT(UsageEndDate, '%Y-%m') as month, SUM(totalCost) as total_cost")
            ->groupBy('LinkedAccountName', 'month')
            ->orderBy('month');

        if ($this->filter !== 'all') {
            $query->where('LinkedAccountName', $this->filter);
        }

        $results = $query->get();

        $grouped = $results->groupBy('LinkedAccountName');

        // Define consistent color map for each account
        $colorMap = [
            'Monitoring' => '#FF6384',
            'RONPOS (Staging)' => '#36A2EB',
            'Celebshare' => '#FFCE56',
            's.ron.ac' => '#4BC0C0',
            'Operations' => '#9966FF',
            'getorders' => '#FF9F40',
            'ron.ac' => '#00A86B',
            'masterpayer-10078689' => '#C71585',
            'rackspace-bishdtr-02-10078689' => '#20B2AA',
            'BHP Site System' => '#FFD700',
            'Slurp' => '#ADFF2F',
            'RONPOS (UAT)' => '#FF4500',
            'rackspace-bishdtr-01-10078689' => '#1E90FF',
        ];

        $datasets = [];

        foreach ($grouped as $account => $records) {
            $costsByMonth = $records->pluck('total_cost', 'month')->toArray();
            $data = [];
            foreach ($months as $month) {
                $data[] = $costsByMonth[$month] ?? 0;
            }

            $color = $colorMap[$account] ?? '#999999'; // fallback color if not mapped

            $datasets[] = [
                'label' => $account,
                'data' => $data,
                'borderColor' => $color,
                'backgroundColor' => $color,
                'fill' => false,
            ];
        }

        return [
            'labels' => $months,
            'datasets' => $datasets,
        ];
    }

    public function getColumnSpan(): int|string|array
    {
        return 'full';
    }

    public function getId(): string
    {
        return 'my-chart-id'; // must match the one in JS and blade
    }

    protected function getOptions(): array
    {
        return [
            'animation' => [
            'duration' => 1500, // 1.5 seconds
            'easing' => 'easeInOutQuart',
            ],
            'plugins' => [
                'legend' => [
                'display' => true,
                'position' => 'top',
                ],
            ],
                'scales' => [
                'y' => [
                'beginAtZero' => true,
                ],
         ],
        ];
    }
}
