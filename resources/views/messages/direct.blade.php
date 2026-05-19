<x-app-layout>
    <x-slot name="title">{{ __('Chat with') }} {{ $recipient->name }}</x-slot>

    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('messages.hub') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div class="w-10 h-10 bg-{{ $recipient->role === 'farmer' ? 'amber' : 'blue' }}-100 text-{{ $recipient->role === 'farmer' ? 'amber' : 'blue' }}-700 rounded-full flex items-center justify-center font-bold">
                {{ strtoupper(substr($recipient->name, 0, 1)) }}
            </div>
            <div>
                <h1 class="font-bold text-gray-900">{{ $recipient->name }}</h1>
                <p class="text-xs text-gray-400 capitalize">{{ $recipient->role }} · {{ $recipient->city ?? '' }}</p>
            </div>
        </div>

        <!-- Chat Window -->
        <div class="bg-white rounded-xl border overflow-hidden shadow-sm">
            <!-- Messages -->
            <div class="h-[450px] overflow-y-auto p-4 space-y-3 bg-gray-50" id="chatMessages">
                @forelse($messages as $msg)
                <div class="flex {{ $msg->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[75%] px-4 py-2.5 rounded-2xl {{ $msg->sender_id === auth()->id() ? 'bg-green-600 text-white rounded-br-md' : 'bg-white text-gray-800 border border-gray-200 rounded-bl-md' }} shadow-sm">
                        <p class="text-sm">{{ $msg->message }}</p>
                        <p class="text-xs mt-1 {{ $msg->sender_id === auth()->id() ? 'text-green-200' : 'text-gray-400' }}">
                            {{ $msg->created_at->format('h:i A') }}
                            @if($msg->sender_id === auth()->id() && $msg->is_read)
                                <span class="ml-1">✓✓</span>
                            @endif
                        </p>
                    </div>
                </div>
                @empty
                <div class="text-center text-gray-400 py-16">
                    <p class="text-4xl mb-3">👋</p>
                    <p class="font-medium">{{ __('Start the conversation!') }}</p>
                    <p class="text-sm mt-1">{{ __('Say hello to :name', ['name' => $recipient->name]) }}</p>
                </div>
                @endforelse
            </div>

            <!-- Input -->
            <form action="{{ route('messages.direct.send', $recipient) }}" method="POST" class="border-t bg-white p-4 flex items-center gap-3">
                @csrf
                <input type="text" name="message" required placeholder="{{ __('Type a message...') }}"
                    class="flex-1 rounded-xl border-gray-300 focus:border-green-500 focus:ring-green-500 bg-gray-50 py-3" autofocus>
                <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded-xl text-sm font-medium hover:bg-green-700 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    {{ __('Send') }}
                </button>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>document.getElementById('chatMessages').scrollTop = document.getElementById('chatMessages').scrollHeight;</script>
    @endpush
</x-app-layout>
