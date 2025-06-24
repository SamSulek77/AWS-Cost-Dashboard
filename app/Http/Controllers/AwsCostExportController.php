<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;

class AwsCostExportController extends Controller
{
    public function exportCsv(Request $request): StreamedResponse
    {
        $month = $request->query('month');

        $records = DB::table('aws_costs')
            ->select('LinkedAccountName', DB::raw('SUM(totalCost) as total_cost'))
            ->whereRaw("DATE_FORMAT(UsageEndDate, '%Y-%m') = ?", [$month])
            ->groupBy('LinkedAccountName')
            ->orderByDesc('total_cost')
            ->get();

        $filename = "aws_cost_{$month}.csv";

        return response()->streamDownload(function () use ($records) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['LinkedAccountName', 'TotalCost']);

            foreach ($records as $row) {
                fputcsv($file, [$row->LinkedAccountName, $row->total_cost]);
            }

            fclose($file);
        }, $filename);
    }

    public function exportPdf(Request $request)
    {
        $month = $request->query('month');

        $records = DB::table('aws_costs')
            ->select('LinkedAccountName', DB::raw('SUM(totalCost) as total_cost'))
            ->whereRaw("DATE_FORMAT(UsageEndDate, '%Y-%m') = ?", [$month])
            ->groupBy('LinkedAccountName')
            ->orderByDesc('total_cost')
            ->get();

        $pdf = Pdf::loadView('exports.aws-cost-pdf', [
            'records' => $records,
            'month' => Carbon::createFromFormat('Y-m', $month)->format('F Y'),
        ]);

        return $pdf->download("aws_cost_{$month}.pdf");
    }
}

