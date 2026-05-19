<x-app-layout>
    <x-slot name="title">{{ __('Booking Details') }}</x-slot>
    <x-slot name="header"><h1 class="text-2xl font-bold text-gray-900">{{ __('Booking') }} #{{ $booking->id }}</h1></x-slot>
    <div class="max-w-5xl mx-auto space-y-6">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <x-status-pill :status="$booking->status" />
                    <x-status-pill :status="$booking->payment_status" />
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div><p class="text-sm text-gray-500">{{ __('Farmer') }}</p><p class="font-semibold mt-1">{{ $booking->farmer->name ?? '—' }}</p></div>
                <div><p class="text-sm text-gray-500">{{ __('Crop') }}</p><p class="font-semibold mt-1">{{ $booking->transportRequest->crop_type ?? '—' }}</p></div>
                <div><p class="text-sm text-gray-500">{{ __('Allocated') }}</p><p class="font-semibold mt-1">{{ $booking->allocated_tons }} {{ __('tons') }}</p></div>
                <div><p class="text-sm text-gray-500">{{ __('Destination') }}</p><p class="font-semibold mt-1">{{ $booking->transportRequest->destinationMarket->name ?? '—' }}</p></div>
                <div><p class="text-sm text-gray-500">{{ __('Total Price') }}</p><p class="font-bold text-green-700 text-xl mt-1">₹{{ number_format($booking->total_price, 0) }}</p></div>
                <div><p class="text-sm text-gray-500">{{ __('Pickup Address') }}</p><p class="font-semibold mt-1">{{ $booking->transportRequest->pickup_address ?? '—' }}</p></div>
            </div>
        </div>

        <!-- Status Actions -->
        <div class="flex flex-wrap gap-3">
            @if($booking->status === 'pending')
                <form action="{{ route('transporter.bookings.accept', $booking) }}" method="POST">@csrf
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700">✅ {{ __('Accept Booking') }}</button>
                </form>
            @endif
            @if($booking->status === 'confirmed')
                <form action="{{ route('transporter.bookings.pickup', $booking) }}" method="POST">@csrf
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700">📦 {{ __('Confirm Pickup') }}</button>
                </form>
            @endif
            @if($booking->status === 'picked_up')
                <form action="{{ route('transporter.bookings.deliver', $booking) }}" method="POST">@csrf
                    <button type="submit" class="bg-amber-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-amber-600">🚛 {{ __('Mark In Transit') }}</button>
                </form>
            @endif

            <!-- Update Tracking -->
            @if(in_array($booking->status, ['picked_up', 'in_transit', 'confirmed']))
            <form action="{{ route('transporter.bookings.tracking.update', $booking) }}" method="POST" class="flex items-center space-x-2">
                @csrf
                <select name="status" required class="rounded-lg border-gray-300 text-sm">
                    <option value="picked_up">{{ __('Picked Up') }}</option>
                    <option value="in_transit">{{ __('In Transit') }}</option>
                    <option value="checkpoint">{{ __('Checkpoint') }}</option>
                    <option value="delivered">{{ __('Delivered') }}</option>
                </select>
                <input type="text" name="status_note" placeholder="{{ __('Note (optional)') }}" class="rounded-lg border-gray-300 text-sm">
                <button type="submit" class="bg-purple-600 text-white px-3 py-2 rounded-lg text-sm hover:bg-purple-700">📍 {{ __('Update') }}</button>
            </form>
            @endif

            <a href="{{ route('transporter.bookings.messages', $booking) }}" class="bg-blue-50 text-blue-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-100">💬 {{ __('Chat') }}</a>
        </div>

        <!-- Timeline -->
        @if($booking->trackingUpdates->count() > 0)
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold mb-4">{{ __('Tracking Timeline') }}</h2>
            <div class="space-y-4">
                @foreach($booking->trackingUpdates->sortByDesc('created_at') as $update)
                <div class="flex items-start space-x-3">
                    <div class="w-3 h-3 mt-1 rounded-full {{ $update->status === 'delivered' ? 'bg-green-500' : 'bg-amber-500' }}"></div>
                    <div>
                        <p class="font-medium text-sm">{{ __(ucwords(str_replace('_', ' ', $update->status))) }}</p>
                        @if($update->status_note)<p class="text-sm text-gray-500">{{ $update->status_note }}</p>@endif
                        <p class="text-xs text-gray-400 mt-1">{{ $update->created_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Review -->
        @if($canReview)
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold mb-4">{{ __('Rate the Farmer') }}</h2>
            <form action="{{ route('transporter.bookings.review', $booking) }}" method="POST" class="space-y-4">
                @csrf
                <div x-data="{ rating: 5 }">
                    <div class="flex space-x-1">
                        @for($i = 1; $i <= 5; $i++)
                        <button type="button" @click="rating = {{ $i }}" :class="rating >= {{ $i }} ? 'text-amber-400' : 'text-gray-300'">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        </button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" :value="rating">
                </div>
                <textarea name="comment" rows="3" class="w-full rounded-lg border-gray-300" placeholder="{{ __('Comment...') }}"></textarea>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700">{{ __('Submit Review') }}</button>
            </form>
        </div>
        @endif
    </div>
</x-app-layout>
