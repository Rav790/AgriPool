<x-app-layout>
    <x-slot name="title">{{ __('Create Transport Request') }}</x-slot>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-900">{{ __('Create Transport Request') }}</h1>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <form action="{{ route('farmer.requests.store') }}" method="POST" class="bg-white rounded-xl border border-gray-200 p-6 space-y-6">
            @csrf

            <!-- Crop Selection -->
            <div>
                <label for="crop_type" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Crop Type') }} *</label>
                <select name="crop_type" id="crop_type" required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500" x-data x-on:change="$refs.perishable.checked = ['Tomato','Onion','Potato'].includes($event.target.value)">
                    <option value="">{{ __('Select crop') }}</option>
                    @foreach($crops as $crop)
                        <option value="{{ $crop }}" {{ old('crop_type') === $crop ? 'selected' : '' }}>{{ __($crop) }}</option>
                    @endforeach
                </select>
                @error('crop_type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Quantity -->
                <div>
                    <label for="quantity_tons" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Quantity (Tons)') }} *</label>
                    <input type="number" name="quantity_tons" id="quantity_tons" step="0.1" min="0.1" max="50" value="{{ old('quantity_tons') }}" required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500" placeholder="e.g. 2.5">
                    @error('quantity_tons') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Packaging -->
                <div>
                    <label for="packaging_type" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Packaging Type') }} *</label>
                    <select name="packaging_type" id="packaging_type" required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                        @foreach($packaging as $type)
                            <option value="{{ $type }}" {{ old('packaging_type') === $type ? 'selected' : '' }}>{{ __(ucfirst($type)) }}</option>
                        @endforeach
                    </select>
                    @error('packaging_type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Pickup Address -->
            <div>
                <label for="pickup_address" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Pickup Address') }} *</label>
                <input type="text" name="pickup_address" id="pickup_address" value="{{ old('pickup_address', auth()->user()->farmerProfile->farm_address ?? '') }}" required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500" placeholder="{{ __('Enter full pickup address') }}">
                <input type="hidden" name="pickup_lat" value="{{ auth()->user()->farmerProfile->farm_location_lat ?? '' }}">
                <input type="hidden" name="pickup_lng" value="{{ auth()->user()->farmerProfile->farm_location_lng ?? '' }}">
                @error('pickup_address') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <!-- Destination Market -->
            <div>
                <label for="destination_market_id" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Destination Market') }} *</label>
                <select name="destination_market_id" id="destination_market_id" required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                    <option value="">{{ __('Select market') }}</option>
                    @foreach($markets as $market)
                        <option value="{{ $market->id }}" {{ old('destination_market_id') == $market->id ? 'selected' : '' }}>{{ $market->name }} ({{ $market->city }}, {{ $market->state }})</option>
                    @endforeach
                </select>
                @error('destination_market_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Required Date -->
                <div>
                    <label for="required_date" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Required Date') }} *</label>
                    <input type="date" name="required_date" id="required_date" value="{{ old('required_date', today()->format('Y-m-d')) }}" min="{{ today()->format('Y-m-d') }}" required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                    @error('required_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Perishable -->
                <div class="flex items-center pt-7">
                    <input type="hidden" name="is_perishable" value="0">
                    <input type="checkbox" name="is_perishable" id="is_perishable" value="1" x-ref="perishable" {{ old('is_perishable') ? 'checked' : '' }} class="rounded border-gray-300 text-green-600 focus:ring-green-500 w-5 h-5">
                    <label for="is_perishable" class="ml-2 text-sm text-gray-700">{{ __('Perishable produce (priority matching)') }}</label>
                </div>
            </div>

            <!-- Special Instructions -->
            <div>
                <label for="special_instructions" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Special Instructions') }}</label>
                <textarea name="special_instructions" id="special_instructions" rows="3" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500" placeholder="{{ __('e.g., Handle with care, keep dry...') }}">{{ old('special_instructions') }}</textarea>
                @error('special_instructions') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t">
                <a href="{{ route('farmer.requests.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">{{ __('Cancel') }}</a>
                <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition">{{ __('Create Request & Find Transport') }}</button>
            </div>
        </form>
    </div>
</x-app-layout>
