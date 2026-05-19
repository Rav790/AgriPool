<x-app-layout>
    <x-slot name="title">{{ __('Record Price') }}</x-slot>
    <x-slot name="header"><h1 class="text-2xl font-bold text-gray-900">{{ __('Record Market Price') }}</h1></x-slot>
    <div class="max-w-2xl mx-auto">
        <form action="{{ route('agent.prices.store') }}" method="POST" class="bg-white rounded-xl border p-6 space-y-6">
            @csrf
            <div>
                <label for="market_id" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Market') }} *</label>
                <select name="market_id" id="market_id" required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                    <option value="">{{ __('Select market') }}</option>
                    @foreach($markets as $market)<option value="{{ $market->id }}">{{ $market->name }} ({{ $market->city }})</option>@endforeach
                </select>
                @error('market_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="crop_type" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Crop') }} *</label>
                <select name="crop_type" id="crop_type" required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                    @foreach($crops as $crop)<option value="{{ $crop }}">{{ __($crop) }}</option>@endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="price_per_quintal" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Price per Quintal (₹)') }} *</label>
                    <input type="number" name="price_per_quintal" id="price_per_quintal" step="0.01" min="1" required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                    @error('price_per_quintal') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="recorded_date" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Date') }} *</label>
                    <input type="date" name="recorded_date" id="recorded_date" value="{{ today()->format('Y-m-d') }}" required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                </div>
            </div>
            <div class="flex justify-end space-x-3 pt-4 border-t">
                <a href="{{ route('agent.prices.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">{{ __('Cancel') }}</a>
                <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">{{ __('Record Price') }}</button>
            </div>
        </form>
    </div>
</x-app-layout>
