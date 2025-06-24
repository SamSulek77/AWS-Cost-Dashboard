namespace App\Services;

use OpenAI;

class OpenAIService
{
    public function summarize(string $content): string
    {
        $client = OpenAI::client(env('OPENAI_API_KEY'));

        $response = $client->chat()->create([
            'model' => 'gpt-4', // or 'gpt-3.5-turbo'
            'messages' => [
                ['role' => 'system', 'content' => 'Summarize this AWS cost data report.'],
                ['role' => 'user', 'content' => $content],
            ],
        ]);

        return $response->choices[0]->message->content ?? 'No summary available.';
    }
}
