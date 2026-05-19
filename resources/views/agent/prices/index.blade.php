<x-app-layout>
    <x-slot name="title">{{ __('Market Prices') }}</x-slot>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('Market Prices') }}</h1>
            <a href="{{ route('agent.prices.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700">+ {{ __('Add Price') }}</a>
        </div>
    </x-slot>
    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-xl border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50"><tr>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Market') }}</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Crop') }}</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Price/Quintal') }}</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Date') }}</th>
                    </tr></thead>
                    <tbody class="divide-y">
                        @forelse($prices as $price)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium">{{ $price->market->name ?? '—' }}</td>
                            <td class="px-4 py-3">{{ $price->crop_type }}</td>
                            <td class="px-4 py-3 font-bold text-green-700">₹{{ number_format($price->price_per_quintal, 0) }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $price->recorded_date->format('M d, Y') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">{{ __('No prices recorded.') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t">{{ $prices->links() }}</div>
        </div>
    </div>
</x-app-layout>
