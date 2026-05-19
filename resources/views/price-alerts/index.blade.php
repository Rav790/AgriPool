<x-app-layout>
    <x-slot name="title">{{ __('Price Alerts') }}</x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">🔔 {{ __('Price Alerts') }}</h1>
                <p class="text-gray-500 mt-1">{{ __('Get notified when crop prices hit your target') }}</p>
            </div>
        </div>

        <!-- Create Alert -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h2 class="font-semibold text-gray-800 mb-4">{{ __('Create New Alert') }}</h2>
            <form method="POST" action="{{ route('farmer.price-alerts.store') }}" class="flex flex-wrap gap-3 items-end">
                @csrf
                <div class="flex-1 min-w-[140px]">
                    <label class="block text-xs font-medium text-gray-500 mb-1">{{ __('Crop') }}</label>
                    <select name="crop_type" required class="w-full rounded-lg border-gray-300 text-sm focus:ring-green-500">
                        @foreach($crops as $crop)
                            <option value="{{ $crop }}">{{ $crop }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-32">
                    <label class="block text-xs font-medium text-gray-500 mb-1">{{ __('Condition') }}</label>
                    <select name="condition" required class="w-full rounded-lg border-gray-300 text-sm focus:ring-green-500">
                        <option value="above">📈 {{ __('Goes above') }}</option>
                        <option value="below">📉 {{ __('Falls below') }}</option>
                    </select>
                </div>
                <div class="w-36">
                    <label class="block text-xs font-medium text-gray-500 mb-1">{{ __('Price (₹/quintal)') }}</label>
                    <input type="number" name="target_price" min="1" required placeholder="2000" class="w-full rounded-lg border-gray-300 text-sm focus:ring-green-500">
                </div>
                <button type="submit" class="px-5 py-2.5 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition">{{ __('Create Alert') }}</button>
            </form>
        </div>

        <!-- Active Alerts -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="font-semibold text-gray-800">{{ __('Active Alerts') }}</h2>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($alerts as $alert)
                    <div class="flex items-center px-6 py-4">
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">
                                {{ $alert->crop_type }}
                                <span class="{{ $alert->condition === 'above' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $alert->condition === 'above' ? '📈 above' : '📉 below' }}
                                </span>
                                ₹{{ number_format($alert->target_price) }}/q
                            </p>
                            <p class="text-xs text-gray-500 mt-0.5">
                                {{ __('Created') }}: {{ $alert->created_at->diffForHumans() }}
                                @if($alert->is_triggered)
                                    · <span class="text-green-600 font-medium">🔔 {{ __('Triggered') }} {{ $alert->triggered_at?->diffForHumans() }}</span>
                                @endif
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <form method="POST" action="{{ route('farmer.price-alerts.toggle', $alert) }}">
                                @csrf
                                <button type="submit" class="px-3 py-1 rounded-full text-xs font-medium {{ $alert->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $alert->is_active ? '✅ Active' : '⏸️ Paused' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('farmer.price-alerts.destroy', $alert) }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-600 text-sm">✕</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 text-gray-400">
                        <div class="text-4xl mb-2">🔔</div>
                        <p>{{ __('No price alerts. Create one above!') }}</p>
                    </div>
                @endforelse
            </div>
            @if($alerts->hasPages())
                <div class="px-6 py-3 border-t border-gray-200">{{ $alerts->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
