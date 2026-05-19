<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="AgriPool — Share transport, reduce costs, get your produce to market faster.">
    <title>AgriPool — {{ __('Transport Sharing for Agricultural Produce') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-white/90 backdrop-blur-sm border-b border-gray-100 fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">
            <a href="/" class="flex items-center space-x-2">
                <div class="w-9 h-9 bg-green-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <span class="text-xl font-bold text-green-700">AgriPool</span>
            </a>
            <div class="flex items-center space-x-4">
                <a href="{{ route('load-board') }}" class="text-sm font-medium text-gray-700 hover:text-green-600 transition hidden sm:inline">📦 {{ __('Load Board') }}</a>
                <a href="{{ route('fare-calculator') }}" class="text-sm font-medium text-gray-700 hover:text-green-600 transition hidden sm:inline">🧮 {{ __('Calculator') }}</a>
                <a href="{{ route('leaderboard') }}" class="text-sm font-medium text-gray-700 hover:text-green-600 transition hidden sm:inline">🏆 {{ __('Leaderboard') }}</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="bg-green-600 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition">{{ __('Dashboard') }}</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-green-600 transition">{{ __('Login') }}</a>
                    <a href="{{ route('register') }}" class="bg-green-600 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition">{{ __('Get Started') }}</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="pt-28 pb-20 bg-gradient-to-br from-green-600 via-green-700 to-emerald-800 text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="60" height="60" patternUnits="userSpaceOnUse"><path d="M 60 0 L 0 0 0 60" fill="none" stroke="white" stroke-width="0.5"/></pattern></defs><rect width="100%" height="100%" fill="url(#grid)"/></svg>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="max-w-3xl">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-tight">{{ __('Share Transport.') }}<br><span class="text-amber-300">{{ __('Reduce Costs.') }}</span><br>{{ __('Reach Markets.') }}</h1>
                <p class="mt-6 text-lg text-green-100 max-w-2xl">{{ __('AgriPool connects farmers with shared transport to move produce to markets efficiently. Pool loads, save money, and get real-time tracking — all from your phone.') }}</p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('register') }}" class="bg-white text-green-700 px-8 py-3 rounded-xl text-lg font-bold hover:bg-green-50 transition shadow-lg">{{ __('Start Sharing') }}</a>
                    <a href="#features" class="bg-white/10 text-white px-8 py-3 rounded-xl text-lg font-medium hover:bg-white/20 transition border border-white/30">{{ __('Learn More') }}</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section class="py-12 bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div><p class="text-3xl font-bold text-green-600">20+</p><p class="text-sm text-gray-500 mt-1">{{ __('Markets Across India') }}</p></div>
            <div><p class="text-3xl font-bold text-green-600">10+</p><p class="text-sm text-gray-500 mt-1">{{ __('Crops Supported') }}</p></div>
            <div><p class="text-3xl font-bold text-green-600">100%</p><p class="text-sm text-gray-500 mt-1">{{ __('Secure Payments') }}</p></div>
            <div><p class="text-3xl font-bold text-green-600">24/7</p><p class="text-sm text-gray-500 mt-1">{{ __('Live Tracking') }}</p></div>
        </div>
    </section>

    <!-- Features -->
    <section id="features" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900">{{ __('How AgriPool Works') }}</h2>
                <p class="mt-4 text-gray-500 max-w-xl mx-auto">{{ __('From farm to market in three simple steps') }}</p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                @php $features = [
                    ['icon' => '📝', 'title' => __('Post Your Produce'), 'desc' => __('Tell us what crop, how much, and where it needs to go. We\'ll find the best transport match.')],
                    ['icon' => '🚛', 'title' => __('Share the Ride'), 'desc' => __('Pool your load with nearby farmers heading to the same market. Save up to 60% on transport costs.')],
                    ['icon' => '📍', 'title' => __('Track & Deliver'), 'desc' => __('Real-time tracking, secure escrow payments, and delivery confirmation at the market gate.')],
                ]; @endphp
                @foreach($features as $f)
                <div class="bg-white rounded-2xl p-8 border border-gray-200 hover:shadow-lg transition-shadow text-center">
                    <div class="text-5xl mb-4">{{ $f['icon'] }}</div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $f['title'] }}</h3>
                    <p class="text-gray-500">{{ $f['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Roles -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900">{{ __('Built for Everyone in the Chain') }}</h2>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @php $roles = [
                    ['icon' => '🌾', 'title' => __('Farmers'), 'desc' => __('Post produce, find transport, track shipments, and get the best market prices.'), 'color' => 'green'],
                    ['icon' => '🚛', 'title' => __('Transporters'), 'desc' => __('List your vehicle, find loads, maximize capacity utilization, and earn more.'), 'color' => 'blue'],
                    ['icon' => '🏪', 'title' => __('Market Agents'), 'desc' => __('Update daily prices, confirm deliveries, and help farmers get fair deals.'), 'color' => 'amber'],
                    ['icon' => '⚙️', 'title' => __('Administrators'), 'desc' => __('Full platform oversight with analytics, user management, and dispute resolution.'), 'color' => 'purple'],
                ]; @endphp
                @foreach($roles as $role)
                <div class="border-2 border-{{ $role['color'] }}-100 rounded-2xl p-6 hover:border-{{ $role['color'] }}-300 transition bg-{{ $role['color'] }}-50/30">
                    <div class="text-4xl mb-3">{{ $role['icon'] }}</div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $role['title'] }}</h3>
                    <p class="text-sm text-gray-500">{{ $role['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-6xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900">{{ __('What Our Users Say') }}</h2>
                <p class="mt-3 text-gray-500">{{ __('Real stories from farmers and transporters across India') }}</p>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                @php $testimonials = [
                    ['name' => 'Ramesh Kumar', 'role' => __('Farmer, Rajasthan'), 'text' => __('AgriPool saved me ₹8,000 last month! I used to hire a full truck for 2 tons of onions. Now I share with 3 other farmers on the same route.'), 'rating' => 5],
                    ['name' => 'Arun Yadav', 'role' => __('Transporter, UP'), 'text' => __('My truck used to go half-empty. With AgriPool, I fill every trip to capacity. My earnings increased by 40% in just 2 months.'), 'rating' => 5],
                    ['name' => 'Lakshmi Devi', 'role' => __('Farmer, Maharashtra'), 'text' => __('The real-time market prices help me decide when to sell. And the escrow payment gives me peace of mind. Excellent platform!'), 'rating' => 4],
                ]; @endphp
                @foreach($testimonials as $t)
                <div class="bg-white rounded-2xl p-6 border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="flex items-center gap-0.5 mb-3">
                        @for($i = 0; $i < 5; $i++)
                            <span class="{{ $i < $t['rating'] ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                        @endfor
                    </div>
                    <p class="text-gray-600 text-sm mb-4">"{{ $t['text'] }}"</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-100 text-green-700 rounded-full flex items-center justify-center font-bold">{{ strtoupper(substr($t['name'], 0, 1)) }}</div>
                        <div>
                            <p class="font-semibold text-gray-900 text-sm">{{ $t['name'] }}</p>
                            <p class="text-xs text-gray-500">{{ $t['role'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Tools Strip -->
    <section class="py-12 bg-white border-y border-gray-100">
        <div class="max-w-6xl mx-auto px-4">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-900">{{ __('Free Tools for Everyone') }}</h2>
                <p class="text-gray-500 mt-2">{{ __('No login required — explore our public tools') }}</p>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('load-board') }}" class="group flex items-center gap-3 bg-gray-50 rounded-xl p-4 hover:bg-green-50 hover:border-green-300 border border-gray-200 transition">
                    <span class="text-3xl">📦</span>
                    <div><p class="font-semibold text-gray-900 group-hover:text-green-700">{{ __('Load Board') }}</p><p class="text-xs text-gray-500">{{ __('Open marketplace') }}</p></div>
                </a>
                <a href="{{ route('fare-calculator') }}" class="group flex items-center gap-3 bg-gray-50 rounded-xl p-4 hover:bg-green-50 hover:border-green-300 border border-gray-200 transition">
                    <span class="text-3xl">🧮</span>
                    <div><p class="font-semibold text-gray-900 group-hover:text-green-700">{{ __('Fare Calculator') }}</p><p class="text-xs text-gray-500">{{ __('Estimate costs') }}</p></div>
                </a>
                <a href="{{ route('leaderboard') }}" class="group flex items-center gap-3 bg-gray-50 rounded-xl p-4 hover:bg-green-50 hover:border-green-300 border border-gray-200 transition">
                    <span class="text-3xl">🏆</span>
                    <div><p class="font-semibold text-gray-900 group-hover:text-green-700">{{ __('Leaderboard') }}</p><p class="text-xs text-gray-500">{{ __('Top performers') }}</p></div>
                </a>
                <a href="/crop-calendar" class="group flex items-center gap-3 bg-gray-50 rounded-xl p-4 hover:bg-green-50 hover:border-green-300 border border-gray-200 transition">
                    <span class="text-3xl">📅</span>
                    <div><p class="font-semibold text-gray-900 group-hover:text-green-700">{{ __('Crop Calendar') }}</p><p class="text-xs text-gray-500">{{ __('Plan your season') }}</p></div>
                </a>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-20 bg-gradient-to-r from-green-600 to-emerald-700 text-white text-center">
        <div class="max-w-3xl mx-auto px-4">
            <h2 class="text-3xl sm:text-4xl font-bold">{{ __('Ready to move your produce?') }}</h2>
            <p class="mt-4 text-green-100 text-lg">{{ __('Join thousands of farmers and transporters already saving with AgriPool.') }}</p>
            <a href="{{ route('register') }}" class="inline-block mt-8 bg-white text-green-700 px-10 py-4 rounded-xl text-lg font-bold hover:bg-green-50 transition shadow-lg">{{ __('Create Free Account') }}</a>
        </div>
    </section>

    @include('partials.footer')
</body>
</html>

