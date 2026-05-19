<x-app-layout>
    <x-slot name="title">{{ __('Edit Listing') }}</x-slot>
    <x-slot name="header"><h1 class="text-2xl font-bold text-gray-900">{{ __('Edit Listing') }} #{{ $listing->id }}</h1></x-slot>
    <div class="max-w-3xl mx-auto">
        <form action="{{ route('transporter.listings.update', $listing) }}" method="POST" class="bg-white rounded-xl border p-6 space-y-6">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Route From') }} *</label><input type="text" name="route_from" required class="w-full rounded-lg border-gray-300" value="{{ $listing->route_from }}"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Route To') }} *</label><input type="text" name="route_to" required class="w-full rounded-lg border-gray-300" value="{{ $listing->route_to }}"></div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Available Date') }} *</label><input type="date" name="available_date" required class="w-full rounded-lg border-gray-300" value="{{ $listing->available_date->format('Y-m-d') }}"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Price per Ton (₹)') }} *</label><input type="number" name="price_per_ton" step="any" min="0" required class="w-full rounded-lg border-gray-300" value="{{ $listing->price_per_ton }}"></div>
            </div>
            <div class="flex justify-end space-x-3 pt-4 border-t">
                <a href="{{ route('transporter.listings.show', $listing) }}" class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg">{{ __('Cancel') }}</a>
                <button type="submit" class="px-6 py-2 text-sm text-white bg-green-600 rounded-lg hover:bg-green-700">{{ __('Update Listing') }}</button>
            </div>
        </form>
    </div>
</x-app-layout>
