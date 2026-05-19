<x-app-layout>
    <x-slot name="title">{{ __('All Bookings') }}</x-slot>
    <x-slot name="header"><h1 class="text-2xl font-bold text-gray-900">{{ __('All Bookings') }}</h1></x-slot>
    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-xl border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50"><tr>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">#</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Farmer') }}</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Transporter') }}</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Tons') }}</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Price') }}</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Status') }}</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Actions') }}</th>
                    </tr></thead>
                    <tbody class="divide-y">
                        @foreach($bookings as $b)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">#{{ $b->id }}</td>
                            <td class="px-4 py-3">{{ $b->farmer->name ?? '—' }}</td>
                            <td class="px-4 py-3">{{ $b->transporter->name ?? '—' }}</td>
                            <td class="px-4 py-3">{{ $b->allocated_tons }}</td>
                            <td class="px-4 py-3 font-semibold">₹{{ number_format($b->total_price, 0) }}</td>
                            <td class="px-4 py-3"><x-status-pill :status="$b->status" /></td>
                            <td class="px-4 py-3">
                                @if($b->status !== 'disputed')
                                <form action="{{ route('admin.bookings.flag', $b) }}" method="POST" class="inline">@csrf<button class="text-red-600 text-xs font-medium">🚩 {{ __('Flag') }}</button></form>
                                @else
                                <span class="text-xs text-red-500 font-medium">🚩 {{ __('Disputed') }}</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t">{{ $bookings->links() }}</div>
        </div>
    </div>
</x-app-layout>
