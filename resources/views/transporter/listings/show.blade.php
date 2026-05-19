<x-app-layout>
    <x-slot name="title">{{ __('Listing Details') }}</x-slot>
    <x-slot name="header"><h1 class="text-2xl font-bold text-gray-900">{{ __('Listing') }} #{{ $listing->id }}</h1></x-slot>
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <x-status-pill :status="$listing->status" />
                <a href="{{ route('transporter.listings.requests', $listing) }}" class="text-sm text-amber-600 hover:text-amber-800 font-medium">{{ __('Find Nearby Loads') }} →</a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div><p class="text-sm text-gray-500">{{ __('Route') }}</p><p class="font-semibold mt-1">{{ $listing->route_from }} → {{ $listing->route_to }}</p></div>
                <div><p class="text-sm text-gray-500">{{ __('Date') }}</p><p class="font-semibold mt-1">{{ $listing->available_date->format('M d, Y') }}</p></div>
                <div><p class="text-sm text-gray-500">{{ __('Price') }}</p><p class="font-bold text-green-700 text-xl mt-1">₹{{ number_format($listing->price_per_ton, 0) }}/{{ __('ton') }}</p></div>
            </div>
            <div class="mt-4">
                <p class="text-sm text-gray-500 mb-2">{{ __('Capacity') }}: {{ $listing->remaining_capacity }}/{{ $listing->total_capacity }} {{ __('tons remaining') }}</p>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="h-3 rounded-full {{ $listing->capacityPercentage() > 80 ? 'bg-red-500' : 'bg-green-500' }}" style="width: {{ $listing->capacityPercentage() }}%"></div>
                </div>
            </div>
        </div>
        @if($listing->bookings->count() > 0)
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="p-5 border-b"><h2 class="text-lg font-semibold">{{ __('Bookings on this listing') }}</h2></div>
            <div class="divide-y">
                @foreach($listing->bookings as $booking)
                <a href="{{ route('transporter.bookings.show', $booking) }}" class="block p-4 hover:bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium">{{ $booking->farmer->name ?? '—' }}</p>
                            <p class="text-sm text-gray-500">{{ $booking->allocated_tons }} {{ __('tons') }} · ₹{{ number_format($booking->total_price, 0) }}</p>
                        </div>
                        <x-status-pill :status="$booking->status" />
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</x-app-layout>
