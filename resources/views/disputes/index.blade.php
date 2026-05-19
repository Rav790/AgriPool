<x-app-layout>
    <x-slot name="title">{{ __('My Disputes') }}</x-slot>
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">⚖️ {{ __('My Disputes') }}</h1>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="divide-y divide-gray-100">
                @forelse($disputes as $d)
                    <div class="flex items-center px-6 py-4 hover:bg-gray-50">
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">{{ __('Booking') }} #{{ $d->booking_id }} — {{ ucfirst(str_replace('_', ' ', $d->category)) }}</p>
                            <p class="text-sm text-gray-500 mt-0.5 line-clamp-1">{{ $d->description }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $d->status === 'open' ? 'bg-yellow-100 text-yellow-800' : ($d->status === 'resolved' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800') }}">{{ ucfirst($d->status) }}</span>
                    </div>
                @empty
                    <div class="text-center py-12 text-gray-400">
                        <p class="text-4xl mb-2">✅</p>
                        <p>{{ __('No disputes. Everything is running smoothly!') }}</p>
                    </div>
                @endforelse
            </div>
            @if($disputes->hasPages()) <div class="px-6 py-3 border-t">{{ $disputes->links() }}</div> @endif
        </div>
    </div>
</x-app-layout>
