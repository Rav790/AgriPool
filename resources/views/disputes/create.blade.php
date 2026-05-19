<x-app-layout>
    <x-slot name="title">{{ __('Raise Dispute') }}</x-slot>

    <div class="max-w-2xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">⚠️ {{ __('Raise a Dispute') }}</h1>
        <p class="text-gray-500 mb-4">{{ __('Booking') }} #{{ $booking->id }} — {{ $booking->transportRequest->crop_type ?? 'N/A' }}, {{ $booking->allocated_tons }} {{ __('tons') }}</p>

        <form method="POST" action="{{ route('disputes.store', $booking) }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Issue Category') }} *</label>
                <select name="category" required class="w-full rounded-lg border-gray-300 focus:ring-green-500">
                    <option value="damaged_goods">📦 {{ __('Damaged Goods') }}</option>
                    <option value="late_delivery">⏰ {{ __('Late Delivery') }}</option>
                    <option value="payment_issue">💰 {{ __('Payment Issue') }}</option>
                    <option value="wrong_delivery">❌ {{ __('Wrong Delivery') }}</option>
                    <option value="other">📋 {{ __('Other') }}</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Priority') }} *</label>
                <select name="priority" required class="w-full rounded-lg border-gray-300 focus:ring-green-500">
                    <option value="low">🟢 {{ __('Low') }}</option>
                    <option value="medium" selected>🟡 {{ __('Medium') }}</option>
                    <option value="high">🟠 {{ __('High') }}</option>
                    <option value="critical">🔴 {{ __('Critical') }}</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Describe the issue') }} *</label>
                <textarea name="description" rows="5" required placeholder="{{ __('Explain what happened in detail...') }}" class="w-full rounded-lg border-gray-300 focus:ring-green-500">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 text-sm text-yellow-800">
                ⚡ {{ __('Our team will review your dispute within 24 hours. The escrowed payment will be held until resolution.') }}
            </div>
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-red-600 text-white py-3 rounded-lg font-semibold hover:bg-red-700 transition">{{ __('Submit Dispute') }}</button>
                <a href="{{ url()->previous() }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">{{ __('Cancel') }}</a>
            </div>
        </form>
    </div>
</x-app-layout>
