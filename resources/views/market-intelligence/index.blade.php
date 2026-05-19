<x-app-layout>
    <x-slot name="title">{{ __('Market Intelligence') }}</x-slot>
    <x-slot name="header"><h1 class="text-2xl font-bold text-gray-900">{{ __('Market Intelligence') }}</h1></x-slot>
    <div class="max-w-7xl mx-auto space-y-6">
        <!-- Crop Selector -->
        <form method="GET" class="bg-white rounded-xl border p-4 flex items-end space-x-4">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">{{ __('Select Crop') }}</label>
                <select name="crop" onchange="this.form.submit()" class="rounded-lg border-gray-300 text-sm focus:border-green-500 focus:ring-green-500">
                    @foreach($crops as $crop)
                    <option value="{{ $crop }}" {{ $selectedCrop === $crop ? 'selected' : '' }}>{{ __($crop) }}</option>
                    @endforeach
                </select>
            </div>
        </form>

        <!-- Best Market Recommendation -->
        @if($bestMarket)
        <div class="bg-gradient-to-r from-green-50 to-amber-50 border border-green-200 rounded-xl p-5">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center text-white text-xl">🏆</div>
                <div>
                    <p class="text-sm font-medium text-green-700">{{ __('Best Market Today for :crop', ['crop' => $selectedCrop]) }}</p>
                    <p class="text-xl font-bold text-gray-900">{{ $bestMarket->market->name ?? '—' }} — ₹{{ number_format($bestMarket->price_per_quintal, 0) }}/{{ __('quintal') }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Price Chart -->
        <div class="bg-white rounded-xl border p-6">
            <h2 class="text-lg font-semibold mb-4">{{ __('Price Trend — :crop (Last 30 Days)', ['crop' => $selectedCrop]) }}</h2>
            <canvas id="priceChart" height="80"></canvas>
        </div>

        <!-- Price Table -->
        <div class="bg-white rounded-xl border overflow-hidden">
            <div class="p-5 border-b"><h2 class="text-lg font-semibold">{{ __('Today\'s Prices — :crop', ['crop' => $selectedCrop]) }}</h2></div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50"><tr>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Market') }}</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('City') }}</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Type') }}</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Price/Quintal') }}</th>
                    </tr></thead>
                    <tbody class="divide-y">
                        @forelse($latestPrices as $price)
                        <tr class="hover:bg-gray-50 {{ $loop->first ? 'bg-green-50' : '' }}">
                            <td class="px-4 py-3 font-medium">{{ $price->market->name ?? '—' }} {{ $loop->first ? '🏆' : '' }}</td>
                            <td class="px-4 py-3">{{ $price->market->city ?? '—' }}</td>
                            <td class="px-4 py-3 capitalize">{{ $price->market->type ?? '—' }}</td>
                            <td class="px-4 py-3 font-bold text-green-700">₹{{ number_format($price->price_per_quintal, 0) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">{{ __('No price data available.') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @push('scripts')
    <script>
        fetch('/api/market-prices/{{ $selectedCrop }}')
            .then(r => r.json())
            .then(data => {
                const ctx = document.getElementById('priceChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.map(d => d.recorded_date),
                        datasets: [
                            { label: '{{ __("Average") }}', data: data.map(d => d.avg_price), borderColor: '#16a34a', backgroundColor: 'rgba(22,163,74,0.1)', fill: true, tension: 0.3 },
                            { label: '{{ __("Max") }}', data: data.map(d => d.max_price), borderColor: '#d97706', borderDash: [5, 5], fill: false, tension: 0.3 },
                            { label: '{{ __("Min") }}', data: data.map(d => d.min_price), borderColor: '#dc2626', borderDash: [5, 5], fill: false, tension: 0.3 },
                        ]
                    },
                    options: { responsive: true, plugins: { legend: { position: 'top' } }, scales: { y: { beginAtZero: false } } }
                });
            });
    </script>
    @endpush
</x-app-layout>
