<x-app-layout>
    <x-slot name="title">{{ __('Load Board') }}</x-slot>

    <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">📦 {{ __('Load Board — Open Marketplace') }}</h1>
                <p class="text-gray-500 mt-1">{{ __('Browse available transport and open produce requests') }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('fare-calculator') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium hover:bg-gray-50 transition">🧮 {{ __('Fare Calculator') }}</a>
                <a href="{{ route('leaderboard') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium hover:bg-gray-50 transition">🏆 {{ __('Leaderboard') }}</a>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
            <form method="GET" class="flex flex-wrap gap-3 items-end">
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-xs font-medium text-gray-500 mb-1">{{ __('From') }}</label>
                    <input type="text" name="route_from" value="{{ request('route_from') }}" placeholder="{{ __('Origin city...') }}" class="w-full rounded-lg border-gray-300 text-sm focus:ring-green-500">
                </div>
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-xs font-medium text-gray-500 mb-1">{{ __('To') }}</label>
                    <input type="text" name="route_to" value="{{ request('route_to') }}" placeholder="{{ __('Destination...') }}" class="w-full rounded-lg border-gray-300 text-sm focus:ring-green-500">
                </div>
                <div class="flex-1 min-w-[120px]">
                    <label class="block text-xs font-medium text-gray-500 mb-1">{{ __('Crop Filter') }}</label>
                    <select name="crop" class="w-full rounded-lg border-gray-300 text-sm focus:ring-green-500">
                        <option value="">{{ __('All Crops') }}</option>
                        @foreach($crops as $c)
                            <option value="{{ $c }}" @selected(request('crop') === $c)>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition">{{ __('Search') }}</button>
            </form>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Available Transport -->
            <div>
                <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">🚛 {{ __('Available Transport') }} <span class="text-sm font-normal text-gray-400">({{ $listings->total() }})</span></h2>
                <div class="space-y-3">
                    @forelse($listings as $listing)
                        <div class="bg-white rounded-xl border border-gray-200 p-4 hover:shadow-md transition">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $listing->route_from }} → {{ $listing->route_to }}</p>
                                    <p class="text-sm text-gray-500 mt-1">📅 {{ $listing->available_date->format('M d, Y') }} · 🚛 {{ $listing->transporter->name ?? 'N/A' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-green-600">₹{{ number_format($listing->price_per_ton) }}<span class="text-xs text-gray-400">/ton</span></p>
                                    <p class="text-xs text-gray-500">{{ $listing->remaining_capacity }}/{{ $listing->total_capacity }} {{ __('tons') }}</p>
                                </div>
                            </div>
                            <div class="mt-3">
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full transition-all" style="width: {{ $listing->capacityPercentage() }}%"></div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-400">{{ __('No available transport listings.') }}</div>
                    @endforelse
                    <div class="mt-4">{{ $listings->appends(request()->query())->links() }}</div>
                </div>
            </div>

            <!-- Open Produce Requests -->
            <div>
                <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">🌾 {{ __('Open Produce Requests') }} <span class="text-sm font-normal text-gray-400">({{ $openRequests->total() }})</span></h2>
                <div class="space-y-3">
                    @forelse($openRequests as $req)
                        <div class="bg-white rounded-xl border border-gray-200 p-4 hover:shadow-md transition">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $req->crop_type }} — {{ $req->quantity_tons }} {{ __('tons') }}</p>
                                    <p class="text-sm text-gray-500 mt-1">📍 {{ $req->pickup_address }} → {{ $req->destinationMarket->name ?? '' }}</p>
                                    <p class="text-xs text-gray-400 mt-1">👤 {{ $req->farmer->name ?? '' }} · 📅 {{ $req->required_date->format('M d') }}</p>
                                </div>
                                <div class="text-right">
                                    @if($req->is_perishable)
                                        <span class="inline-block px-2 py-0.5 bg-red-100 text-red-700 text-xs rounded-full">🧊 {{ __('Perishable') }}</span>
                                    @endif
                                    <p class="text-xs text-gray-500 mt-1">{{ $req->packaging_type }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-400">{{ __('No open requests at this time.') }}</div>
                    @endforelse
                    <div class="mt-4">{{ $openRequests->appends(request()->query())->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
