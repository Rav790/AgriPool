<x-app-layout>
    <x-slot name="title">{{ __('Leaderboard') }}</x-slot>

    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">🏆 {{ __('AgriPool Leaderboard') }}</h1>
            <p class="text-gray-500 mt-2">{{ __('Celebrating our top performers and platform impact') }}</p>
        </div>

        <!-- Platform Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl p-5 text-white text-center">
                <p class="text-3xl font-bold">{{ number_format($stats['total_shipments']) }}</p>
                <p class="text-green-100 text-sm mt-1">{{ __('Successful Deliveries') }}</p>
            </div>
            <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl p-5 text-white text-center">
                <p class="text-3xl font-bold">{{ number_format($stats['total_volume'], 1) }}</p>
                <p class="text-blue-100 text-sm mt-1">{{ __('Tons Transported') }}</p>
            </div>
            <div class="bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl p-5 text-white text-center">
                <p class="text-3xl font-bold">₹{{ number_format($stats['total_saved']) }}</p>
                <p class="text-yellow-100 text-sm mt-1">{{ __('Saved by Pooling') }}</p>
            </div>
            <div class="bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl p-5 text-white text-center">
                <p class="text-3xl font-bold">{{ $stats['active_users'] }}</p>
                <p class="text-purple-100 text-sm mt-1">{{ __('Active Users') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Top Transporters -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
                    <h2 class="text-white font-bold text-lg">🚛 {{ __('Top Transporters') }}</h2>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($topTransporters as $i => $t)
                        <div class="flex items-center px-6 py-4 {{ $i < 3 ? 'bg-yellow-50/50' : '' }}">
                            <div class="w-8 text-center font-bold {{ $i === 0 ? 'text-yellow-500 text-xl' : ($i === 1 ? 'text-gray-400 text-lg' : ($i === 2 ? 'text-orange-400' : 'text-gray-400')) }}">
                                {{ $i === 0 ? '🥇' : ($i === 1 ? '🥈' : ($i === 2 ? '🥉' : '#'.($i+1))) }}
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="font-semibold text-gray-900">{{ $t->name }}</p>
                                <p class="text-xs text-gray-500">{{ $t->transporter_bookings_count }} {{ __('trips') }}</p>
                            </div>
                            <div class="text-right">
                                <div class="flex items-center gap-1">
                                    <span class="text-yellow-500">★</span>
                                    <span class="font-bold text-gray-900">{{ number_format($t->reviews_received_avg_rating ?? 0, 1) }}</span>
                                </div>
                                <p class="text-xs text-gray-400">{{ $t->reviews_received_count }} {{ __('reviews') }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-400">{{ __('No ratings yet') }}</div>
                    @endforelse
                </div>
            </div>

            <!-- Top Farmers -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-yellow-500 to-orange-500 px-6 py-4">
                    <h2 class="text-white font-bold text-lg">🌾 {{ __('Top Farmers') }}</h2>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($topFarmers as $i => $f)
                        <div class="flex items-center px-6 py-4 {{ $i < 3 ? 'bg-yellow-50/50' : '' }}">
                            <div class="w-8 text-center font-bold">
                                {{ $i === 0 ? '🥇' : ($i === 1 ? '🥈' : ($i === 2 ? '🥉' : '#'.($i+1))) }}
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="font-semibold text-gray-900">{{ $f->name }}</p>
                            </div>
                            <div class="text-right">
                                <span class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-bold">{{ $f->farmer_bookings_count }} {{ __('bookings') }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-400">{{ __('No bookings yet') }}</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Top Routes -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mt-6 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                <h2 class="text-white font-bold text-lg">🗺️ {{ __('Most Popular Routes') }}</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                        <tr><th class="px-6 py-3 text-left">#</th><th class="px-6 py-3 text-left">{{ __('Route') }}</th><th class="px-6 py-3 text-right">{{ __('Trips') }}</th><th class="px-6 py-3 text-right">{{ __('Revenue') }}</th></tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($topRoutes as $i => $r)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 font-bold text-gray-400">{{ $i + 1 }}</td>
                                <td class="px-6 py-3 font-medium text-gray-900">{{ $r->route_from }} → {{ $r->route_to }}</td>
                                <td class="px-6 py-3 text-right"><span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-sm">{{ $r->trip_count }}</span></td>
                                <td class="px-6 py-3 text-right font-semibold text-green-600">₹{{ number_format($r->total_revenue) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center py-8 text-gray-400">{{ __('No route data yet') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
