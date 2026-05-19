<x-app-layout>
    <x-slot name="title">{{ __('Analytics') }}</x-slot>

    <div class="max-w-7xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">📊 {{ __('Platform Analytics') }}</h1>
                <p class="text-gray-500 mt-1">{{ __('Data-driven insights for platform growth') }}</p>
            </div>
            <span class="text-xs text-gray-400">{{ __('Last updated') }}: {{ now()->format('M d, Y h:i A') }}</span>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl border border-gray-200 p-5 hover-lift">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Revenue This Month') }}</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">₹{{ number_format($kpis['revenue_this_month']) }}</p>
                @php $revChange = $kpis['revenue_last_month'] > 0 ? round(($kpis['revenue_this_month'] - $kpis['revenue_last_month']) / $kpis['revenue_last_month'] * 100, 1) : 0; @endphp
                <p class="text-xs mt-1 {{ $revChange >= 0 ? 'text-green-600' : 'text-red-600' }}">{{ $revChange >= 0 ? '↑' : '↓' }} {{ abs($revChange) }}% {{ __('vs last month') }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-5 hover-lift">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Bookings This Month') }}</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($kpis['bookings_this_month']) }}</p>
                @php $bkgChange = $kpis['bookings_last_month'] > 0 ? round(($kpis['bookings_this_month'] - $kpis['bookings_last_month']) / $kpis['bookings_last_month'] * 100, 1) : 0; @endphp
                <p class="text-xs mt-1 {{ $bkgChange >= 0 ? 'text-green-600' : 'text-red-600' }}">{{ $bkgChange >= 0 ? '↑' : '↓' }} {{ abs($bkgChange) }}% {{ __('vs last month') }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-5 hover-lift">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Avg Booking Value') }}</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">₹{{ number_format($kpis['avg_booking_value']) }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ __('per delivered booking') }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-5 hover-lift">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Completion Rate') }}</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">{{ $kpis['completion_rate'] }}%</p>
                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ min($kpis['completion_rate'], 100) }}%"></div>
                </div>
            </div>
        </div>

        <!-- Charts Row 1 -->
        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Revenue Trend (2/3 width) -->
            <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">📈 {{ __('Revenue & Bookings Trend') }}</h2>
                <canvas id="revenueChart" height="120"></canvas>
            </div>
            <!-- Booking Status Doughnut (1/3 width) -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">🎯 {{ __('Booking Status') }}</h2>
                <canvas id="statusChart" height="200"></canvas>
            </div>
        </div>

        <!-- Charts Row 2 -->
        <div class="grid lg:grid-cols-2 gap-6">
            <!-- User Growth -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">👥 {{ __('User Growth') }}</h2>
                <canvas id="userGrowthChart" height="140"></canvas>
            </div>
            <!-- Revenue by Crop -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">🌾 {{ __('Revenue by Crop') }}</h2>
                <canvas id="cropRevenueChart" height="140"></canvas>
            </div>
        </div>

        <!-- Crop Demand Table -->
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">📋 {{ __('Crop Demand Analysis') }}</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Crop') }}</th>
                            <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Requests') }}</th>
                            <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Total Tons') }}</th>
                            <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Market Share') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @php $totalDemand = $cropDemand->sum('demand_count'); @endphp
                        @foreach($cropDemand as $crop)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $crop->crop_type }}</td>
                            <td class="px-4 py-3">{{ $crop->demand_count }}</td>
                            <td class="px-4 py-3">{{ number_format($crop->total_tons, 1) }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 bg-gray-200 rounded-full h-2 max-w-[120px]">
                                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ $totalDemand > 0 ? round($crop->demand_count / $totalDemand * 100) : 0 }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-500">{{ $totalDemand > 0 ? round($crop->demand_count / $totalDemand * 100) : 0 }}%</span>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const chartColors = ['#22c55e', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#06b6d4', '#84cc16'];

        // Revenue & Bookings Trend
        new Chart(document.getElementById('revenueChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: {!! json_encode($monthlyBookings->map(fn($m) => $m->month.'/'.$m->year)) !!},
                datasets: [{
                    label: '{{ __("Revenue (₹)") }}',
                    data: {!! json_encode($monthlyBookings->pluck('revenue')) !!},
                    borderColor: '#22c55e',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    fill: true,
                    tension: 0.4,
                    yAxisID: 'y'
                }, {
                    label: '{{ __("Bookings") }}',
                    data: {!! json_encode($monthlyBookings->pluck('total')) !!},
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4,
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true,
                interaction: { mode: 'index', intersect: false },
                plugins: { legend: { position: 'top' } },
                scales: {
                    y: { type: 'linear', display: true, position: 'left', beginAtZero: true, title: { display: true, text: '{{ __("Revenue (₹)") }}' } },
                    y1: { type: 'linear', display: true, position: 'right', beginAtZero: true, grid: { drawOnChartArea: false }, title: { display: true, text: '{{ __("Bookings") }}' } }
                }
            }
        });

        // Booking Status Doughnut
        new Chart(document.getElementById('statusChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($statusDistribution->pluck('status')->map(fn($s) => ucfirst($s))) !!},
                datasets: [{
                    data: {!! json_encode($statusDistribution->pluck('count')) !!},
                    backgroundColor: chartColors.slice(0, {{ $statusDistribution->count() }}),
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: { responsive: true, cutout: '60%', plugins: { legend: { position: 'bottom', labels: { boxWidth: 12 } } } }
        });

        // User Growth
        new Chart(document.getElementById('userGrowthChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($userGrowth->map(fn($u) => $u->month.'/'.$u->year)) !!},
                datasets: [{
                    label: '{{ __("New Users") }}',
                    data: {!! json_encode($userGrowth->pluck('count')) !!},
                    backgroundColor: 'rgba(139, 92, 246, 0.6)',
                    borderColor: '#8b5cf6',
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true } }, plugins: { legend: { display: false } } }
        });

        // Revenue by Crop
        new Chart(document.getElementById('cropRevenueChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($revenueByCrop->pluck('crop_type')) !!},
                datasets: [{
                    label: '{{ __("Revenue (₹)") }}',
                    data: {!! json_encode($revenueByCrop->pluck('revenue')) !!},
                    backgroundColor: chartColors.slice(0, {{ $revenueByCrop->count() }}),
                    borderWidth: 0,
                    borderRadius: 6
                }]
            },
            options: { indexAxis: 'y', responsive: true, scales: { x: { beginAtZero: true } }, plugins: { legend: { display: false } } }
        });
    </script>
    @endpush
</x-app-layout>
