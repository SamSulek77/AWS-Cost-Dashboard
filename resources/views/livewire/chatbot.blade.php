<div class="p-4 bg-black rounded shadow w-full max-w-xl mx-auto">
    <h2 class="text-xl font-bold mb-4">💬 AWS Cost Assistant</h2>

    <div class="space-y-2 max-h-96 overflow-y-auto text-black mb-4">
        @foreach($messages as $message)
            <div class="{{ $message['role'] === 'user' ? 'text-right' : 'text-left' }}">
                <span class="px-3 py-2 rounded text-black 
                    {{ $message['role'] === 'user' ? 'bg-black-100' : 'bg-black-100' }}">
                    {{ $message['content'] }}
                </span>
            </div>
        @endforeach
    </div>

    <form wire:submit.prevent="send" class="flex gap-2">
        <input type="text-black " wire:model="input" placeholder="Ask something..."
            class="w-full border rounded px-4 py-2 text-black focus:outline-none focus:ring">
        <button class="bg-black-500 px-4 py-2 text-black rounded hover:bg-black-600">Send</button>
    </form>
</div>