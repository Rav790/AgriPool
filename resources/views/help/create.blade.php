<x-app-layout>
    <x-slot name="title">{{ __('New Support Ticket') }}</x-slot>

    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">📝 {{ __('Create Support Ticket') }}</h1>

        <form method="POST" action="{{ route('help.store') }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Subject') }} *</label>
                <input type="text" name="subject" value="{{ old('subject') }}" required placeholder="{{ __('Brief description of your issue') }}"
                       class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">
                @error('subject') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Category') }} *</label>
                    <select name="category" required class="w-full rounded-lg border-gray-300 focus:ring-green-500">
                        <option value="account">👤 {{ __('Account') }}</option>
                        <option value="payment">💰 {{ __('Payment') }}</option>
                        <option value="booking">📦 {{ __('Booking') }}</option>
                        <option value="technical">🔧 {{ __('Technical') }}</option>
                        <option value="other">📋 {{ __('Other') }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Priority') }} *</label>
                    <select name="priority" required class="w-full rounded-lg border-gray-300 focus:ring-green-500">
                        <option value="low">🟢 {{ __('Low') }}</option>
                        <option value="normal" selected>🟡 {{ __('Normal') }}</option>
                        <option value="high">🟠 {{ __('High') }}</option>
                        <option value="urgent">🔴 {{ __('Urgent') }}</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Description') }} *</label>
                <textarea name="description" rows="5" required placeholder="{{ __('Describe your issue in detail...') }}"
                          class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700 transition">{{ __('Submit Ticket') }}</button>
                <a href="{{ route('help.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition">{{ __('Cancel') }}</a>
            </div>
        </form>
    </div>
</x-app-layout>
