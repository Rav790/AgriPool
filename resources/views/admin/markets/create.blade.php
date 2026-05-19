<x-app-layout>
    <x-slot name="title">{{ __('Add Market') }}</x-slot>
    <x-slot name="header"><h1 class="text-2xl font-bold text-gray-900">{{ __('Add New Market') }}</h1></x-slot>
    <div class="max-w-2xl mx-auto">
        <form action="{{ route('admin.markets.store') }}" method="POST" class="bg-white rounded-xl border p-6 space-y-6">
            @csrf
            <div><label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Name') }} *</label><input type="text" name="name" required class="w-full rounded-lg border-gray-300" value="{{ old('name') }}">@error('name') <p class="text-sm text-red-600">{{ $message }}</p> @enderror</div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium text-gray-700 mb-1">{{ __('City') }} *</label><input type="text" name="city" required class="w-full rounded-lg border-gray-300" value="{{ old('city') }}"></div>
                <div><label class="block text-sm font-medium text-gray-700 mb-1">{{ __('State') }} *</label><input type="text" name="state" required class="w-full rounded-lg border-gray-300" value="{{ old('state') }}"></div>
            </div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Location') }}</label><input type="text" name="location" class="w-full rounded-lg border-gray-300" value="{{ old('location') }}"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Type') }} *</label>
                <select name="type" required class="w-full rounded-lg border-gray-300">
                    <option value="mandi">{{ __('Mandi') }}</option>
                    <option value="wholesale">{{ __('Wholesale') }}</option>
                    <option value="retail">{{ __('Retail') }}</option>
                </select>
            </div>
            <div class="flex justify-end space-x-3 pt-4 border-t">
                <a href="{{ route('admin.markets.index') }}" class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-lg">{{ __('Cancel') }}</a>
                <button type="submit" class="px-6 py-2 text-sm text-white bg-green-600 rounded-lg hover:bg-green-700">{{ __('Create') }}</button>
            </div>
        </form>
    </div>
</x-app-layout>
