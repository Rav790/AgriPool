<x-app-layout>
    <x-slot name="title">{{ __('Transport Matches') }}</x-slot>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('Available Transporters for :crop', ['crop' => $request->crop_type]) }}</h1>
    </x-slot>

    <div class="max-w-5xl mx-auto">
        <!-- Request Summary -->
        <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
                <div><span class="text-green-600 font-medium">{{ __('Crop') }}:</span> <span class="text-gray-800">{{ $request->crop_type }}</span></div>
                <div><span class="text-green-600 font-medium">{{ __('Quantity') }}:</span> <span class="text-gray-800">{{ $request->quantity_tons }} {{ __('tons') }}</span></div>
                <div><span class="text-green-600 font-medium">{{ __('Date') }}:</span> <span class="text-gray-800">{{ $request->required_date->format('M d, Y') }}</span></div>
                <div><span class="text-green-600 font-medium">{{ __('To') }}:</span> <span class="text-gray-800">{{ $request->destinationMarket->name ?? '—' }}</span></div>
            </div>
        </div>

        @if($matches->count() > 0)
            <div class="space-y-4">
                @foreach($matches as $listing)
                <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <span class="text-green-700 font-bold">{{ strtoupper(substr($listing->transporter->name, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $listing->transporter->name }}</p>
                                    <div class="flex items-center text-xs text-gray-500">
                                        <x-rating-stars :rating="$listing->transporter->averageRating()" size="sm" />
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3 text-sm mt-3">
                                <div><span class="text-gray-500">{{ __('Route') }}:</span> <span class="font-medium">{{ $listing->route_from }} → {{ $listing->route_to }}</span></div>
                                <div><span class="text-gray-500">{{ __('Date') }}:</span> <span class="font-medium">{{ $listing->available_date->format('M d, Y') }}</span></div>
                                <div><span class="text-gray-500">{{ __('Price') }}:</span> <span class="font-bold text-green-700">₹{{ number_format($listing->price_per_ton, 0) }}/{{ __('ton') }}</span></div>
                                <div><span class="text-gray-500">{{ __('Available') }}:</span> <span class="font-medium">{{ $listing->remaining_capacity }}/{{ $listing->total_capacity }} {{ __('tons') }}</span></div>
                            </div>

                            <!-- Capacity Bar -->
                            <div class="mt-3">
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="h-2 rounded-full {{ $listing->capacityPercentage() > 80 ? 'bg-red-500' : ($listing->capacityPercentage() > 50 ? 'bg-amber-500' : 'bg-green-500') }}" style="width: {{ $listing->capacityPercentage() }}%"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">{{ $listing->capacityPercentage() }}% {{ __('capacity used') }}</p>
                            </div>
                        </div>

                        <!-- Match Score & Book -->
                        <div class="text-center sm:text-right sm:min-w-[140px]">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full {{ $listing->match_score >= 70 ? 'bg-green-100 text-green-700' : ($listing->match_score >= 40 ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }} mb-2">
                                <span class="text-lg font-bold">{{ round($listing->match_score) }}%</span>
                            </div>
                            <p class="text-xs text-gray-500 mb-3">{{ __('Match Score') }}</p>

                            <!-- Match Details -->
                            <div class="text-xs space-y-1 mb-3">
                                <div class="flex justify-between"><span class="text-gray-400">{{ __('Route') }}</span><span class="font-medium">{{ $listing->match_details['route_score'] }}%</span></div>
                                <div class="flex justify-between"><span class="text-gray-400">{{ __('Date') }}</span><span class="font-medium">{{ $listing->match_details['date_score'] }}%</span></div>
                                <div class="flex justify-between"><span class="text-gray-400">{{ __('Capacity') }}</span><span class="font-medium">{{ $listing->match_details['capacity_score'] }}%</span></div>
                            </div>

                            <form action="{{ route('bookings.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="request_id" value="{{ $request->id }}">
                                <input type="hidden" name="listing_id" value="{{ $listing->id }}">
                                <input type="number" name="allocated_tons" value="{{ min($request->quantity_tons, $listing->remaining_capacity) }}" step="0.1" min="0.1" max="{{ $listing->remaining_capacity }}" class="w-full rounded-lg border-gray-300 text-sm mb-2 focus:border-green-500 focus:ring-green-500">
                                <p class="text-xs text-gray-500 mb-2">{{ __('Est. Cost') }}: ₹{{ number_format(min($request->quantity_tons, $listing->remaining_capacity) * $listing->price_per_ton, 0) }}</p>
                                <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition">{{ __('Book Now') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <h3 class="text-lg font-semibold text-gray-600 mb-2">{{ __('No matching transporters found') }}</h3>
                <p class="text-gray-400">{{ __('Try adjusting your date or destination. New transporters list routes daily.') }}</p>
            </div>
        @endif
    </div>
</x-app-layout>
