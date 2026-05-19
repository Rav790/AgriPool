<x-app-layout>
    <x-slot name="title">{{ __('Create Cooperative') }}</x-slot>

    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">🤝 {{ __('Create Cooperative Group') }}</h1>

        <form method="POST" action="{{ route('farmer.cooperatives.store') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Group Name') }} *</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="{{ __('e.g. Sanganer Farmers Union') }}" class="w-full rounded-lg border-gray-300 focus:ring-green-500">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Region / Village') }} *</label>
                <input type="text" name="region" value="{{ old('region') }}" required placeholder="{{ __('e.g. Sanganer, Jaipur') }}" class="w-full rounded-lg border-gray-300 focus:ring-green-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Description') }}</label>
                <textarea name="description" rows="3" placeholder="{{ __('What crops does your group focus on?') }}" class="w-full rounded-lg border-gray-300 focus:ring-green-500">{{ old('description') }}</textarea>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700 transition">{{ __('Create Group') }}</button>
                <a href="{{ route('farmer.cooperatives.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">{{ __('Cancel') }}</a>
            </div>
        </form>
    </div>
</x-app-layout>
