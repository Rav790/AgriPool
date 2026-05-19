<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>{{ __('Privacy Policy') }} — AgriPool</title><link href="https://fonts.bunny.net/css?family=inter:400,600,700&display=swap" rel="stylesheet">@vite(['resources/css/app.css'])</head>
<body class="font-sans antialiased bg-gray-50">
    <nav class="bg-white border-b border-gray-100 fixed w-full z-50"><div class="max-w-7xl mx-auto px-4 flex justify-between items-center h-16"><a href="/" class="flex items-center space-x-2"><div class="w-9 h-9 bg-green-600 rounded-lg flex items-center justify-center"><svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div><span class="text-xl font-bold text-green-700">AgriPool</span></a><a href="/" class="text-sm font-medium text-gray-700 hover:text-green-600">← {{ __('Home') }}</a></div></nav>
    <div class="pt-24 pb-16 max-w-3xl mx-auto px-4">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ __('Privacy Policy') }}</h1>
        <div class="bg-white rounded-xl p-8 border border-gray-200 prose prose-green max-w-none">
            <p class="text-sm text-gray-500">Last updated: {{ date('F d, Y') }}</p>
            <h2>1. Information We Collect</h2><p>We collect personal information including name, email, phone number, Aadhaar/PAN (for KYC), location data, and transaction history.</p>
            <h2>2. How We Use Information</h2><p>Your data is used to: facilitate bookings, process payments, verify identity, send notifications, improve our services, and comply with legal requirements.</p>
            <h2>3. Data Sharing</h2><p>We share limited information with: transport providers (for bookings), payment processors, and government authorities (when required by law). We never sell your data.</p>
            <h2>4. Data Security</h2><p>We use industry-standard encryption (SSL/TLS) for data transmission. KYC documents are stored encrypted. Passwords are hashed using bcrypt.</p>
            <h2>5. Your Rights</h2><p>You may: access, update, or delete your personal data at any time through your profile settings. You can request data export in machine-readable format.</p>
            <h2>6. Cookies</h2><p>We use essential cookies for authentication and session management. Analytics cookies are used to improve user experience. You may disable non-essential cookies.</p>
            <h2>7. Children's Privacy</h2><p>AgriPool is not intended for users under 18. We do not knowingly collect data from minors.</p>
            <h2>8. Contact</h2><p>For privacy concerns, contact our Data Protection Officer at <a href="mailto:privacy@agripool.in" class="text-green-600">privacy@agripool.in</a>.</p>
        </div>
    </div>
    @include('partials.footer')
</body></html>
