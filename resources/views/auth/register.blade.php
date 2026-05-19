<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-900">{{ __('Join AgriPool') }}</h2>
        <p class="text-sm text-gray-500 mt-1">{{ __('Create your account to get started') }}</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Role Selection -->
        <div x-data="{ role: '{{ old('role', 'farmer') }}' }">
            <label class="block text-sm font-medium text-gray-700 mb-3">{{ __('I am a...') }} <span class="text-red-500">*</span></label>
            <div class="grid grid-cols-3 gap-3">
                <label @click="role = 'farmer'" :class="role === 'farmer' ? 'border-green-500 bg-green-50 ring-2 ring-green-500 shadow-sm' : 'border-gray-200 bg-white hover:border-green-300'" class="cursor-pointer rounded-xl border-2 p-3 text-center transition-all">
                    <input type="radio" name="role" value="farmer" x-model="role" class="sr-only">
                    <div class="text-2xl mb-1">🌾</div>
                    <p class="text-sm font-semibold" :class="role === 'farmer' ? 'text-green-700' : 'text-gray-700'">{{ __('Farmer') }}</p>
                </label>
                <label @click="role = 'transporter'" :class="role === 'transporter' ? 'border-green-500 bg-green-50 ring-2 ring-green-500 shadow-sm' : 'border-gray-200 bg-white hover:border-green-300'" class="cursor-pointer rounded-xl border-2 p-3 text-center transition-all">
                    <input type="radio" name="role" value="transporter" x-model="role" class="sr-only">
                    <div class="text-2xl mb-1">🚛</div>
                    <p class="text-sm font-semibold" :class="role === 'transporter' ? 'text-green-700' : 'text-gray-700'">{{ __('Transporter') }}</p>
                </label>
                <label @click="role = 'agent'" :class="role === 'agent' ? 'border-green-500 bg-green-50 ring-2 ring-green-500 shadow-sm' : 'border-gray-200 bg-white hover:border-green-300'" class="cursor-pointer rounded-xl border-2 p-3 text-center transition-all">
                    <input type="radio" name="role" value="agent" x-model="role" class="sr-only">
                    <div class="text-2xl mb-1">🏪</div>
                    <p class="text-sm font-semibold" :class="role === 'agent' ? 'text-green-700' : 'text-gray-700'">{{ __('Agent') }}</p>
                </label>
            </div>
            @error('role') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Full Name') }}</label>
            <input id="name" class="block w-full rounded-xl border-gray-300 focus:border-green-500 focus:ring-green-500 shadow-sm px-4 py-3 bg-gray-50" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="John Doe" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Phone -->
        <div>
            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Phone Number') }}</label>
            <input id="phone" class="block w-full rounded-xl border-gray-300 focus:border-green-500 focus:ring-green-500 shadow-sm px-4 py-3 bg-gray-50" type="tel" name="phone" :value="old('phone')" autocomplete="tel" placeholder="e.g. 9876543210" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Email') }}</label>
            <input id="email" class="block w-full rounded-xl border-gray-300 focus:border-green-500 focus:ring-green-500 shadow-sm px-4 py-3 bg-gray-50" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="name@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="grid grid-cols-2 gap-4">
            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Password') }}</label>
                <input id="password" class="block w-full rounded-xl border-gray-300 focus:border-green-500 focus:ring-green-500 shadow-sm px-4 py-3 bg-gray-50" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Confirm') }}</label>
                <input id="password_confirmation" class="block w-full rounded-xl border-gray-300 focus:border-green-500 focus:ring-green-500 shadow-sm px-4 py-3 bg-gray-50" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                {{ __('Create Account') }}
            </button>
        </div>
        
        <div class="text-center mt-6">
            <p class="text-sm text-gray-600">
                {{ __('Already have an account?') }}
                <a href="{{ route('login') }}" class="font-medium text-green-600 hover:text-green-500 hover:underline">
                    {{ __('Sign in instead') }}
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
