<x-app-layout>
    <x-slot name="title">{{ __('Farmer Dashboard') }}</x-slot>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ __('Welcome back, :name!', ['name' => auth()->user()->name]) }}</h1>
                <p class="text-sm text-gray-500 mt-1">{{ __('Here\'s what\'s happening with your produce today') }}</p>
            </div>
            <a href="{{ route('farmer.requests.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                {{ __('New Request') }}
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto">

        <!-- Top Row: Stats + Weather -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-6">
            <div class="lg:col-span-3">
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
                    <div class="bg-white rounded-xl border border-gray-200 p-4 hover-lift">
                        <p class="text-xs text-gray-500 font-medium">{{ __('Requests') }}</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_requests'] }}</p>
                        <p class="text-xs text-amber-600 mt-1">{{ $stats['pending_requests'] }} {{ __('pending') }}</p>
                    </div>
                    <div class="bg-white rounded-xl border border-gray-200 p-4 hover-lift">
                        <p class="text-xs text-gray-500 font-medium">{{ __('Active') }}</p>
                        <p class="text-2xl font-bold text-purple-600 mt-1">{{ $stats['active_bookings'] }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ __('in transit') }}</p>
                    </div>
                    <div class="bg-white rounded-xl border border-gray-200 p-4 hover-lift">
                        <p class="text-xs text-gray-500 font-medium">{{ __('Delivered') }}</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">{{ $stats['delivered'] }}</p>
                        <p class="text-xs text-green-500 mt-1">✅ {{ __('completed') }}</p>
                    </div>
                    <div class="bg-white rounded-xl border border-gray-200 p-4 hover-lift">
                        <p class="text-xs text-gray-500 font-medium">{{ __('Wallet') }}</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">₹{{ number_format($stats['wallet_balance']) }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ __('available') }}</p>
                    </div>
                    <div class="bg-white rounded-xl border border-gray-200 p-4 hover-lift">
                        <p class="text-xs text-gray-500 font-medium">{{ __('Total Spent') }}</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">₹{{ number_format($stats['total_spent']) }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ __('on transport') }}</p>
                    </div>
                    <div class="bg-white rounded-xl border border-gray-200 p-4 hover-lift">
                        <p class="text-xs text-gray-500 font-medium">{{ __('Trust Score') }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <p class="text-2xl font-bold {{ $stats['trust_score'] >= 60 ? 'text-green-600' : ($stats['trust_score'] >= 30 ? 'text-amber-600' : 'text-red-500') }}">{{ $stats['trust_score'] }}</p>
                            <span class="text-xs text-gray-400">/100</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                            <div class="h-1.5 rounded-full {{ $stats['trust_score'] >= 60 ? 'bg-green-500' : ($stats['trust_score'] >= 30 ? 'bg-amber-500' : 'bg-red-500') }}" style="width: {{ $stats['trust_score'] }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Weather Widget -->
            <div class="lg:col-span-1">
                @include('components.weather-widget')
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
            <a href="{{ route('farmer.requests.create') }}" class="bg-white rounded-xl border border-gray-200 p-4 text-center hover:border-green-300 hover:bg-green-50 transition group">
                <p class="text-2xl mb-1">📝</p>
                <p class="text-sm font-medium text-gray-700 group-hover:text-green-700">{{ __('New Request') }}</p>
            </a>
            <a href="{{ route('load-board') }}" class="bg-white rounded-xl border border-gray-200 p-4 text-center hover:border-green-300 hover:bg-green-50 transition group">
                <p class="text-2xl mb-1">📦</p>
                <p class="text-sm font-medium text-gray-700 group-hover:text-green-700">{{ __('Load Board') }}</p>
            </a>
            <a href="{{ route('fare-calculator') }}" class="bg-white rounded-xl border border-gray-200 p-4 text-center hover:border-green-300 hover:bg-green-50 transition group">
                <p class="text-2xl mb-1">🧮</p>
                <p class="text-sm font-medium text-gray-700 group-hover:text-green-700">{{ __('Fare Calculator') }}</p>
            </a>
            <a href="{{ route('farmer.market-prices') }}" class="bg-white rounded-xl border border-gray-200 p-4 text-center hover:border-green-300 hover:bg-green-50 transition group">
                <p class="text-2xl mb-1">📊</p>
                <p class="text-sm font-medium text-gray-700 group-hover:text-green-700">{{ __('Market Prices') }}</p>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left column (2/3) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Available Vehicles for Selection -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gradient-to-r from-green-50 to-white">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">🚛 {{ __('Available Vehicles / Transporters') }}</h2>
                            <p class="text-xs text-gray-500 mt-0.5">{{ __('Select a vehicle below to transport your pending produce requests') }}</p>
                        </div>
                        <span class="text-xs bg-green-100 text-green-800 font-bold px-2.5 py-1 rounded-full">{{ $availableVehicles->count() }} {{ __('Available') }}</span>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse($availableVehicles as $vehicle)
                            <div class="p-5 hover:bg-gray-50 transition block">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <p class="font-bold text-gray-900 text-base">{{ $vehicle->transporter->name ?? __('Transporter') }}</p>
                                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded">{{ $vehicle->vehicle_type }}</span>
                                        </div>
                                        <p class="text-sm font-medium text-gray-700 mt-1">📍 {{ $vehicle->route_from }} → {{ $vehicle->route_to }}</p>
                                        <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                                            <span>📅 {{ __('Date') }}: <strong class="text-gray-700">{{ $vehicle->available_date->format('M d, Y') }}</strong></span>
                                            <span>💵 {{ __('Fare') }}: <strong class="text-green-600">₹{{ number_format($vehicle->price_per_ton) }}/ton</strong></span>
                                            <span>⚖️ {{ __('Available') }}: <strong class="text-indigo-600">{{ $vehicle->remaining_capacity }} {{ __('tons') }}</strong></span>
                                        </div>
                                    </div>
                                    
                                    <div class="sm:max-w-xs w-full bg-white p-3 rounded-lg border border-gray-200 shadow-sm">
                                        @if($pendingRequests->count() > 0)
                                            <form action="{{ route('bookings.store') }}" method="POST" class="space-y-2">
                                                @csrf
                                                <input type="hidden" name="listing_id" value="{{ $vehicle->id }}">
                                                
                                                <div>
                                                    <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">{{ __('Select Produce Request') }}</label>
                                                    <select name="request_id" class="w-full text-xs rounded border-gray-300 py-1 px-2 focus:ring-green-500" required>
                                                        @foreach($pendingRequests as $req)
                                                            <option value="{{ $req->id }}">{{ $req->crop_type }} ({{ $req->quantity_tons }}T)</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                
                                                <div class="flex items-center gap-2">
                                                    <div class="flex-1">
                                                        <label class="block text-[10px] font-bold text-gray-500 uppercase">{{ __('Tons') }}</label>
                                                        <input type="number" name="allocated_tons" min="0.1" max="{{ $vehicle->remaining_capacity }}" step="0.1" value="{{ min($pendingRequests->first()->quantity_tons ?? 1, $vehicle->remaining_capacity) }}" class="w-full text-xs rounded border-gray-300 py-1 px-2 focus:ring-green-500" required>
                                                    </div>
                                                    <button type="submit" class="mt-4 bg-green-600 text-white font-bold text-xs px-3 py-1.5 rounded hover:bg-green-700 transition self-end whitespace-nowrap">
                                                        {{ __('Book Vehicle') }}
                                                    </button>
                                                </div>
                                            </form>
                                        @else
                                            <div class="text-center py-1">
                                                <p class="text-xs text-gray-500 mb-2">{{ __('No pending requests to book.') }}</p>
                                                <a href="{{ route('farmer.requests.create') }}" class="block text-center bg-gray-900 text-white font-bold text-xs py-1.5 rounded hover:bg-gray-800 transition">
                                                    {{ __('Create Request First') }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-gray-400">
                                <p class="text-3xl mb-2">🚛</p>
                                <p class="text-sm">{{ __('No vehicles available right now.') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Requests -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="p-5 border-b border-gray-100 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-900">{{ __('Recent Requests') }}</h2>
                        <a href="{{ route('farmer.requests.index') }}" class="text-sm text-green-600 hover:text-green-800">{{ __('View All') }} →</a>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse($recentRequests as $request)
                            <a href="{{ route('farmer.requests.show', $request) }}" class="block p-4 hover:bg-gray-50 transition">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $request->crop_type }} — {{ $request->quantity_tons }} {{ __('tons') }}</p>
                                        <p class="text-sm text-gray-500 mt-1">{{ __('To') }}: {{ $request->destinationMarket->name ?? '—' }}</p>
                                    </div>
                                    <div class="text-right">
                                        <x-status-pill :status="$request->status" />
                                        <p class="text-xs text-gray-400 mt-1">{{ $request->required_date->format('M d') }}</p>
                                    </div>
                                </div>
                                @if($request->hasSpoilageRisk())
                                    <div class="mt-2 text-xs text-red-600 bg-red-50 px-2 py-1 rounded-md inline-flex items-center">
                                        ⚠️ {{ __('Spoilage risk! Schedule pickup soon.') }}
                                    </div>
                                @endif
                            </a>
                        @empty
                            <div class="p-8 text-center text-gray-400">
                                <p class="text-4xl mb-2">📝</p>
                                <p>{{ __('No transport requests yet.') }}</p>
                                <a href="{{ route('farmer.requests.create') }}" class="text-green-600 hover:underline text-sm mt-1 inline-block">{{ __('Create your first request') }}</a>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Active Bookings -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="p-5 border-b border-gray-100 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-900">{{ __('Active Bookings') }}</h2>
                        <a href="{{ route('farmer.bookings.index') }}" class="text-sm text-green-600 hover:text-green-800">{{ __('View All') }} →</a>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse($activeBookings as $booking)
                            <a href="{{ route('farmer.bookings.show', $booking) }}" class="block p-4 hover:bg-gray-50 transition">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $booking->transportRequest->crop_type ?? '—' }} — {{ $booking->allocated_tons }} {{ __('tons') }}</p>
                                        <p class="text-sm text-gray-500 mt-1">🚛 {{ $booking->transportListing->transporter->name ?? '—' }}</p>
                                    </div>
                                    <div class="text-right">
                                        <x-status-pill :status="$booking->status" />
                                        <p class="text-sm font-semibold text-gray-700 mt-1">₹{{ number_format($booking->total_price, 0) }}</p>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="p-8 text-center text-gray-400">
                                <p>{{ __('No active bookings.') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right column (1/3) — Activity Feed -->
            <div class="space-y-6">
                @include('components.activity-feed', ['activities' => $activities])

                <!-- Recent Deliveries Mini -->
                @if($recentDeliveries->count() > 0)
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-200">
                        <h3 class="font-semibold text-gray-800">🏆 {{ __('Recent Deliveries') }}</h3>
                    </div>
                    <div class="divide-y divide-gray-50">
                        @foreach($recentDeliveries->take(4) as $d)
                        <div class="px-5 py-3 flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $d->transportRequest->crop_type ?? '—' }}</p>
                                <p class="text-xs text-gray-500">{{ $d->transporter->name ?? '—' }}</p>
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

    <!-- Onboarding Wizard -->
    @include('components.onboarding-wizard')
</x-app-layout>
