<!-- Onboarding Wizard — Shows only for new users (< 24h old) -->
@if(auth()->check() && auth()->user()->created_at->diffInHours(now()) < 24 && !session('onboarding_dismissed'))
<div x-data="{ show: true, step: 1, totalSteps: 4 }" x-show="show" x-transition class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden" @click.away="">

        <!-- Progress bar -->
        <div class="h-1 bg-gray-200">
            <div class="h-1 bg-green-500 transition-all duration-500" :style="'width: ' + (step / totalSteps * 100) + '%'"></div>
        </div>

        <div class="p-8">
            <!-- Step 1: Welcome -->
            <div x-show="step === 1" x-transition>
                <div class="text-center">
                    <div class="text-6xl mb-4">🎉</div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ __('Welcome to AgriPool!') }}</h2>
                    <p class="text-gray-500 mt-3">{{ __('You\'re now part of India\'s largest agricultural transport sharing platform. Let\'s get you started in 30 seconds.') }}</p>
                </div>
            </div>

            <!-- Step 2: Complete Profile -->
            <div x-show="step === 2" x-transition>
                <div class="text-center">
                    <div class="text-6xl mb-4">👤</div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ __('Complete Your Profile') }}</h2>
                    <p class="text-gray-500 mt-3">{{ __('Add your phone number and location for better matching with nearby transport.') }}</p>
                    <div class="mt-6 bg-green-50 rounded-xl p-4 text-left">
                        <div class="flex items-center gap-3 mb-2">
                            <span class="w-6 h-6 bg-green-500 rounded-full text-white flex items-center justify-center text-xs">✓</span>
                            <span class="text-sm text-gray-700">{{ __('Name & Email — Done!') }}</span>
                        </div>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="w-6 h-6 bg-gray-200 rounded-full text-gray-500 flex items-center justify-center text-xs">2</span>
                            <span class="text-sm text-gray-700">{{ __('Add phone number') }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="w-6 h-6 bg-gray-200 rounded-full text-gray-500 flex items-center justify-center text-xs">3</span>
                            <span class="text-sm text-gray-700">{{ __('Complete KYC for trust badge') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3: Key Features -->
            <div x-show="step === 3" x-transition>
                <div class="text-center">
                    <div class="text-6xl mb-4">🚀</div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ __('What You Can Do') }}</h2>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-3">
                    @if(auth()->user()->role === 'farmer')
                        <div class="bg-gray-50 rounded-xl p-3 text-center"><p class="text-2xl">📝</p><p class="text-xs font-medium mt-1">{{ __('Post produce requests') }}</p></div>
                        <div class="bg-gray-50 rounded-xl p-3 text-center"><p class="text-2xl">🚛</p><p class="text-xs font-medium mt-1">{{ __('Find shared transport') }}</p></div>
                        <div class="bg-gray-50 rounded-xl p-3 text-center"><p class="text-2xl">📊</p><p class="text-xs font-medium mt-1">{{ __('Track market prices') }}</p></div>
                        <div class="bg-gray-50 rounded-xl p-3 text-center"><p class="text-2xl">🤝</p><p class="text-xs font-medium mt-1">{{ __('Join cooperatives') }}</p></div>
                    @elseif(auth()->user()->role === 'transporter')
                        <div class="bg-gray-50 rounded-xl p-3 text-center"><p class="text-2xl">🚛</p><p class="text-xs font-medium mt-1">{{ __('List your vehicle') }}</p></div>
                        <div class="bg-gray-50 rounded-xl p-3 text-center"><p class="text-2xl">📦</p><p class="text-xs font-medium mt-1">{{ __('Find loads nearby') }}</p></div>
                        <div class="bg-gray-50 rounded-xl p-3 text-center"><p class="text-2xl">💰</p><p class="text-xs font-medium mt-1">{{ __('Earn with every trip') }}</p></div>
                        <div class="bg-gray-50 rounded-xl p-3 text-center"><p class="text-2xl">⭐</p><p class="text-xs font-medium mt-1">{{ __('Build your rating') }}</p></div>
                    @else
                        <div class="bg-gray-50 rounded-xl p-3 text-center"><p class="text-2xl">📈</p><p class="text-xs font-medium mt-1">{{ __('Update market prices') }}</p></div>
                        <div class="bg-gray-50 rounded-xl p-3 text-center"><p class="text-2xl">✅</p><p class="text-xs font-medium mt-1">{{ __('Confirm deliveries') }}</p></div>
                        <div class="bg-gray-50 rounded-xl p-3 text-center"><p class="text-2xl">🏪</p><p class="text-xs font-medium mt-1">{{ __('Manage your market') }}</p></div>
                        <div class="bg-gray-50 rounded-xl p-3 text-center"><p class="text-2xl">🎧</p><p class="text-xs font-medium mt-1">{{ __('Get support') }}</p></div>
                    @endif
                </div>
            </div>

            <!-- Step 4: Ready! -->
            <div x-show="step === 4" x-transition>
                <div class="text-center">
                    <div class="text-6xl mb-4">✅</div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ __('You\'re All Set!') }}</h2>
                    <p class="text-gray-500 mt-3">{{ __('Start exploring your dashboard. Need help anytime? Click the Help button in the sidebar.') }}</p>
                    <div class="mt-6 bg-amber-50 border border-amber-200 rounded-xl p-4 text-sm text-amber-800">
                        💡 <strong>{{ __('Pro Tip:') }}</strong> {{ __('Complete your KYC verification to increase your Trust Score and get priority matching!') }}
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="flex justify-between mt-8">
                <button x-show="step > 1" @click="step--" class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-800 transition">← {{ __('Back') }}</button>
                <div x-show="step === 1"></div>
                <button x-show="step < totalSteps" @click="step++" class="px-6 py-2.5 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition ml-auto">{{ __('Next') }} →</button>
                <button x-show="step === totalSteps" @click="show = false; fetch('/onboarding/dismiss', {method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}})" class="px-6 py-2.5 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition ml-auto">🚀 {{ __('Go to Dashboard') }}</button>
            </div>
        </div>

        <!-- Skip button -->
        <div class="px-8 pb-4 text-center">
            <button @click="show = false; fetch('/onboarding/dismiss', {method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}})" class="text-xs text-gray-400 hover:text-gray-600">{{ __('Skip tour') }}</button>
        </div>
    </div>
</div>
@endif
