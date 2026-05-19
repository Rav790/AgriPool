<x-app-layout>
    <x-slot name="title">{{ __('Booking Details') }}</x-slot>
    <x-slot name="header"><h1 class="text-2xl font-bold text-gray-900">{{ __('Booking') }} #{{ $booking->id }}</h1></x-slot>

    <div class="max-w-5xl mx-auto space-y-6">
        <!-- Booking Summary -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <x-status-pill :status="$booking->status" />
                    <x-status-pill :status="$booking->payment_status" />
                </div>
                @if($booking->status === 'delivered')
                    <a href="{{ route('farmer.bookings.invoice', $booking) }}" class="text-sm text-green-600 hover:text-green-800 font-medium flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        {{ __('Download Invoice') }}
                    </a>
                @endif
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div><p class="text-sm text-gray-500">{{ __('Crop') }}</p><p class="font-semibold mt-1">{{ $booking->transportRequest->crop_type ?? '—' }}</p></div>
                <div><p class="text-sm text-gray-500">{{ __('Allocated Tons') }}</p><p class="font-semibold mt-1">{{ $booking->allocated_tons }} {{ __('tons') }}</p></div>
                <div><p class="text-sm text-gray-500">{{ __('Total Price') }}</p><p class="font-bold text-green-700 text-xl mt-1">₹{{ number_format($booking->total_price, 0) }}</p></div>
                <div><p class="text-sm text-gray-500">{{ __('Transporter') }}</p><p class="font-semibold mt-1">{{ $booking->transporter->name ?? '—' }}</p></div>
                <div><p class="text-sm text-gray-500">{{ __('Route') }}</p><p class="font-semibold mt-1">{{ $booking->transportListing->route_from ?? '—' }} → {{ $booking->transportListing->route_to ?? '—' }}</p></div>
                <div><p class="text-sm text-gray-500">{{ __('Destination') }}</p><p class="font-semibold mt-1">{{ $booking->transportRequest->destinationMarket->name ?? '—' }}</p></div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-wrap gap-3">
            @if($booking->payment_status === 'unpaid')
                <form action="{{ route('farmer.bookings.pay', $booking) }}" method="POST" class="flex items-center space-x-2">
                    @csrf
                    <select name="payment_mode" required class="rounded-lg border-gray-300 text-sm focus:border-green-500 focus:ring-green-500">
                        <option value="wallet">{{ __('Wallet') }}</option>
                        <option value="upi">{{ __('UPI') }}</option>
                        <option value="cash">{{ __('Cash') }}</option>
                    </select>
                    <button type="submit" class="bg-amber-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-amber-600 transition">{{ __('Pay ₹:amount', ['amount' => number_format($booking->total_price, 0)]) }}</button>
                </form>
            @endif

            @if(in_array($booking->status, ['in_transit', 'picked_up']))
                <form action="{{ route('farmer.bookings.confirm-delivery', $booking) }}" method="POST" onsubmit="return confirm('{{ __('Confirm delivery? Payment will be released.') }}')">
                    @csrf
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition">✅ {{ __('Confirm Delivery') }}</button>
                </form>
            @endif

            <a href="{{ route('farmer.bookings.messages', $booking) }}" class="bg-blue-50 text-blue-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-100 transition">💬 {{ __('Chat with Transporter') }}</a>

            <a href="{{ route('farmer.bookings.tracking', $booking) }}" class="bg-purple-50 text-purple-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-purple-100 transition">📍 {{ __('Track Shipment') }}</a>
        </div>

        <!-- Tracking Timeline -->
        @if($booking->trackingUpdates->count() > 0)
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Shipment Timeline') }}</h2>
            <div class="space-y-4">
                @foreach($booking->trackingUpdates->sortByDesc('created_at') as $update)
                <div class="flex items-start space-x-3">
                    <div class="w-3 h-3 mt-1 rounded-full {{ $update->status === 'delivered' ? 'bg-green-500' : 'bg-amber-500' }}"></div>
                    <div>
                        <p class="font-medium text-gray-900 text-sm">{{ __(ucwords(str_replace('_', ' ', $update->status))) }}</p>
                        @if($update->status_note)<p class="text-sm text-gray-500">{{ $update->status_note }}</p>@endif
                        <p class="text-xs text-gray-400 mt-1">{{ $update->created_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Review Section -->
        @if($canReview)
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Rate this Transport') }}</h2>
            <form action="{{ route('farmer.bookings.review', $booking) }}" method="POST" class="space-y-4">
                @csrf
                <div x-data="{ rating: 5 }">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Rating') }}</label>
                    <div class="flex space-x-1">
                        @for($i = 1; $i <= 5; $i++)
                        <button type="button" @click="rating = {{ $i }}" :class="rating >= {{ $i }} ? 'text-amber-400' : 'text-gray-300'" class="focus:outline-none">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" :value="rating">
                </div>
                <div>
                    <label for="comment" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Comment') }}</label>
                    <textarea name="comment" id="comment" rows="3" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500" placeholder="{{ __('Share your experience...') }}"></textarea>
                </div>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700">{{ __('Submit Review') }}</button>
            </form>
        </div>
        @endif

        <!-- Existing Reviews -->
        @if($booking->reviews->count() > 0)
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Reviews') }}</h2>
            @foreach($booking->reviews as $review)
            <div class="border-b last:border-0 py-3">
                <div class="flex items-center justify-between">
                    <p class="font-medium text-sm">{{ $review->reviewer->name }}</p>
                    <x-rating-stars :rating="$review->rating" size="sm" />
                </div>
                @if($review->comment)<p class="text-sm text-gray-600 mt-1">{{ $review->comment }}</p>@endif
                <p class="text-xs text-gray-400 mt-1">{{ $review->created_at->diffForHumans() }}</p>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</x-app-layout>
