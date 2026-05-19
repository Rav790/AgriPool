<x-app-layout>
    <x-slot name="title">{{ __('KYC Verification') }}</x-slot>

    <div class="max-w-3xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">🪪 {{ __('KYC Verification') }}</h1>
        <p class="text-gray-500 mb-6">{{ __('Complete your identity verification to unlock all features and build trust.') }}</p>

        <!-- Status Banner -->
        <div class="mb-6 rounded-xl p-4 border {{ $user->kyc_status === 'verified' ? 'bg-green-50 border-green-200' : ($user->kyc_status === 'pending' ? 'bg-yellow-50 border-yellow-200' : ($user->kyc_status === 'rejected' ? 'bg-red-50 border-red-200' : 'bg-gray-50 border-gray-200')) }}">
            <div class="flex items-center gap-3">
                <span class="text-2xl">{{ $user->kyc_status === 'verified' ? '✅' : ($user->kyc_status === 'pending' ? '⏳' : ($user->kyc_status === 'rejected' ? '❌' : '📋')) }}</span>
                <div>
                    <p class="font-semibold {{ $user->kyc_status === 'verified' ? 'text-green-800' : ($user->kyc_status === 'pending' ? 'text-yellow-800' : 'text-gray-800') }}">
                        {{ __('KYC Status: :status', ['status' => ucfirst($user->kyc_status ?? 'not_submitted')]) }}
                    </p>
                    <p class="text-sm {{ $user->kyc_status === 'verified' ? 'text-green-600' : 'text-gray-500' }}">
                        @if($user->kyc_status === 'verified')
                            {{ __('Your identity is verified. You have full platform access.') }}
                        @elseif($user->kyc_status === 'pending')
                            {{ __('Your documents are under review. This typically takes 1-2 business days.') }}
                        @else
                            {{ __('Submit your documents below to get verified.') }}
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Trust Score -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="font-semibold text-gray-800 mb-3">🛡️ {{ __('Trust Score') }}</h3>
            <div class="flex items-center gap-4">
                <div class="relative w-20 h-20">
                    <svg class="w-20 h-20 transform -rotate-90" viewBox="0 0 36 36">
                        <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#e5e7eb" stroke-width="3"/>
                        <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="{{ $user->trustScore() >= 70 ? '#22c55e' : ($user->trustScore() >= 40 ? '#f59e0b' : '#ef4444') }}" stroke-width="3" stroke-dasharray="{{ $user->trustScore() }}, 100"/>
                    </svg>
                    <span class="absolute inset-0 flex items-center justify-center text-lg font-bold">{{ $user->trustScore() }}</span>
                </div>
                <div class="flex-1 space-y-2 text-sm">
                    <div class="flex items-center gap-2"><span>{{ $user->is_verified ? '✅' : '⬜' }}</span> {{ __('Account Verified') }} <span class="text-gray-400">(+20)</span></div>
                    <div class="flex items-center gap-2"><span>{{ $user->kyc_status === 'verified' ? '✅' : '⬜' }}</span> {{ __('KYC Complete') }} <span class="text-gray-400">(+30)</span></div>
                    <div class="flex items-center gap-2"><span>{{ $user->reviewsReceived()->count() > 0 ? '✅' : '⬜' }}</span> {{ __('Has Reviews') }} <span class="text-gray-400">(+25)</span></div>
                    <div class="flex items-center gap-2"><span>{{ $user->averageRating() >= 4 ? '✅' : '⬜' }}</span> {{ __('4+ Star Rating') }} <span class="text-gray-400">(+25)</span></div>
                </div>
            </div>
        </div>

        <!-- KYC Form -->
        @if($user->kyc_status !== 'verified')
        <form method="POST" action="{{ route('kyc.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-800 mb-4">📋 {{ __('Identity Documents') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Aadhaar Number') }} *</label>
                        <input type="text" name="aadhaar_number" value="{{ old('aadhaar_number', $user->aadhaar_number) }}" maxlength="12" placeholder="1234 5678 9012" class="w-full rounded-lg border-gray-300 focus:ring-green-500" required>
                        @error('aadhaar_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Aadhaar Document') }}</label>
                        <input type="file" name="aadhaar_document" accept=".jpg,.jpeg,.png,.pdf" class="w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-green-50 file:text-green-700 file:font-medium hover:file:bg-green-100">
                        @error('aadhaar_document') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('PAN Number') }}</label>
                        <input type="text" name="pan_number" value="{{ old('pan_number', $user->pan_number) }}" maxlength="20" placeholder="ABCDE1234F" class="w-full rounded-lg border-gray-300 focus:ring-green-500">
                        @error('pan_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('PAN Document') }}</label>
                        <input type="file" name="pan_document" accept=".jpg,.jpeg,.png,.pdf" class="w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-green-50 file:text-green-700 file:font-medium hover:file:bg-green-100">
                        @error('pan_document') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-800 mb-4">🏠 {{ __('Address Details') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Full Address') }} *</label>
                        <textarea name="address" rows="2" class="w-full rounded-lg border-gray-300 focus:ring-green-500" required>{{ old('address', $user->address) }}</textarea>
                        @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('City') }} *</label>
                        <input type="text" name="city" value="{{ old('city', $user->city) }}" class="w-full rounded-lg border-gray-300 focus:ring-green-500" required>
                        @error('city') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('State') }} *</label>
                        <input type="text" name="state" value="{{ old('state', $user->state) }}" class="w-full rounded-lg border-gray-300 focus:ring-green-500" required>
                        @error('state') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('PIN Code') }} *</label>
                        <input type="text" name="pincode" value="{{ old('pincode', $user->pincode) }}" maxlength="10" class="w-full rounded-lg border-gray-300 focus:ring-green-500" required>
                        @error('pincode') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-800 mb-4">🏦 {{ __('Bank Details') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Bank Name') }}</label>
                        <input type="text" name="bank_name" value="{{ old('bank_name', $user->bank_name) }}" class="w-full rounded-lg border-gray-300 focus:ring-green-500">
                        @error('bank_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Account Number') }}</label>
                        <input type="text" name="bank_account_number" value="{{ old('bank_account_number', $user->bank_account_number) }}" class="w-full rounded-lg border-gray-300 focus:ring-green-500">
                        @error('bank_account_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('IFSC Code') }}</label>
                        <input type="text" name="bank_ifsc" value="{{ old('bank_ifsc', $user->bank_ifsc) }}" class="w-full rounded-lg border-gray-300 focus:ring-green-500">
                        @error('bank_ifsc') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-xl font-semibold hover:bg-green-700 transition">
                {{ __('Submit KYC for Verification') }}
            </button>
        </form>
        @endif
    </div>
</x-app-layout>
