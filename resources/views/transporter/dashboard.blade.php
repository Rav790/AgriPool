<x-app-layout>
    <x-slot name="title">{{ __('Transporter Dashboard') }}</x-slot>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ __('Welcome back, :name!', ['name' => auth()->user()->name]) }}</h1>
                <p class="text-sm text-gray-500 mt-1">{{ __('Your fleet at a glance') }}</p>
            </div>
            <a href="{{ route('transporter.listings.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                {{ __('Add Listing') }}
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto">

        <!-- Stats Row -->
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-3 mb-6">
            <div class="bg-white rounded-xl border border-gray-200 p-4 hover-lift">
                <p class="text-xs text-gray-500 font-medium">{{ __('Listings') }}</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['active_listings'] }}</p>
                <p class="text-xs text-gray-400">{{ __('of') }} {{ $stats['total_listings'] }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4 hover-lift">
                <p class="text-xs text-gray-500 font-medium">{{ __('Active') }}</p>
                <p class="text-2xl font-bold text-amber-600 mt-1">{{ $stats['active_bookings'] }}</p>
                <p class="text-xs text-gray-400">{{ __('bookings') }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4 hover-lift">
                <p class="text-xs text-gray-500 font-medium">{{ __('Completed') }}</p>
                <p class="text-2xl font-bold text-green-600 mt-1">{{ $stats['completed_trips'] }}</p>
                <p class="text-xs text-green-500">{{ __('trips') }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4 hover-lift">
                <p class="text-xs text-gray-500 font-medium">{{ __('Revenue') }}</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">₹{{ number_format($stats['total_revenue']) }}</p>
                <p class="text-xs text-gray-400">{{ __('total') }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4 hover-lift">
                <p class="text-xs text-gray-500 font-medium">{{ __('Wallet') }}</p>
                <p class="text-2xl font-bold text-indigo-600 mt-1">₹{{ number_format($stats['wallet_balance']) }}</p>
                <p class="text-xs text-gray-400">{{ __('balance') }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4 hover-lift">
                <p class="text-xs text-gray-500 font-medium">{{ __('Rating') }}</p>
                <p class="text-2xl font-bold text-yellow-500 mt-1">{{ $stats['avg_rating'] ?: '—' }} ★</p>
                <p class="text-xs text-gray-400">{{ __('average') }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4 hover-lift col-span-2">
                <p class="text-xs text-gray-500 font-medium">{{ __('Trust Score') }}</p>
                <div class="flex items-center gap-3 mt-1">
                    <p class="text-2xl font-bold {{ $stats['trust_score'] >= 60 ? 'text-green-600' : ($stats['trust_score'] >= 30 ? 'text-amber-600' : 'text-red-500') }}">{{ $stats['trust_score'] }}<span class="text-xs text-gray-400 font-normal">/100</span></p>
                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                        <div class="h-2 rounded-full {{ $stats['trust_score'] >= 60 ? 'bg-green-500' : ($stats['trust_score'] >= 30 ? 'bg-amber-500' : 'bg-red-500') }}" style="width: {{ $stats['trust_score'] }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings Chart + Quick Actions -->
        <div class="grid lg:grid-cols-3 gap-6 mb-6">
            <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">💰 {{ __('Monthly Earnings') }}</h2>
                <canvas id="earningsChart" height="120"></canvas>
            </div>
            <div class="space-y-3">
                <a href="{{ route('transporter.listings.create') }}" class="block bg-white rounded-xl border border-gray-200 p-4 hover:border-green-300 hover:bg-green-50 transition group">
                    <p class="text-2xl mb-1">🚛</p>
                    <p class="font-semibold text-gray-700 group-hover:text-green-700">{{ __('Add New Route') }}</p>
                    <p class="text-xs text-gray-500">{{ __('List a vehicle for a route') }}</p>
                </a>
                <a href="{{ route('load-board') }}" class="block bg-white rounded-xl border border-gray-200 p-4 hover:border-green-300 hover:bg-green-50 transition group">
                    <p class="text-2xl mb-1">📦</p>
                    <p class="font-semibold text-gray-700 group-hover:text-green-700">{{ __('Find Loads') }}</p>
                    <p class="text-xs text-gray-500">{{ __('Browse available loads') }}</p>
                </a>
                <a href="{{ route('kyc.index') }}" class="block bg-white rounded-xl border border-gray-200 p-4 hover:border-green-300 hover:bg-green-50 transition group">
                    <p class="text-2xl mb-1">🪪</p>
                    <p class="font-semibold text-gray-700 group-hover:text-green-700">{{ __('KYC Verification') }}</p>
                    <p class="text-xs text-gray-500">{{ __('Boost your trust score') }}</p>
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <!-- Open Farmer Requests (Packages to Accept) -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gradient-to-r from-amber-50 to-white">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">🌾 {{ __('Open Produce Requests / Packages') }}</h2>
                            <p class="text-xs text-gray-500 mt-0.5">{{ __('Accept packages listed by farmers to fill up your active routes') }}</p>
                        </div>
                        <span class="text-xs bg-amber-100 text-amber-800 font-bold px-2.5 py-1 rounded-full">{{ $availableRequests->count() }} {{ __('Available') }}</span>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse($availableRequests as $request)
                            <div class="p-5 hover:bg-gray-50 transition block">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <p class="font-bold text-gray-900 text-base">{{ $request->crop_type }}</p>
                                            <span class="text-xs font-semibold text-amber-700 bg-amber-50 px-2 py-0.5 rounded">{{ $request->quantity_tons }} {{ __('tons') }}</span>
                                        </div>
                                        <p class="text-sm text-gray-600 mt-1">👤 {{ $request->farmer->name ?? __('Farmer') }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">🏁 {{ __('Destination') }}: <strong class="text-gray-700">{{ $request->destinationMarket->name ?? __('Market') }}</strong></p>
                                        <div class="flex items-center gap-4 mt-2 text-xs text-gray-400">
                                            <span>📅 {{ __('Required by') }}: <strong class="text-gray-600">{{ $request->required_date->format('M d, Y') }}</strong></span>
                                            @if($request->hasSpoilageRisk())
                                                <span class="text-red-600 font-bold bg-red-50 px-1.5 py-0.5 rounded">⚠️ {{ __('High Risk') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="sm:max-w-xs w-full bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                                        @if($activeListingsForSelect->count() > 0)
                                            <form action="{{ route('bookings.store') }}" method="POST" class="space-y-2">
                                                @csrf
                                                <input type="hidden" name="request_id" value="{{ $request->id }}">
                                                
                                                <div>
                                                    <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">{{ __('Select Your Route Vehicle') }}</label>
                                                    <select name="listing_id" class="w-full text-xs rounded border-gray-300 py-1 px-2 focus:ring-green-500" required>
                                                        @foreach($activeListingsForSelect as $listingOption)
                                                            <option value="{{ $listingOption->id }}">
                                                                {{ $listingOption->route_from }} → {{ $listingOption->route_to }} (₹{{ $listingOption->price_per_ton }}/T)
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                
                                                <div class="flex items-center gap-2">
                                                    <div class="flex-1">
                                                        <label class="block text-[10px] font-bold text-gray-500 uppercase">{{ __('Tons') }}</label>
                                                        <input type="number" name="allocated_tons" min="0.1" max="{{ min($request->quantity_tons, $activeListingsForSelect->first()->remaining_capacity ?? 999) }}" step="0.1" value="{{ min($request->quantity_tons, $activeListingsForSelect->first()->remaining_capacity ?? 1) }}" class="w-full text-xs rounded border-gray-300 py-1 px-2 focus:ring-green-500" required>
                                                    </div>
                                                    <button type="submit" class="mt-4 bg-amber-600 text-white font-bold text-xs px-3 py-1.5 rounded hover:bg-amber-700 transition self-end whitespace-nowrap">
                                                        {{ __('Accept Package') }}
                                                    </button>
                                                </div>
                                            </form>
                                        @else
                                            <div class="text-center py-1">
                                                <p class="text-xs text-gray-500 mb-2">{{ __('No active vehicle routes.') }}</p>
                                                <a href="{{ route('transporter.listings.create') }}" class="block text-center bg-gray-900 text-white font-bold text-xs py-1.5 rounded hover:bg-gray-800 transition">
                                                    {{ __('Add Vehicle Listing') }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-gray-400">
                                <p class="text-3xl mb-2">📦</p>
                                <p class="text-sm">{{ __('No farmer packages available right now.') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Active Listings -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="p-5 border-b flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-900">🚛 {{ __('Active Listings') }}</h2>
                        <a href="{{ route('transporter.listings.index') }}" class="text-sm text-green-600 hover:text-green-800">{{ __('View All') }} →</a>
                    </div>
                    <div class="divide-y">
                        @forelse($activeListings as $listing)
                        <a href="{{ route('transporter.listings.show', $listing) }}" class="block p-4 hover:bg-gray-50 transition">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $listing->route_from }} → {{ $listing->route_to }}</p>
                                    <p class="text-sm text-gray-500 mt-1">{{ $listing->available_date->format('M d') }} · ₹{{ number_format($listing->price_per_ton, 0) }}/ton</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold {{ $listing->remaining_capacity < 1 ? 'text-red-600' : 'text-green-600' }}">{{ $listing->remaining_capacity }}/{{ $listing->total_capacity }} {{ __('tons') }}</p>
                                    <x-status-pill :status="$listing->status" />
                                </div>
                            </div>
                            <div class="mt-2 w-full bg-gray-200 rounded-full h-1.5">
                                <div class="h-1.5 rounded-full {{ $listing->capacityPercentage() > 80 ? 'bg-red-500' : 'bg-green-500' }}" style="width: {{ $listing->capacityPercentage() }}%"></div>
                            </div>
                        </a>
                        @empty
                        <div class="p-8 text-center text-gray-400">
                            <p class="text-4xl mb-2">🚛</p>
                            <p>{{ __('No active listings.') }}</p>
                            <a href="{{ route('transporter.listings.create') }}" class="text-green-600 hover:underline text-sm">{{ __('Create your first listing') }}</a>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Active Bookings -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="p-5 border-b flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-900">📋 {{ __('Active Bookings') }}</h2>
                        <a href="{{ route('transporter.bookings.index') }}" class="text-sm text-green-600 hover:text-green-800">{{ __('View All') }} →</a>
                    </div>
                    <div class="divide-y">
                        @forelse($activeBookings as $booking)
                        <a href="{{ route('transporter.bookings.show', $booking) }}" class="block p-4 hover:bg-gray-50 transition">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $booking->farmer->name ?? '—' }}</p>
                                    <p class="text-sm text-gray-500 mt-1">{{ $booking->transportRequest->crop_type ?? '—' }} · {{ $booking->allocated_tons }} {{ __('tons') }}</p>
                                </div>
                                <div class="text-right">
                                    <x-status-pill :status="$booking->status" />
                                    <p class="text-sm font-semibold text-gray-700 mt-1">₹{{ number_format($booking->total_price, 0) }}</p>
                                </div>
                            </div>
                        </a>
                        @empty
                        <div class="p-8 text-center text-gray-400">{{ __('No active bookings.') }}</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right sidebar -->
            <div class="space-y-6">
                @include('components.activity-feed', ['activities' => $activities])

                @if($recentDeliveries->count() > 0)
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-200">
                        <h3 class="font-semibold text-gray-800">✅ {{ __('Recent Deliveries') }}</h3>
                    </div>
                    <div class="divide-y divide-gray-50">
                        @foreach($recentDeliveries->take(4) as $d)
                        <div class="px-5 py-3 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $d->transportRequest->crop_type ?? '—' }}</p>
                                <p class="text-xs text-gray-500">{{ $d->farmer->name ?? '—' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-green-600">₹{{ number_format($d->total_price) }}</p>
                                <p class="text-xs text-gray-400">{{ $d->delivery_confirmed_at?->format('M d') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        new Chart(document.getElementById('earningsChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($monthlyEarnings->map(fn($e) => $e->month.'/'.$e->year)) !!},
                datasets: [{
                    label: '{{ __("Earnings (₹)") }}',
                    data: {!! json_encode($monthlyEarnings->pluck('earnings')) !!},
                    backgroundColor: 'rgba(34, 197, 94, 0.6)',
                    borderColor: '#22c55e',
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true } }, plugins: { legend: { display: false } } }
        });
    </script>
    @endpush
</x-app-layout>
