<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class ImportAwsCostData extends Command
{
    protected $signature = 'import:aws-cost';
    protected $description = 'Import AWS cost data from CSV into the database';

    public function handle()
    {
        $filePath = storage_path('app/aws/439828644544-aws-cost-allocation-2025-06 copy.csv');
        $filePath = storage_path('app/aws/aws-cost-allocation-2025-07-dummy copy.csv');
        $filePath = storage_path('app/aws/aws-cost-allocation-2025-08-dummy copy.csv');
        $filePath = storage_path('app/aws/aws-cost-allocation-2025-09-dummy copy.csv');
        $filePath = storage_path('app/aws/aws-cost-allocation-2025-10-dummy copy.csv');

        if (!file_exists($filePath)) {
            $this->error("File not found at: $filePath");
            return;
        }

        $csv = Reader::createFromPath($filePath, 'r');
        $csv->setHeaderOffset(1); // Skip the first line, use second line as header

        $records = $csv->getRecords();
        $inserted = 0;

        foreach ($records as $record) {
            $name = $record['LinkedAccountName'] ?? null;
            $date = $record['UsageEndDate'] ?? null;
            $cost = (float) ($record['TotalCost'] ?? 0);

            if ($name && $date) {
                DB::table('aws_costs')->insert([
                    'LinkedAccountName' => $name,
                    'UsageEndDate' => $date,
                    'totalCost' => $cost,
                ]);
                $inserted++;
            }
        }

        $this->info("Import complete. Rows inserted: $inserted");
    }
}
