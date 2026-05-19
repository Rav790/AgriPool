<x-app-layout>
    <x-slot name="title">{{ __('Wallet') }}</x-slot>
    <x-slot name="header"><h1 class="text-2xl font-bold text-gray-900">{{ __('My Wallet') }}</h1></x-slot>
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Balance Card -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-xl p-6 text-white">
            <p class="text-sm opacity-80">{{ __('Available Balance') }}</p>
            <p class="text-4xl font-bold mt-2">₹{{ number_format($wallet->balance, 2) }}</p>
            <form action="{{ auth()->user()->role === 'farmer' ? route('farmer.wallet.topup') : route('transporter.wallet.topup') }}" method="POST" class="mt-4 flex items-center space-x-3">
                @csrf
                <input type="number" name="amount" min="1" max="100000" step="1" placeholder="{{ __('Amount') }}" required class="rounded-lg border-0 bg-white/20 text-white placeholder-white/60 focus:ring-2 focus:ring-white text-sm px-4 py-2 w-40">
                <button type="submit" class="bg-white text-green-700 px-4 py-2 rounded-lg text-sm font-bold hover:bg-green-50 transition">{{ __('Top Up') }}</button>
            </form>
        </div>

        <!-- Transactions -->
        <div class="bg-white rounded-xl border overflow-hidden">
            <div class="p-5 border-b"><h2 class="text-lg font-semibold">{{ __('Transaction History') }}</h2></div>
            <div class="divide-y">
                @forelse($transactions as $txn)
                <div class="p-4 flex items-center justify-between">
                    <div>
                        <p class="font-medium text-sm capitalize">{{ $txn->type }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $txn->description ?? '—' }}</p>
                        <p class="text-xs text-gray-400">{{ $txn->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    <p class="font-bold text-lg {{ in_array($txn->type, ['credit', 'release', 'refund']) ? 'text-green-600' : 'text-red-600' }}">
                        {{ in_array($txn->type, ['credit', 'release', 'refund']) ? '+' : '-' }}₹{{ number_format($txn->amount, 0) }}
                    </p>
                </div>
                @empty
                <div class="p-8 text-center text-gray-400">{{ __('No transactions yet.') }}</div>
                @endforelse
            </div>
            <div class="p-4 border-t">{{ $transactions->links() }}</div>
        </div>
    </div>
</x-app-layout>
