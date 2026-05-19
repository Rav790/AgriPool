<x-app-layout>
    <x-slot name="title">{{ __('Tracking') }}</x-slot>
    <x-slot name="header"><h1 class="text-2xl font-bold text-gray-900">{{ __('Track Shipment — Booking') }} #{{ $booking->id }}</h1></x-slot>
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Status Bar -->
        <div class="bg-white rounded-xl border p-6">
            <div class="flex items-center justify-between mb-6">
                @php $steps = ['pending', 'confirmed', 'picked_up', 'in_transit', 'delivered']; $currentIdx = array_search($booking->status, $steps); @endphp
                @foreach($steps as $i => $step)
                <div class="flex-1 text-center">
                    <div class="w-10 h-10 mx-auto rounded-full flex items-center justify-center {{ $i <= ($currentIdx ?: 0) ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-400' }}">
                        @if($i <= ($currentIdx ?: 0)) ✓ @else {{ $i + 1 }} @endif
                    </div>
                    <p class="text-xs mt-2 font-medium {{ $i <= ($currentIdx ?: 0) ? 'text-green-700' : 'text-gray-400' }}">{{ __(ucwords(str_replace('_', ' ', $step))) }}</p>
                </div>
                @if($i < count($steps) - 1)
                <div class="flex-1 h-1 {{ $i < ($currentIdx ?: 0) ? 'bg-green-500' : 'bg-gray-200' }} rounded mx-2 mt-[-20px]"></div>
                @endif
                @endforeach
            </div>
        </div>

        <!-- Timeline -->
        <div class="bg-white rounded-xl border p-6">
            <h2 class="text-lg font-semibold mb-4">{{ __('Tracking History') }}</h2>
            @forelse($updates as $update)
            <div class="flex items-start space-x-4 pb-4 border-l-2 {{ $update->status === 'delivered' ? 'border-green-500' : 'border-amber-300' }} ml-4 pl-4 relative">
                <div class="absolute -left-2.5 w-5 h-5 rounded-full {{ $update->status === 'delivered' ? 'bg-green-500' : 'bg-amber-400' }}"></div>
                <div>
                    <p class="font-medium text-sm">{{ __(ucwords(str_replace('_', ' ', $update->status))) }}</p>
                    @if($update->status_note)<p class="text-sm text-gray-500">{{ $update->status_note }}</p>@endif
                    @if($update->lat)<p class="text-xs text-gray-400">📍 {{ $update->lat }}, {{ $update->lng }}</p>@endif
                    <p class="text-xs text-gray-400 mt-1">{{ $update->created_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
            @empty
            <p class="text-gray-400 text-center py-8">{{ __('No tracking updates yet.') }}</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
