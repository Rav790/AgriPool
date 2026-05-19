<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-green-50 via-white to-blue-50 relative overflow-hidden">
            <!-- Decorative background elements -->
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
                <div class="absolute -top-[20%] -right-[10%] w-[50%] h-[50%] rounded-full bg-green-200/20 blur-3xl"></div>
                <div class="absolute -bottom-[20%] -left-[10%] w-[50%] h-[50%] rounded-full bg-blue-200/20 blur-3xl"></div>
            </div>

            <div class="z-10 mb-6 flex flex-col items-center">
                <a href="/" class="flex items-center gap-3 group">
                    <div class="w-12 h-12 bg-green-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-3xl font-bold text-gray-900 tracking-tight">Agri<span class="text-green-600">Pool</span></span>
                </a>
            </div>

            <div class="z-10 w-full sm:max-w-md px-8 py-8 bg-white/80 backdrop-blur-xl shadow-xl border border-gray-100 sm:rounded-3xl relative">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
