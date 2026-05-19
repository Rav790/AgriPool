<x-app-layout>
    <x-slot name="title">{{ __('Messages') }}</x-slot>
    <x-slot name="header"><h1 class="text-2xl font-bold text-gray-900">{{ __('Chat — Booking') }} #{{ $booking->id }}</h1></x-slot>
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-xl border overflow-hidden">
            <!-- Messages -->
            <div class="h-96 overflow-y-auto p-4 space-y-3" id="chatMessages">
                @forelse($messages as $msg)
                <div class="flex {{ $msg->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-xs px-4 py-2 rounded-2xl {{ $msg->sender_id === auth()->id() ? 'bg-green-600 text-white rounded-br-md' : 'bg-gray-100 text-gray-800 rounded-bl-md' }}">
                        <p class="text-sm">{{ $msg->message }}</p>
                        <p class="text-xs mt-1 {{ $msg->sender_id === auth()->id() ? 'text-green-200' : 'text-gray-400' }}">
                            {{ $msg->created_at->format('H:i') }}
                            @if($msg->sender_id === auth()->id() && $msg->is_read) ✓✓ @endif
                        </p>
                    </div>
                </div>
                @empty
                <div class="text-center text-gray-400 py-12">{{ __('No messages yet. Start the conversation!') }}</div>
                @endforelse
            </div>
            <!-- Input -->
            <form action="{{ $booking->farmer_id === auth()->id() ? route('farmer.bookings.messages.store', $booking) : route('transporter.bookings.messages.store', $booking) }}" method="POST" class="border-t p-4 flex space-x-3">
                @csrf
                <input type="text" name="message" required placeholder="{{ __('Type a message...') }}" class="flex-1 rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500" autofocus>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700">{{ __('Send') }}</button>
            </form>
        </div>
    </div>
    @push('scripts')
    <script>document.getElementById('chatMessages').scrollTop = document.getElementById('chatMessages').scrollHeight;</script>
    @endpush
</x-app-layout>
