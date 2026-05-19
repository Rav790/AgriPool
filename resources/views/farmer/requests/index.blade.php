<x-app-layout>
    <x-slot name="title">{{ __('My Transport Requests') }}</x-slot>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('My Transport Requests') }}</h1>
            <a href="{{ route('farmer.requests.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                {{ __('New Request') }}
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Crop') }}</th>
                            <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Quantity') }}</th>
                            <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Destination') }}</th>
                            <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Required Date') }}</th>
                            <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Status') }}</th>
                            <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($requests as $request)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <div class="flex items-center">
                                    <span class="font-medium text-gray-900">{{ $request->crop_type }}</span>
                                    @if($request->is_perishable)
                                        <span class="ml-2 text-xs bg-red-50 text-red-600 px-1.5 py-0.5 rounded">🍅 {{ __('Perishable') }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $request->quantity_tons }} {{ __('tons') }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $request->destinationMarket->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $request->required_date->format('M d, Y') }}</td>
                            <td class="px-4 py-3"><x-status-pill :status="$request->status" /></td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('farmer.requests.show', $request) }}" class="text-green-600 hover:text-green-800 text-xs font-medium">{{ __('View') }}</a>
                                    @if($request->status === 'pending')
                                        <a href="{{ route('farmer.requests.matches', $request) }}" class="text-amber-600 hover:text-amber-800 text-xs font-medium">{{ __('Find Transport') }}</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-400">{{ __('No transport requests yet.') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-100">
                {{ $requests->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
