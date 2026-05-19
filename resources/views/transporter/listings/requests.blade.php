<x-app-layout>
    <x-slot name="title">{{ __('Open Produce Requests') }}</x-slot>
    <x-slot name="header"><h1 class="text-2xl font-bold text-gray-900">{{ __('Open Produce Requests for') }} {{ $listing->route_from }} → {{ $listing->route_to }}</h1></x-slot>
    <div class="max-w-5xl mx-auto">
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6 text-sm flex items-center justify-between">
            <div>
                <span class="font-medium text-amber-700">{{ __('Your Listing') }}:</span>
                {{ $listing->available_date->format('M d, Y') }} · {{ $listing->remaining_capacity }}/{{ $listing->total_capacity }} {{ __('tons available') }} · ₹{{ number_format($listing->price_per_ton, 0) }}/{{ __('ton') }}
            </div>
            <a href="{{ route('transporter.listings.show', $listing) }}" class="text-amber-700 hover:underline text-sm">← {{ __('Back to Listing') }}</a>
        </div>

        @if($requests->count() > 0)
        <div class="space-y-4">
            @foreach($requests as $request)
            <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
                <div class="flex flex-col lg:flex-row lg:items-start gap-4">
                    <!-- Request Info -->
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center">
                                <span class="text-amber-700 font-bold">{{ strtoupper(substr($request->farmer->name, 0, 1)) }}</span>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $request->farmer->name }}</p>
                                <p class="text-sm text-gray-500">{{ $request->crop_type }} · {{ $request->quantity_tons }} {{ __('tons') }}
                                    @if($request->is_perishable)
                                        <span class="ml-1 text-xs bg-red-50 text-red-600 px-2 py-0.5 rounded">🍅 {{ __('Perishable') }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div><span class="text-gray-400">{{ __('Pickup') }}:</span> <span class="text-gray-700">{{ $request->pickup_address }}</span></div>
                            <div><span class="text-gray-400">{{ __('Market') }}:</span> <span class="text-gray-700">{{ $request->destinationMarket->name ?? '—' }}</span></div>
                            <div><span class="text-gray-400">{{ __('Date Needed') }}:</span> <span class="text-gray-700">{{ $request->required_date->format('M d, Y') }}</span></div>
                            <div><span class="text-gray-400">{{ __('Packaging') }}:</span> <span class="text-gray-700">{{ __(ucfirst($request->packaging_type)) }}</span></div>
                        </div>
                        @if($request->special_instructions)
                        <p class="text-sm text-gray-500 mt-2 italic">📝 {{ $request->special_instructions }}</p>
                        @endif
                    </div>

                    <!-- Match Score + Accept Action -->
                    <div class="flex flex-col items-center gap-3 min-w-[180px]">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full {{ $request->match_score >= 70 ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                            <span class="text-xl font-bold">{{ round($request->match_score) }}%</span>
                        </div>
                        <p class="text-xs text-gray-400">{{ __('Match Score') }}</p>

                        @if($request->status === 'pending' && $listing->remaining_capacity >= $request->quantity_tons)
                        <form method="POST" action="{{ route('bookings.store') }}" class="w-full" x-data="{ tons: {{ min($request->quantity_tons, $listing->remaining_capacity) }} }">
                            @csrf
                            <input type="hidden" name="request_id" value="{{ $request->id }}">
                            <input type="hidden" name="listing_id" value="{{ $listing->id }}">
                            <div class="mb-2">
                                <label class="text-xs text-gray-500 block mb-1">{{ __('Tons to carry') }}</label>
                                <input type="number" name="allocated_tons" x-model="tons" min="0.1" max="{{ min($request->quantity_tons, $listing->remaining_capacity) }}" step="0.1" class="w-full text-sm rounded-lg border-gray-300 focus:ring-green-500">
                            </div>
                            <p class="text-xs text-gray-500 mb-2">{{ __('Est. fare') }}: <span class="font-bold text-green-600" x-text="'₹' + (tons * {{ $listing->price_per_ton }}).toLocaleString('en-IN')"></span></p>
                            <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition">
                                ✅ {{ __('Accept & Book') }}
                            </button>
                        </form>
                        @elseif($request->status !== 'pending')
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">{{ ucfirst($request->status) }}</span>
                        @else
                        <p class="text-xs text-red-500 text-center">{{ __('Not enough capacity') }}</p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="bg-white rounded-xl border p-12 text-center text-gray-400">
            <p class="text-4xl mb-3">📦</p>
            <p class="font-medium">{{ __('No matching requests found for this route.') }}</p>
            <p class="text-sm mt-1">{{ __('Check back later or try adjusting your listing route.') }}</p>
        </div>
        @endif
    </div>
</x-app-layout>
