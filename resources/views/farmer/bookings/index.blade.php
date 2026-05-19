<x-app-layout>
    <x-slot name="title">{{ __('My Bookings') }}</x-slot>
    <x-slot name="header"><h1 class="text-2xl font-bold text-gray-900">{{ __('My Bookings') }}</h1></x-slot>

    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left px-4 py-3 font-medium text-gray-500">#</th>
                            <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Crop') }}</th>
                            <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Transporter') }}</th>
                            <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Tons') }}</th>
                            <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Price') }}</th>
                            <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Status') }}</th>
                            <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Payment') }}</th>
                            <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($bookings as $booking)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-500">#{{ $booking->id }}</td>
                            <td class="px-4 py-3 font-medium">{{ $booking->transportRequest->crop_type ?? '—' }}</td>
                            <td class="px-4 py-3">{{ $booking->transportListing->transporter->name ?? '—' }}</td>
                            <td class="px-4 py-3">{{ $booking->allocated_tons }}</td>
                            <td class="px-4 py-3 font-semibold">₹{{ number_format($booking->total_price, 0) }}</td>
                            <td class="px-4 py-3"><x-status-pill :status="$booking->status" /></td>
                            <td class="px-4 py-3"><x-status-pill :status="$booking->payment_status" /></td>
                            <td class="px-4 py-3">
                                <a href="{{ route('farmer.bookings.show', $booking) }}" class="text-green-600 hover:text-green-800 text-xs font-medium">{{ __('View') }}</a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="px-4 py-8 text-center text-gray-400">{{ __('No bookings yet.') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-100">{{ $bookings->links() }}</div>
        </div>
    </div>
</x-app-layout>
