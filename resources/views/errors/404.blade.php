<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 — Page Not Found | AgriPool</title>
    <link href="https://fonts.bunny.net/css?family=inter:400,600,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="font-sans antialiased bg-gray-50 flex items-center justify-center min-h-screen">
    <div class="text-center px-4">
        <div class="text-8xl mb-4">🌾</div>
        <h1 class="text-6xl font-bold text-gray-300 mb-2">404</h1>
        <h2 class="text-2xl font-bold text-gray-800 mb-4">{{ __('Lost in the Fields') }}</h2>
        <p class="text-gray-500 max-w-md mx-auto mb-8">{{ __('The page you\'re looking for doesn\'t exist. It might have been moved or the URL is incorrect.') }}</p>
        <div class="flex justify-center gap-4">
            <a href="{{ url('/') }}" class="px-6 py-3 bg-green-600 text-white rounded-xl font-semibold hover:bg-green-700 transition">{{ __('Go Home') }}</a>
            <a href="{{ url()->previous() }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition">{{ __('Go Back') }}</a>
        </div>
    </div>
</body>
</html>
