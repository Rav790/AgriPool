<x-app-layout>
    <x-slot name="title">{{ __('Dispute') }} #{{ $dispute->id }}</x-slot>
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-xl font-bold">{{ ucfirst(str_replace('_', ' ', $dispute->category)) }}</h1>
                    <p class="text-sm text-gray-500">{{ __('Booking') }} #{{ $dispute->booking_id }} · {{ $dispute->created_at->format('M d, Y') }}</p>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $dispute->status === 'resolved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">{{ ucfirst($dispute->status) }}</span>
            </div>
            <div class="mt-4 bg-gray-50 rounded-lg p-4"><p class="whitespace-pre-wrap">{{ $dispute->description }}</p></div>
            @if($dispute->resolution_notes)
                <div class="mt-4 bg-green-50 border border-green-200 rounded-lg p-4">
                    <p class="text-sm font-semibold text-green-800 mb-1">{{ __('Resolution') }}</p>
                    <p>{{ $dispute->resolution_notes }}</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
