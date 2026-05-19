<x-app-layout>
    <x-slot name="title">{{ __('Admin Dashboard') }}</x-slot>
    <x-slot name="header"><h1 class="text-2xl font-bold text-gray-900">{{ __('Admin Dashboard') }}</h1></x-slot>
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <x-stat-card :title="__('Total Users')" :value="$stats['total_users']" color="blue" icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>' />
            <x-stat-card :title="__('Active Bookings')" :value="$stats['active_bookings']" color="amber" icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>' />
            <x-stat-card :title="__('Total Revenue')" :value="number_format($stats['total_revenue'], 0)" prefix="₹" color="green" icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' />
            <x-stat-card :title="__('Pending Requests')" :value="$stats['pending_requests']" color="red" icon='<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' />
        </div>

        <!-- Role Breakdown -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-xl border p-4 text-center"><p class="text-2xl font-bold text-green-600">{{ $stats['total_farmers'] }}</p><p class="text-sm text-gray-500">{{ __('Farmers') }}</p></div>
            <div class="bg-white rounded-xl border p-4 text-center"><p class="text-2xl font-bold text-blue-600">{{ $stats['total_transporters'] }}</p><p class="text-sm text-gray-500">{{ __('Transporters') }}</p></div>
            <div class="bg-white rounded-xl border p-4 text-center"><p class="text-2xl font-bold text-amber-600">{{ $stats['total_agents'] }}</p><p class="text-sm text-gray-500">{{ __('Agents') }}</p></div>
            <div class="bg-white rounded-xl border p-4 text-center"><p class="text-2xl font-bold text-purple-600">{{ $stats['total_markets'] }}</p><p class="text-sm text-gray-500">{{ __('Markets') }}</p></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Top Routes -->
            <div class="bg-white rounded-xl border overflow-hidden">
                <div class="p-5 border-b"><h2 class="text-lg font-semibold">{{ __('Top Routes') }}</h2></div>
                <div class="divide-y">
                    @foreach($topRoutes as $route)
                    <div class="p-4 flex items-center justify-between">
                        <div><p class="font-medium text-sm">{{ $route->route_from }} → {{ $route->route_to }}</p><p class="text-xs text-gray-500">{{ $route->trip_count }} {{ __('trips') }}</p></div>
                        <p class="font-bold text-green-700">₹{{ number_format($route->revenue, 0) }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Recent Users -->
            <div class="bg-white rounded-xl border overflow-hidden">
                <div class="p-5 border-b flex justify-between items-center">
                    <h2 class="text-lg font-semibold">{{ __('Recent Users') }}</h2>
                    <a href="{{ route('admin.users.index') }}" class="text-sm text-green-600">{{ __('View All') }} →</a>
                </div>
                <div class="divide-y">
                    @foreach($recentUsers as $user)
                    <a href="{{ route('admin.users.show', $user) }}" class="block p-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center"><span class="text-green-700 font-bold text-xs">{{ strtoupper(substr($user->name, 0, 1)) }}</span></div>
                                <div><p class="font-medium text-sm">{{ $user->name }}</p><p class="text-xs text-gray-500">{{ $user->email }}</p></div>
                            </div>
                            <div class="text-right">
                                <span class="text-xs px-2 py-0.5 rounded-full {{ $user->is_verified ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ $user->is_verified ? __('Verified') : __('Unverified') }}</span>
                                <p class="text-xs text-gray-400 mt-1 capitalize">{{ $user->role }}</p>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
