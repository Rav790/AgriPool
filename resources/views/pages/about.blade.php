<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="About AgriPool — India's leading transport pooling platform for agricultural produce.">
    <title>{{ __('About Us') }} — AgriPool</title>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet">
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
                <a href="{{ route('load-board') }}" class="text-sm font-medium text-gray-700 hover:text-green-600 hidden sm:inline">{{ __('Load Board') }}</a>
                <a href="{{ url('/contact') }}" class="text-sm font-medium text-gray-700 hover:text-green-600 hidden sm:inline">{{ __('Contact') }}</a>
                <a href="{{ route('register') }}" class="bg-green-600 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition">{{ __('Get Started') }}</a>
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="pt-28 pb-16 bg-gradient-to-br from-green-600 to-emerald-800 text-white">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h1 class="text-4xl sm:text-5xl font-extrabold">{{ __('Our Mission') }}</h1>
            <p class="mt-6 text-xl text-green-100 max-w-2xl mx-auto">{{ __('Empowering Indian farmers with affordable, shared transport to get their produce to market faster and cheaper.') }}</p>
        </div>
    </section>

    <!-- Story -->
    <section class="py-20 bg-white">
        <div class="max-w-5xl mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">{{ __('The Problem We Solve') }}</h2>
                    <p class="text-gray-600 mb-4">{{ __('In India, 30% of agricultural produce is wasted due to inadequate transport. Small farmers often pay full truckloads for partial loads, making transport unaffordable.') }}</p>
                    <p class="text-gray-600 mb-4">{{ __('AgriPool solves this by connecting farmers heading to the same markets, allowing them to share transport costs — reducing expenses by up to 60%.') }}</p>
                    <p class="text-gray-600">{{ __('Our platform also provides real-time market prices, escrow-protected payments, and live tracking — bringing transparency to agricultural logistics.') }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-green-50 rounded-2xl p-6 text-center"><p class="text-4xl font-bold text-green-600">60%</p><p class="text-sm text-gray-600 mt-2">{{ __('Cost Savings') }}</p></div>
                    <div class="bg-blue-50 rounded-2xl p-6 text-center"><p class="text-4xl font-bold text-blue-600">30%</p><p class="text-sm text-gray-600 mt-2">{{ __('Less Wastage') }}</p></div>
                    <div class="bg-amber-50 rounded-2xl p-6 text-center"><p class="text-4xl font-bold text-amber-600">100%</p><p class="text-sm text-gray-600 mt-2">{{ __('Secure Payments') }}</p></div>
                    <div class="bg-purple-50 rounded-2xl p-6 text-center"><p class="text-4xl font-bold text-purple-600">24/7</p><p class="text-sm text-gray-600 mt-2">{{ __('Live Tracking') }}</p></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Values -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-5xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-12">{{ __('Our Values') }}</h2>
            <div class="grid sm:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl p-8 border border-gray-200">
                    <div class="text-4xl mb-4">🌱</div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('Farmer First') }}</h3>
                    <p class="text-sm text-gray-500">{{ __('Every decision is made with the farmer\'s livelihood in mind.') }}</p>
                </div>
                <div class="bg-white rounded-2xl p-8 border border-gray-200">
                    <div class="text-4xl mb-4">🤝</div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('Community Driven') }}</h3>
                    <p class="text-sm text-gray-500">{{ __('We build cooperative tools that strengthen farming communities.') }}</p>
                </div>
                <div class="bg-white rounded-2xl p-8 border border-gray-200">
                    <div class="text-4xl mb-4">🔒</div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('Trust & Safety') }}</h3>
                    <p class="text-sm text-gray-500">{{ __('KYC verification, escrow payments, and dispute resolution protect everyone.') }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Team -->
    <section class="py-20 bg-white">
        <div class="max-w-5xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-12">{{ __('Leadership Team') }}</h2>
            <div class="grid sm:grid-cols-3 gap-8">
                @foreach([
                    ['name' => 'Rajesh Verma', 'role' => __('CEO & Founder'), 'bio' => __('20 years in agri-tech. Ex-IIT Kharagpur.'), 'color' => 'green'],
                    ['name' => 'Meera Patel', 'role' => __('CTO'), 'bio' => __('Ex-Flipkart. Built logistics at scale.'), 'color' => 'blue'],
                    ['name' => 'Arjun Singh', 'role' => __('Head of Operations'), 'bio' => __('10 years in supply chain for FMCG.'), 'color' => 'amber'],
                ] as $member)
                    <div class="text-center">
                        <div class="w-24 h-24 bg-{{ $member['color'] }}-100 rounded-full mx-auto flex items-center justify-center text-3xl font-bold text-{{ $member['color'] }}-600">{{ strtoupper(substr($member['name'], 0, 1)) }}</div>
                        <h3 class="font-bold text-gray-900 mt-4">{{ $member['name'] }}</h3>
                        <p class="text-sm text-green-600 font-medium">{{ $member['role'] }}</p>
                        <p class="text-sm text-gray-500 mt-1">{{ $member['bio'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-16 bg-gradient-to-r from-green-600 to-emerald-700 text-white text-center">
        <div class="max-w-3xl mx-auto px-4">
            <h2 class="text-3xl font-bold">{{ __('Join the AgriPool Revolution') }}</h2>
            <p class="mt-4 text-green-100">{{ __('Whether you\'re a farmer, transporter, or market agent — there\'s a place for you.') }}</p>
            <a href="{{ route('register') }}" class="inline-block mt-6 bg-white text-green-700 px-8 py-3 rounded-xl font-bold hover:bg-green-50 transition">{{ __('Create Free Account') }}</a>
        </div>
    </section>

    @include('partials.footer')
</body>
</html>
