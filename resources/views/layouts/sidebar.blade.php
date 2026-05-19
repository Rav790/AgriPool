<!-- Sidebar Overlay (mobile) -->
<div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-20 lg:hidden" x-transition.opacity></div>

<!-- Sidebar -->
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed top-16 left-0 z-20 w-64 h-[calc(100vh-4rem)] bg-white border-r border-gray-200 transition-transform duration-300 lg:translate-x-0 overflow-y-auto">
    <div class="p-4">
        <!-- Role Badge -->
        <div class="mb-6 p-3 bg-green-50 rounded-lg border border-green-100">
            <p class="text-xs font-medium text-green-600 uppercase tracking-wider">{{ __('Logged in as') }}</p>
            <p class="text-sm font-bold text-green-800 capitalize mt-1">{{ __(auth()->user()->role) }}</p>
        </div>

        <nav class="space-y-1">
            @if(auth()->user()->role === 'farmer')
                {{-- Farmer Menu --}}
                <a href="{{ route('farmer.dashboard') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('farmer.dashboard') ? 'bg-green-100 text-green-800' : 'text-gray-600 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    {{ __('Dashboard') }}
                </a>
                <a href="{{ route('farmer.requests.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('farmer.requests.*') ? 'bg-green-100 text-green-800' : 'text-gray-600 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    {{ __('My Requests') }}
                </a>
                <a href="{{ route('farmer.bookings.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('farmer.bookings.*') ? 'bg-green-100 text-green-800' : 'text-gray-600 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ __('My Bookings') }}
                </a>
                <a href="{{ route('farmer.market-prices') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('farmer.market-prices') ? 'bg-green-100 text-green-800' : 'text-gray-600 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    {{ __('Market Prices') }}
                </a>
                <a href="{{ route('farmer.wallet') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('farmer.wallet') ? 'bg-green-100 text-green-800' : 'text-gray-600 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    {{ __('Wallet') }}
                </a>

                <div class="pt-3 mt-3 border-t border-gray-200">
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">{{ __('Tools') }}</p>
                </div>
                <a href="{{ route('farmer.cooperatives.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('farmer.cooperatives.*') ? 'bg-green-100 text-green-800' : 'text-gray-600 hover:bg-gray-100' }}">
                    <span class="w-5 h-5 mr-3 text-center">🤝</span> {{ __('Cooperatives') }}
                </a>
                <a href="{{ route('farmer.price-alerts.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('farmer.price-alerts.*') ? 'bg-green-100 text-green-800' : 'text-gray-600 hover:bg-gray-100' }}">
                    <span class="w-5 h-5 mr-3 text-center">🔔</span> {{ __('Price Alerts') }}
                </a>
                <a href="{{ route('kyc.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('kyc.*') ? 'bg-green-100 text-green-800' : 'text-gray-600 hover:bg-gray-100' }}">
                    <span class="w-5 h-5 mr-3 text-center">🪪</span> {{ __('KYC') }}
                </a>
                <a href="{{ route('help.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('help.*') ? 'bg-green-100 text-green-800' : 'text-gray-600 hover:bg-gray-100' }}">
                    <span class="w-5 h-5 mr-3 text-center">🎧</span> {{ __('Help') }}
                </a>
                <a href="{{ route('crop-calendar') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('crop-calendar') ? 'bg-green-100 text-green-800' : 'text-gray-600 hover:bg-gray-100' }}">
                    <span class="w-5 h-5 mr-3 text-center">📅</span> {{ __('Crop Calendar') }}
                </a>
                <a href="{{ route('disputes.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('disputes.*') ? 'bg-green-100 text-green-800' : 'text-gray-600 hover:bg-gray-100' }}">
                    <span class="w-5 h-5 mr-3 text-center">⚖️</span> {{ __('Disputes') }}
                </a>
                <a href="{{ route('messages.hub') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('messages.*') ? 'bg-green-100 text-green-800' : 'text-gray-600 hover:bg-gray-100' }}">
                    <span class="w-5 h-5 mr-3 text-center">💬</span> {{ __('Messages') }}
                </a>

            @elseif(auth()->user()->role === 'transporter')
                {{-- Transporter Menu --}}
                <a href="{{ route('transporter.dashboard') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('transporter.dashboard') ? 'bg-green-100 text-green-800' : 'text-gray-600 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    {{ __('Dashboard') }}
                </a>
                <a href="{{ route('transporter.listings.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('transporter.listings.*') ? 'bg-green-100 text-green-800' : 'text-gray-600 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    {{ __('My Listings') }}
                </a>
                <a href="{{ route('transporter.bookings.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('transporter.bookings.*') ? 'bg-green-100 text-green-800' : 'text-gray-600 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ __('Bookings') }}
                </a>
                <a href="{{ route('transporter.wallet') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('transporter.wallet') ? 'bg-green-100 text-green-800' : 'text-gray-600 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    {{ __('Wallet') }}
                </a>
                <div class="pt-3 mt-3 border-t border-gray-200">
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">{{ __('Tools') }}</p>
                </div>
                <a href="{{ route('kyc.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('kyc.*') ? 'bg-green-100 text-green-800' : 'text-gray-600 hover:bg-gray-100' }}">
                    <span class="w-5 h-5 mr-3 text-center">🪪</span> {{ __('KYC') }}
                </a>
                <a href="{{ route('help.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('help.*') ? 'bg-green-100 text-green-800' : 'text-gray-600 hover:bg-gray-100' }}">
                    <span class="w-5 h-5 mr-3 text-center">🎧</span> {{ __('Help') }}
                </a>
                <a href="{{ route('messages.hub') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('messages.*') ? 'bg-green-100 text-green-800' : 'text-gray-600 hover:bg-gray-100' }}">
                    <span class="w-5 h-5 mr-3 text-center">💬</span> {{ __('Messages') }}
                </a>

            @elseif(auth()->user()->role === 'agent')
                {{-- Agent Menu --}}
                <a href="{{ route('agent.dashboard') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('agent.dashboard') ? 'bg-green-100 text-green-800' : 'text-gray-600 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    {{ __('Dashboard') }}
                </a>
                <a href="{{ route('agent.prices.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('agent.prices.*') ? 'bg-green-100 text-green-800' : 'text-gray-600 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ __('Market Prices') }}
                </a>
                <a href="{{ route('agent.deliveries') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('agent.deliveries') ? 'bg-green-100 text-green-800' : 'text-gray-600 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ __('Deliveries') }}
                </a>
                <a href="{{ route('help.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('help.*') ? 'bg-green-100 text-green-800' : 'text-gray-600 hover:bg-gray-100' }}">
                    <span class="w-5 h-5 mr-3 text-center">🎧</span> {{ __('Help') }}
                </a>

            @elseif(auth()->user()->role === 'admin')
                {{-- Admin Menu --}}
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-green-100 text-green-800' : 'text-gray-600 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    {{ __('Dashboard') }}
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-green-100 text-green-800' : 'text-gray-600 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    {{ __('Users') }}
                </a>
                <a href="{{ route('admin.bookings.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('admin.bookings.*') ? 'bg-green-100 text-green-800' : 'text-gray-600 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ __('Bookings') }}
                </a>
                <a href="{{ route('admin.markets.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('admin.markets.*') ? 'bg-green-100 text-green-800' : 'text-gray-600 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    {{ __('Markets') }}
                </a>
                <a href="{{ route('admin.analytics') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('admin.analytics') ? 'bg-green-100 text-green-800' : 'text-gray-600 hover:bg-gray-100' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    {{ __('Analytics') }}
                </a>
                <div class="pt-3 mt-3 border-t border-gray-200">
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">{{ __('Support') }}</p>
                </div>
                <a href="{{ route('admin.help.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('admin.help.*') ? 'bg-green-100 text-green-800' : 'text-gray-600 hover:bg-gray-100' }}">
                    <span class="w-5 h-5 mr-3 text-center">🎫</span> {{ __('Help Tickets') }}
                </a>
                <a href="{{ route('admin.disputes.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('admin.disputes.*') ? 'bg-green-100 text-green-800' : 'text-gray-600 hover:bg-gray-100' }}">
                    <span class="w-5 h-5 mr-3 text-center">⚖️</span> {{ __('Disputes') }}
                </a>
            @endif
        </nav>
    </div>
</aside>
