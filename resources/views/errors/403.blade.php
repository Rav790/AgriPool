<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 — Access Denied | AgriPool</title>
    <link href="https://fonts.bunny.net/css?family=inter:400,600,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="font-sans antialiased bg-gray-50 flex items-center justify-center min-h-screen">
    <div class="text-center px-4">
        <div class="text-8xl mb-4">🔒</div>
        <h1 class="text-6xl font-bold text-gray-300 mb-2">403</h1>
        <h2 class="text-2xl font-bold text-gray-800 mb-4">{{ __('Access Denied') }}</h2>
        <p class="text-gray-500 max-w-md mx-auto mb-8">{{ __('You don\'t have permission to access this page. Please contact support if you believe this is an error.') }}</p>
        <a href="{{ url('/') }}" class="px-6 py-3 bg-green-600 text-white rounded-xl font-semibold hover:bg-green-700 transition">{{ __('Go Home') }}</a>
    </div>
</body>
</html>
