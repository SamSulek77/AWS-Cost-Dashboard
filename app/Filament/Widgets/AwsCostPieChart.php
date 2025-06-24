<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use OpenAI\Laravel\Facades\OpenAI;

class AwsCostPieChart extends ChartWidget
{
    public ?string $summary = null;

    protected static ?string $maxHeight = '500px';

    protected function getType(): string
    {
        return 'pie';
    }

    public function getHeading(): string
    {
        return 'AWS Cost Breakdown - ' . (
            $this->filter
                ? Carbon::createFromFormat('Y-m', $this->filter)->format('F Y')
                : 'Latest Month'
        );
    }

    public function getFilters(): ?array
    {
        return DB::table('aws_costs')
            ->selectRaw("DISTINCT DATE_FORMAT(UsageEndDate, '%Y-%m') as month")
            ->orderBy('month', 'desc')
            ->pluck('month')
            ->mapWithKeys(fn($month) => [
                $month => Carbon::createFromFormat('Y-m', $month)->format('F Y')
            ])
            ->toArray();
    }

    protected function getData(): array
    {
        $selectedMonth = $this->filter ?? DB::table('aws_costs')
            ->selectRaw("MAX(DATE_FORMAT(UsageEndDate, '%Y-%m')) as latest_month")
            ->value('latest_month');

        $records = DB::table('aws_costs')
            ->selectRaw("LinkedAccountName, SUM(totalCost) as total_cost")
            ->whereRaw("DATE_FORMAT(UsageEndDate, '%Y-%m') = ?", [$selectedMonth])
            ->groupBy('LinkedAccountName')
            ->orderByDesc('total_cost')
            ->get();

        $labels = $records->pluck('LinkedAccountName')->toArray();
        $data = $records->pluck('total_cost')->toArray();

        $colors = [
            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40',
            '#00A86B', '#C71585', '#20B2AA', '#FFD700', '#ADFF2F', '#FF4500', '#1E90FF',
        ];

        $backgroundColors = collect($labels)->map(
            fn($_, $i) => $colors[$i % count($colors)]
        );

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Cost',
                    'data' => $data,
                    'backgroundColor' => $backgroundColors->toArray(),
                ],
            ],
        ];
    }

    public function summarize(): void
    {
        $data = $this->getData();
        $text = "Summarize this AWS cost breakdown: ";

        foreach ($data['labels'] as $index => $label) {
            $cost = number_format($data['datasets'][0]['data'][$index], 2);
            $text .= "{$label}: \${$cost}, ";
        }

        $response = OpenAI::chat()->create([
            'model' => 'gpt-4', // or 'gpt-3.5-turbo'
            'messages' => [
                ['role' => 'user', 'content' => $text],
            ],
        ]);

        $this->summary = $response->choices[0]->message->content;
    }

    public function getFooter(): ?\Illuminate\Contracts\View\View
    {
        return view('filament.widgets.exports.pie-summary-footer', [
            'widget' => $this,
            'summary' => $this->summary,
        ]);
    }

    public function exportSummary()
    {
    if (empty($this->summary)) {
        $this->dispatch('notify', type: 'warning', message: 'No summary available to export.');
        return;
    }

    $filename = 'ai-summary-' . now()->format('Y-m-d_H-i') . '.txt';

    return response($this->summary)
        ->header('Content-Type', 'text/plain')
        ->header('Content-Disposition', "attachment; filename={$filename}");
    }


    protected function getOptions(): array
    {

    return [
        'animation' => [
            'animateRotate' => true,
            'duration' => 2000, // 2 seconds
            'easing' => 'easeInOutCubic',
        ],
    ];
    
    }



}
