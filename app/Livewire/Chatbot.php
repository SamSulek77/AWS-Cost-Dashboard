<?php

namespace App\Livewire;

use Livewire\Component;
use OpenAI\Laravel\Facades\OpenAI;

class Chatbot extends Component
{
    public $messages = [];
    public $input = '';

    public function send()
    {
        $this->messages[] = ['role' => 'user', 'content' => $this->input];

        $response = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => $this->messages,
        ]);

        $aiReply = $response->choices[0]->message->content;

        $this->messages[] = ['role' => 'assistant', 'content' => $aiReply];
        $this->input = '';
    }

    public function render()
    {
        return view('livewire.chatbot');
    }
}

