<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class CostBreakdownPieChart extends ChartWidget
{
    protected static ?string $heading = 'Cost Breakdown (Pie Chart)';

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getData(): array
    {
        $records = DB::table('account_names')
            ->select('accName', DB::raw('SUM(cost) as total_cost'))
            ->groupBy('accName')
            ->get();

        $labels = $records->pluck('accName')->toArray();
        $data = $records->pluck('total_cost')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Total Cost',
                    'data' => $data,
                    'backgroundColor' => [
                        '#F87171', '#60A5FA', '#34D399', '#FBBF24', '#A78BFA', '#F472B6',
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }
}
