<x-app-layout>
    <x-slot name="title">{{ __('Deliveries') }}</x-slot>
    <x-slot name="header"><h1 class="text-2xl font-bold text-gray-900">{{ __('Pending Deliveries') }}</h1></x-slot>
    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-xl border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50"><tr>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">#</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Farmer') }}</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Transporter') }}</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Market') }}</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Status') }}</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Action') }}</th>
                    </tr></thead>
                    <tbody class="divide-y">
                        @forelse($deliveries as $d)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">#{{ $d->id }}</td>
                            <td class="px-4 py-3 font-medium">{{ $d->farmer->name ?? '—' }}</td>
                            <td class="px-4 py-3">{{ $d->transporter->name ?? '—' }}</td>
                            <td class="px-4 py-3">{{ $d->transportRequest->destinationMarket->name ?? '—' }}</td>
                            <td class="px-4 py-3"><x-status-pill :status="$d->status" /></td>
                            <td class="px-4 py-3">
                                <form action="{{ route('agent.deliveries.confirm', $d) }}" method="POST">@csrf
                                    <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded text-xs font-medium hover:bg-green-700">{{ __('Confirm Delivery') }}</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">{{ __('No pending deliveries.') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t">{{ $deliveries->links() }}</div>
        </div>
    </div>
</x-app-layout>
