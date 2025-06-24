<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\AwsCostExportController;

Route::get('/export/aws-cost/csv', [AwsCostExportController::class, 'exportCsv'])->name('export.aws-cost.csv');
Route::get('/export/aws-cost/pdf', [AwsCostExportController::class, 'exportPdf'])->name('export.aws-cost.pdf');



use OpenAI\Laravel\Facades\OpenAI;

Route::get('/test-ai', function () {
    $response = OpenAI::chat()->create([
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            ['role' => 'user', 'content' => 'Summarize AWS billing data in simple terms.'],
        ],
    ]);

    return $response->choices[0]->message->content;
});
