<x-app-layout>
    <x-slot name="title">{{ __('Fare Calculator') }}</x-slot>

    <div class="max-w-4xl mx-auto" x-data="fareCalculator()">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">🧮 {{ __('Fare Calculator') }}</h1>
                <p class="text-gray-500 mt-1">{{ __('Estimate your transport cost and see how much you save by pooling') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Input Form -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold mb-4">{{ __('Trip Details') }}</h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Distance (km)') }}</label>
                        <input type="number" x-model="distance" min="1" max="5000" placeholder="e.g. 200"
                               class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Weight (tons)') }}</label>
                        <input type="number" x-model="weight" min="0.1" max="50" step="0.1" placeholder="e.g. 5"
                               class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Vehicle Type') }}</label>
                        <select x-model="vehicle" class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">
                            <option value="truck">🚛 Truck (10+ tons)</option>
                            <option value="mini-truck">🚚 Mini-Truck (4-8 tons)</option>
                            <option value="pickup">🛻 Pickup (1-3 tons)</option>
                            <option value="tractor">🚜 Tractor-Trolley (3-5 tons)</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" x-model="perishable" id="perishable"
                               class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                        <label for="perishable" class="text-sm text-gray-700">🧊 {{ __('Perishable goods (cold chain surcharge)') }}</label>
                    </div>

                    <button @click="calculate()" :disabled="loading"
                            class="w-full bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700 transition flex items-center justify-center gap-2 disabled:opacity-50">
                        <span x-show="!loading">{{ __('Calculate Fare') }}</span>
                        <span x-show="loading" class="flex items-center gap-2">
                            <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            {{ __('Calculating...') }}
                        </span>
                    </button>
                </div>
            </div>

            <!-- Results -->
            <div>
                <template x-if="result">
                    <div class="space-y-4">
                        <!-- Savings Hero -->
                        <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl p-6 text-white">
                            <p class="text-green-100 text-sm font-medium">{{ __('With AgriPool Pooling') }}</p>
                            <p class="text-4xl font-bold mt-1">₹<span x-text="Number(result.pooled_cost).toLocaleString('en-IN')"></span></p>
                            <div class="flex items-center gap-2 mt-3">
                                <span class="bg-white/20 px-3 py-1 rounded-full text-sm font-medium">
                                    🎉 {{ __('You save') }} ₹<span x-text="Number(result.savings).toLocaleString('en-IN')"></span>
                                </span>
                                <span class="bg-yellow-400 text-yellow-900 px-3 py-1 rounded-full text-sm font-bold">
                                    <span x-text="result.savings_percent"></span>% OFF
                                </span>
                            </div>
                        </div>

                        <!-- Breakdown -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                            <h3 class="font-semibold text-gray-900 mb-3">{{ __('Cost Breakdown') }}</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">{{ __('Base Transport Cost') }}</span>
                                    <span class="font-medium">₹<span x-text="Number(result.base_cost).toLocaleString('en-IN')"></span></span>
                                </div>
                                <div class="flex justify-between text-sm" x-show="result.perishable_surcharge > 0">
                                    <span class="text-gray-600">🧊 {{ __('Perishable Surcharge (15%)') }}</span>
                                    <span class="font-medium text-orange-600">+₹<span x-text="Number(result.perishable_surcharge).toLocaleString('en-IN')"></span></span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">📋 {{ __('Platform Fee (5%)') }}</span>
                                    <span class="font-medium">₹<span x-text="Number(result.platform_fee).toLocaleString('en-IN')"></span></span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">🛡️ {{ __('Insurance (2%)') }}</span>
                                    <span class="font-medium">₹<span x-text="Number(result.insurance).toLocaleString('en-IN')"></span></span>
                                </div>
                                <hr>
                                <div class="flex justify-between font-semibold">
                                    <span>{{ __('Total (without pooling)') }}</span>
                                    <span class="line-through text-gray-400">₹<span x-text="Number(result.total_cost).toLocaleString('en-IN')"></span></span>
                                </div>
                                <div class="flex justify-between font-bold text-green-600 text-lg">
                                    <span>{{ __('Pooled Total') }}</span>
                                    <span>₹<span x-text="Number(result.pooled_cost).toLocaleString('en-IN')"></span></span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-sm text-blue-800">
                            💡 {{ __('Rate: ₹') }}<span x-text="result.rate_per_km"></span>/km · {{ __('Pooling discount applied based on average route utilization.') }}
                        </div>
                    </div>
                </template>

                <template x-if="!result">
                    <div class="bg-gray-50 rounded-xl border-2 border-dashed border-gray-300 p-12 text-center">
                        <div class="text-5xl mb-3">🚛</div>
                        <p class="text-gray-500">{{ __('Enter trip details to see estimated fare and pooling savings') }}</p>
                    </div>
                </template>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    function fareCalculator() {
        return {
            distance: '', weight: '', vehicle: 'truck', perishable: false,
            loading: false, result: null,
            async calculate() {
                if (!this.distance || !this.weight) return;
                this.loading = true;
                try {
                    const res = await fetch('{{ route("fare-calculator.calculate") }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                        body: JSON.stringify({ distance_km: this.distance, weight_tons: this.weight, vehicle_type: this.vehicle, is_perishable: this.perishable })
                    });
                    this.result = await res.json();
                } catch (e) { console.error(e); }
                this.loading = false;
            }
        }
    }
    </script>
    @endpush
</x-app-layout>
