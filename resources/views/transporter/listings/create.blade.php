<x-app-layout>
    <x-slot name="title">{{ __('Create Listing') }}</x-slot>
    <x-slot name="header"><h1 class="text-2xl font-bold text-gray-900">{{ __('Create Transport Listing') }}</h1></x-slot>
    <div class="max-w-3xl mx-auto">
        <form action="{{ route('transporter.listings.store') }}" method="POST" class="bg-white rounded-xl border border-gray-200 p-6 space-y-6">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="route_from" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Route From') }} *</label>
                    <input type="text" name="route_from" id="route_from" value="{{ old('route_from') }}" required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500" placeholder="e.g. Najafgarh, Delhi">
                    @error('route_from') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="route_to" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Route To') }} *</label>
                    <input type="text" name="route_to" id="route_to" value="{{ old('route_to') }}" required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500" placeholder="e.g. Azadpur Mandi, Delhi">
                    @error('route_to') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="available_date" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Available Date') }} *</label>
                    <input type="date" name="available_date" id="available_date" value="{{ old('available_date', today()->format('Y-m-d')) }}" min="{{ today()->format('Y-m-d') }}" required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                    @error('available_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="total_capacity" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Total Capacity (Tons)') }} *</label>
                    <input type="number" name="total_capacity" id="total_capacity" step="any" min="0.1" max="1000000" value="{{ old('total_capacity', auth()->user()->transporterProfile->capacity_tons ?? '') }}" required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                    @error('total_capacity') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="price_per_ton" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Price per Ton (₹)') }} *</label>
                    <input type="number" name="price_per_ton" id="price_per_ton" step="any" min="0" value="{{ old('price_per_ton') }}" required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500" placeholder="e.g. 1500">
                    @error('price_per_ton') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
            <input type="hidden" name="route_from_lat" value="">
            <input type="hidden" name="route_from_lng" value="">
            <input type="hidden" name="route_to_lat" value="">
            <input type="hidden" name="route_to_lng" value="">
            <div class="flex justify-end space-x-3 pt-4 border-t">
                <a href="{{ route('transporter.listings.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">{{ __('Cancel') }}</a>
                <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">{{ __('Create Listing') }}</button>
            </div>
        </form>
    </div>
</x-app-layout>
