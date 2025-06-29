<?php

namespace App\Livewire;

use Livewire\Component;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Filament\Notifications\Notification;

class AiSummaryWidget extends Component
{
    public string $summary = '';

    public function summarize()
    {
        $selectedMonth = DB::table('aws_costs')
            ->selectRaw("MAX(DATE_FORMAT(UsageEndDate, '%Y-%m')) as latest_month")
            ->value('latest_month');

        $records = DB::table('aws_costs')
            ->selectRaw("LinkedAccountName, SUM(totalCost) as total_cost")
            ->whereRaw("DATE_FORMAT(UsageEndDate, '%Y-%m') = ?", [$selectedMonth])
            ->groupBy('LinkedAccountName')
            ->get();

        $text = "AWS Cost Breakdown for " . Carbon::createFromFormat('Y-m', $selectedMonth)->format('F Y') . ":\n\n";
        foreach ($records as $r) {
            $text .= "- {$r->LinkedAccountName}: \${$r->total_cost}\n";
        }

        $response = OpenAI::chat()->create([
            'model' => 'gpt-4.1-nano', // or 'gpt-4', 'gpt-3.5-turbo'
            'messages' => [
                ['role' => 'system', 'content' => 'Summarize AWS account spending in plain language.'],
                ['role' => 'user', 'content' => $text],
            ],
        ]);

        $this->summary = $response->choices[0]->message->content ?? 'No summary generated.';

        Notification::make()
            ->title('Summary Generated')
            ->body('The AI has generated your AWS cost summary.')
            ->success()
            ->duration(3000)
            ->send();
    }

    public function exportSummary()
    {
        $fileName = 'ai-summary-' . now()->format('Ymd_His') . '.txt';
        $content = $this->summary ?: 'No summary available.';

        Notification::make()
            ->title('Exported Successfully')
            ->body('AI summary exported as .txt file')
            ->success()
            ->duration(3000)
            ->send();

        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, $fileName, [
            'Content-Type' => 'text/plain',
        ]);
    }

    public function render()
    {
        return view('livewire.ai-summary-widget');
    }
}
