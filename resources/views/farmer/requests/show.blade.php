<x-app-layout>
    <x-slot name="title">{{ __('Request Details') }}</x-slot>
    <x-slot name="header"><h1 class="text-2xl font-bold text-gray-900">{{ __('Transport Request') }} #{{ $request->id }}</h1></x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <x-status-pill :status="$request->status" />
                @if($request->is_perishable)
                    <span class="text-xs bg-red-50 text-red-600 px-2 py-1 rounded-lg font-medium">🍅 {{ __('Perishable') }}</span>
                @endif
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div><p class="text-sm text-gray-500">{{ __('Crop Type') }}</p><p class="font-semibold text-gray-900 mt-1">{{ $request->crop_type }}</p></div>
                <div><p class="text-sm text-gray-500">{{ __('Quantity') }}</p><p class="font-semibold text-gray-900 mt-1">{{ $request->quantity_tons }} {{ __('tons') }}</p></div>
                <div><p class="text-sm text-gray-500">{{ __('Packaging') }}</p><p class="font-semibold text-gray-900 mt-1 capitalize">{{ __($request->packaging_type) }}</p></div>
                <div><p class="text-sm text-gray-500">{{ __('Required Date') }}</p><p class="font-semibold text-gray-900 mt-1">{{ $request->required_date->format('M d, Y') }}</p></div>
                <div><p class="text-sm text-gray-500">{{ __('Pickup Address') }}</p><p class="font-semibold text-gray-900 mt-1">{{ $request->pickup_address }}</p></div>
                <div><p class="text-sm text-gray-500">{{ __('Destination Market') }}</p><p class="font-semibold text-gray-900 mt-1">{{ $request->destinationMarket->name ?? '—' }} ({{ $request->destinationMarket->city ?? '' }})</p></div>
            </div>

            @if($request->special_instructions)
                <div class="mt-6 p-4 bg-amber-50 border border-amber-100 rounded-lg">
                    <p class="text-sm font-medium text-amber-800">{{ __('Special Instructions') }}</p>
                    <p class="text-sm text-amber-700 mt-1">{{ $request->special_instructions }}</p>
                </div>
            @endif

            @if($request->hasSpoilageRisk())
                <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg flex items-center">
                    <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    <p class="text-sm text-red-700 font-medium">{{ __('⚠ Spoilage Risk! This perishable produce needs pickup within 24 hours.') }}</p>
                </div>
            @endif

            <div class="mt-6 flex flex-wrap gap-3">
                @if($request->status === 'pending')
                    <a href="{{ route('farmer.requests.matches', $request) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition">{{ __('Find Transport') }}</a>
                    <a href="{{ route('farmer.requests.edit', $request) }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition">{{ __('Edit') }}</a>
                    <form method="POST" action="{{ route('farmer.requests.destroy', $request) }}" onsubmit="return confirm('{{ __('Delete this request?') }}')">
                        @csrf @method('DELETE')
                        <button type="submit" class="bg-red-50 text-red-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-100 transition">{{ __('Delete') }}</button>
                    </form>
                @endif
            </div>
        </div>

        @if($request->bookings->count() > 0)
        <div class="mt-6 bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="p-5 border-b"><h2 class="text-lg font-semibold text-gray-900">{{ __('Bookings') }}</h2></div>
            <div class="divide-y">
                @foreach($request->bookings as $booking)
                <a href="{{ route('farmer.bookings.show', $booking) }}" class="block p-4 hover:bg-gray-50 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium">🚛 {{ $booking->transporter->name ?? '—' }}</p>
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
