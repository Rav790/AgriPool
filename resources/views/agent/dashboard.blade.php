<x-app-layout>
    <x-slot name="title">{{ __('Agent Dashboard') }}</x-slot>
    <x-slot name="header"><h1 class="text-2xl font-bold text-gray-900">{{ __('Market Agent Dashboard') }}</h1></x-slot>
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <x-stat-card :title="__('Total Markets')" :value="$stats['total_markets']" color="green" icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>' />
            <x-stat-card :title="__('Prices Today')" :value="$stats['prices_today']" color="blue" icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' />
            <x-stat-card :title="__('In Transit')" :value="$stats['pending_deliveries']" color="amber" icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>' />
            <x-stat-card :title="__('Delivered')" :value="$stats['completed_deliveries']" color="green" icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' />
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl border overflow-hidden">
                <div class="p-5 border-b flex justify-between items-center">
                    <h2 class="text-lg font-semibold">{{ __('Recent Price Entries') }}</h2>
                    <a href="{{ route('agent.prices.create') }}" class="bg-green-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-green-700">+ {{ __('Add Price') }}</a>
                </div>
                <div class="divide-y">
                    @forelse($recentPrices as $price)
                    <div class="p-4">
                        <div class="flex justify-between">
                            <div><p class="font-medium text-sm">{{ $price->crop_type }} — {{ $price->market->name ?? '—' }}</p></div>
                            <p class="font-bold text-green-700">₹{{ number_format($price->price_per_quintal, 0) }}/q</p>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">{{ $price->recorded_date->format('M d, Y') }}</p>
                    </div>
                    @empty
                    <div class="p-8 text-center text-gray-400">{{ __('No prices recorded yet.') }}</div>
                    @endforelse
                </div>
            </div>
            <div class="bg-white rounded-xl border overflow-hidden">
                <div class="p-5 border-b flex justify-between items-center">
                    <h2 class="text-lg font-semibold">{{ __('Pending Deliveries') }}</h2>
                    <a href="{{ route('agent.deliveries') }}" class="text-sm text-green-600 hover:text-green-800">{{ __('View All') }} →</a>
                </div>
                <div class="divide-y">
                    @forelse($pendingDeliveries as $delivery)
                    <div class="p-4 flex items-center justify-between">
                        <div>
                            <p class="font-medium text-sm">{{ $delivery->farmer->name ?? '—' }} → {{ $delivery->transportRequest->destinationMarket->name ?? '—' }}</p>
                            <p class="text-xs text-gray-500">{{ $delivery->transportRequest->crop_type ?? '' }} · {{ $delivery->allocated_tons }} tons</p>
                        </div>
                        <form action="{{ route('agent.deliveries.confirm', $delivery) }}" method="POST">@csrf
                            <button type="submit" class="bg-green-600 text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-green-700">{{ __('Confirm') }}</button>
                        </form>
                    </div>
                    @empty
                    <div class="p-8 text-center text-gray-400">{{ __('No pending deliveries.') }}</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
