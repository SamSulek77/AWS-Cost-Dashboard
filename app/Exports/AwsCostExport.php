<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AwsCostExport implements FromArray, WithHeadings, WithStyles, WithTitle
{
    private $selectedMonth;
    private $records;

    public function __construct($selectedMonth)
    {
        $this->selectedMonth = $selectedMonth;
        $this->records = $this->getCostData();
    }

    public function array(): array
    {
        $data = [];
        $monthName = Carbon::createFromFormat('Y-m', $this->selectedMonth)->format('F Y');
        
        foreach ($this->records as $record) {
            $data[] = [
                $record->LinkedAccountName,
                number_format($record->total_cost, 2),
                $monthName
            ];
        }
        
        // Add total row
        $totalCost = $this->records->sum('total_cost');
        $data[] = [
            'TOTAL',
            number_format($totalCost, 2),
            $monthName
        ];
        
        return $data;
    }

    public function headings(): array
    {
        return [
            'Account Name',
            'Total Cost (USD)',
            'Month'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = count($this->records) + 2; // +2 for header and total row
        
        return [
            // Header row styling
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2E8F0']
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ],
            // Total row styling
            $lastRow => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FEF3C7']
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ],
            // All data borders
            "A1:C{$lastRow}" => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ],
        ];
    }

    public function title(): string
    {
        $monthName = Carbon::createFromFormat('Y-m', $this->selectedMonth)->format('F Y');
        return "AWS Cost Breakdown - {$monthName}";
    }

    private function getCostData()
    {
        return DB::table('aws_costs')
            ->selectRaw("LinkedAccountName, SUM(totalCost) as total_cost")
            ->whereRaw("DATE_FORMAT(UsageEndDate, '%Y-%m') = ?", [$this->selectedMonth])
            ->groupBy('LinkedAccountName')
            ->orderByDesc('total_cost')
            ->get();
    }
}