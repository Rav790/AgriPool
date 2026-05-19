<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Contact Us') }} — AgriPool</title>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <nav class="bg-white/90 backdrop-blur-sm border-b border-gray-100 fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">
            <a href="/" class="flex items-center space-x-2">
                <div class="w-9 h-9 bg-green-600 rounded-lg flex items-center justify-center"><svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                <span class="text-xl font-bold text-green-700">AgriPool</span>
            </a>
            <a href="{{ route('register') }}" class="bg-green-600 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-green-700">{{ __('Get Started') }}</a>
        </div>
    </nav>

    <div class="pt-28 pb-20 max-w-5xl mx-auto px-4">
        <div class="text-center mb-12">
            <h1 class="text-3xl font-bold text-gray-900">📞 {{ __('Contact Us') }}</h1>
            <p class="text-gray-500 mt-2">{{ __('We\'d love to hear from you. Reach out to us through any channel.') }}</p>
        </div>

        <div class="grid md:grid-cols-2 gap-8">
            <!-- Contact Info -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl p-6 border border-gray-200">
                    <h3 class="font-bold text-gray-900 mb-4">{{ __('Get in Touch') }}</h3>
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <span class="text-xl">📧</span>
                            <div><p class="font-medium text-gray-900">{{ __('Email') }}</p><p class="text-sm text-gray-500">support@agripool.in</p></div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="text-xl">📱</span>
                            <div><p class="font-medium text-gray-900">{{ __('Phone') }}</p><p class="text-sm text-gray-500">+91 1800-XXX-XXXX (Toll Free)</p></div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="text-xl">💬</span>
                            <div><p class="font-medium text-gray-900">{{ __('WhatsApp') }}</p><p class="text-sm text-gray-500">+91 98XXX XXXXX</p></div>
                        </div>
                        <div class="flex items-start gap-3">
                            <span class="text-xl">📍</span>
                            <div><p class="font-medium text-gray-900">{{ __('Office') }}</p><p class="text-sm text-gray-500">AgriPool Technologies Pvt. Ltd.<br>Sector 62, Noida, UP 201301</p></div>
                        </div>
                    </div>
                </div>
                <div class="bg-green-50 rounded-2xl p-6 border border-green-200">
                    <h3 class="font-bold text-green-800 mb-2">🕐 {{ __('Business Hours') }}</h3>
                    <p class="text-sm text-green-700">{{ __('Monday - Saturday: 8:00 AM - 8:00 PM IST') }}</p>
                    <p class="text-sm text-green-700">{{ __('Sunday: 9:00 AM - 5:00 PM IST') }}</p>
                    <p class="text-sm text-green-600 mt-2">{{ __('Support available in Hindi & English') }}</p>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="bg-white rounded-2xl p-6 border border-gray-200">
                <h3 class="font-bold text-gray-900 mb-4">{{ __('Send us a Message') }}</h3>
                <form class="space-y-4" x-data="{ sent: false }" @submit.prevent="sent = true">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Your Name') }}</label>
                        <input type="text" required class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Email') }}</label>
                        <input type="email" required class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Subject') }}</label>
                        <select class="w-full rounded-lg border-gray-300 focus:ring-green-500">
                            <option>{{ __('General Inquiry') }}</option>
                            <option>{{ __('Partnership') }}</option>
                            <option>{{ __('Technical Support') }}</option>
                            <option>{{ __('Media/Press') }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Message') }}</label>
                        <textarea rows="4" required class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500"></textarea>
                    </div>
                    <button type="submit" x-show="!sent" class="w-full bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700 transition">{{ __('Send Message') }}</button>
                    <div x-show="sent" class="bg-green-50 border border-green-200 rounded-lg p-4 text-center text-green-800 font-medium">
                        ✅ {{ __('Thank you! We\'ll get back to you within 24 hours.') }}
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('partials.footer')
</body>
</html>
