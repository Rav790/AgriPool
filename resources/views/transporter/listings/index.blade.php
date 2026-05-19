<x-app-layout>
    <x-slot name="title">{{ __('My Listings') }}</x-slot>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('My Transport Listings') }}</h1>
            <a href="{{ route('transporter.listings.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition">+ {{ __('New Listing') }}</a>
        </div>
    </x-slot>
    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50"><tr>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Route') }}</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Date') }}</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Capacity') }}</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Price/Ton') }}</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Status') }}</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Actions') }}</th>
                    </tr></thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($listings as $listing)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium">{{ $listing->route_from }} → {{ $listing->route_to }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $listing->available_date->format('M d, Y') }}</td>
                            <td class="px-4 py-3">
                                <span class="font-medium">{{ $listing->remaining_capacity }}/{{ $listing->total_capacity }}</span> {{ __('tons') }}
                                <div class="w-20 bg-gray-200 rounded-full h-1.5 mt-1"><div class="h-1.5 rounded-full bg-green-500" style="width: {{ $listing->capacityPercentage() }}%"></div></div>
                            </td>
                            <td class="px-4 py-3 font-semibold">₹{{ number_format($listing->price_per_ton, 0) }}</td>
                            <td class="px-4 py-3"><x-status-pill :status="$listing->status" /></td>
                            <td class="px-4 py-3 space-x-2">
                                <a href="{{ route('transporter.listings.show', $listing) }}" class="text-green-600 text-xs font-medium">{{ __('View') }}</a>
                                <a href="{{ route('transporter.listings.requests', $listing) }}" class="text-amber-600 text-xs font-medium">{{ __('Find Loads') }}</a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">{{ __('No listings yet.') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t">{{ $listings->links() }}</div>
        </div>
    </div>
</x-app-layout>
