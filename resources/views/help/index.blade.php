<x-app-layout>
    <x-slot name="title">{{ __('Help Center') }}</x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">🎧 {{ __('Help Center') }}</h1>
                <p class="text-gray-500 mt-1">{{ __('Get support from our team') }}</p>
            </div>
            <a href="{{ route('help.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                {{ __('New Ticket') }}
            </a>
        </div>

        <!-- FAQ Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6" x-data="{ open: null }">
            <h2 class="font-semibold text-gray-800 mb-4">❓ {{ __('Frequently Asked Questions') }}</h2>
            <div class="space-y-2">
                @foreach([
                    ['q' => __('How do I create a transport request?'), 'a' => __('Go to your Dashboard → Click "New Transport Request" → Fill in crop, quantity, destination market, and required date → Submit. Our matching engine will find the best transporters for you.')],
                    ['q' => __('How does the escrow payment work?'), 'a' => __('When you pay for a booking, the amount is held in escrow (secure hold). It is only released to the transporter after you or the market agent confirms delivery. This protects both parties.')],
                    ['q' => __('How do I track my shipment?'), 'a' => __('Go to My Bookings → Click on your booking → Click "Track Shipment". You\'ll see a step-by-step timeline with real-time status updates from the transporter.')],
                    ['q' => __('What if my goods are damaged during transport?'), 'a' => __('You can raise a dispute from the booking detail page. Our team will investigate within 24 hours. If the claim is valid, the escrowed payment will be refunded.')],
                    ['q' => __('How do cooperative groups work?'), 'a' => __('Farmers can create or join cooperatives to pool their produce together. This helps fill trucks faster, reducing per-ton costs for everyone in the group.')],
                ] as $i => $faq)
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <button @click="open = open === {{ $i }} ? null : {{ $i }}" class="w-full flex justify-between items-center px-4 py-3 text-left hover:bg-gray-50 transition">
                            <span class="font-medium text-gray-800">{{ $faq['q'] }}</span>
                            <svg class="w-5 h-5 text-gray-400 transition-transform" :class="open === {{ $i }} && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open === {{ $i }}" x-collapse class="px-4 pb-3 text-sm text-gray-600">{{ $faq['a'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Tickets List -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="font-semibold text-gray-800">{{ __('Your Support Tickets') }}</h2>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($tickets as $ticket)
                    <a href="{{ route('help.show', $ticket) }}" class="flex items-center px-6 py-4 hover:bg-gray-50 transition">
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">{{ $ticket->subject }}</p>
                            <p class="text-sm text-gray-500 mt-0.5">{{ ucfirst($ticket->category) }} · {{ $ticket->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $ticket->status === 'open' ? 'bg-yellow-100 text-yellow-800' : ($ticket->status === 'resolved' ? 'bg-green-100 text-green-800' : ($ticket->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')) }}">
                            {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                        </span>
                    </a>
                @empty
                    <div class="text-center py-12 text-gray-400">
                        <div class="text-4xl mb-2">🎫</div>
                        <p>{{ __('No tickets yet. We\'re here to help!') }}</p>
                    </div>
                @endforelse
            </div>
            @if($tickets->hasPages())
                <div class="px-6 py-3 border-t border-gray-200">{{ $tickets->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
