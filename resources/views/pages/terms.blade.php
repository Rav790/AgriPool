<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>{{ __('Terms of Service') }} — AgriPool</title><link href="https://fonts.bunny.net/css?family=inter:400,600,700&display=swap" rel="stylesheet">@vite(['resources/css/app.css'])</head>
<body class="font-sans antialiased bg-gray-50">
    <nav class="bg-white border-b border-gray-100 fixed w-full z-50"><div class="max-w-7xl mx-auto px-4 flex justify-between items-center h-16"><a href="/" class="flex items-center space-x-2"><div class="w-9 h-9 bg-green-600 rounded-lg flex items-center justify-center"><svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div><span class="text-xl font-bold text-green-700">AgriPool</span></a><a href="/" class="text-sm font-medium text-gray-700 hover:text-green-600">← {{ __('Home') }}</a></div></nav>
    <div class="pt-24 pb-16 max-w-3xl mx-auto px-4">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ __('Terms of Service') }}</h1>
        <div class="bg-white rounded-xl p-8 border border-gray-200 prose prose-green max-w-none">
            <p class="text-sm text-gray-500">Last updated: {{ date('F d, Y') }}</p>
            <h2>1. Acceptance of Terms</h2><p>By accessing AgriPool, you agree to be bound by these Terms of Service. If you do not agree, do not use the platform.</p>
            <h2>2. User Accounts</h2><p>You must provide accurate information during registration. You are responsible for maintaining the security of your account credentials. Each person may create only one account.</p>
            <h2>3. Service Description</h2><p>AgriPool provides a marketplace connecting farmers with transport providers. We do not own or operate any transport vehicles. We act as an intermediary platform.</p>
            <h2>4. Payments & Escrow</h2><p>All payments are processed through our escrow system. Funds are held securely until delivery is confirmed by both parties. Platform fees are non-refundable once a booking is confirmed.</p>
            <h2>5. Dispute Resolution</h2><p>Disputes must be raised within 48 hours of delivery. Our team will investigate and resolve disputes within 5 business days. Decisions by the admin team are final.</p>
            <h2>6. Prohibited Conduct</h2><p>Users must not: falsify information, manipulate pricing, harass other users, or use the platform for illegal transport of goods.</p>
            <h2>7. Limitation of Liability</h2><p>AgriPool is not liable for damage, loss, or delay of goods during transport. Users should obtain appropriate insurance for high-value shipments.</p>
            <h2>8. Modifications</h2><p>We reserve the right to modify these terms at any time. Continued use constitutes acceptance of modified terms.</p>
            <h2>9. Contact</h2><p>For questions about these Terms, contact us at <a href="mailto:legal@agripool.in" class="text-green-600">legal@agripool.in</a>.</p>
        </div>
    </div>
    @include('partials.footer')
</body></html>
