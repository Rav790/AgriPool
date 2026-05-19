<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="AgriPool — Transport Sharing Platform for Agricultural Produce">
        <meta name="theme-color" content="#16a34a">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <link rel="manifest" href="/manifest.json">

        <title>{{ config('app.name', 'AgriPool') }} — {{ $title ?? __('Dashboard') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @stack('styles')
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-50" x-data="{ sidebarOpen: false, lowData: localStorage.getItem('lowData') === 'true', darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="if(darkMode) document.documentElement.classList.add('dark')">

            @include('layouts.navigation')

            <div class="flex">
                <!-- Sidebar -->
                @auth
                    @include('layouts.sidebar')
                @endauth

                <!-- Main Content -->
                <div class="flex-1 lg:ml-64">
                    <!-- Page Heading -->
                    @isset($header)
                        <header class="bg-white shadow-sm border-b border-gray-200">
                            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                            <div class="bg-green-50 border border-green-200 text-green-800 rounded-lg p-4 flex items-center" x-data="{ show: true }" x-show="show" x-transition>
                                <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                <span>{{ session('success') }}</span>
                                <button @click="show = false" class="ml-auto text-green-600 hover:text-green-800">&times;</button>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                            <div class="bg-red-50 border border-red-200 text-red-800 rounded-lg p-4 flex items-center" x-data="{ show: true }" x-show="show" x-transition>
                                <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                <span>{{ session('error') }}</span>
                                <button @click="show = false" class="ml-auto text-red-600 hover:text-red-800">&times;</button>
                            </div>
                        </div>
                    @endif

                    <!-- Page Content -->
                    <main class="py-6 px-4 sm:px-6 lg:px-8">
                        {{ $slot }}
                    </main>
                </div>
            </div>
        </div>

        @include('components.toast-notifications')

        @stack('scripts')
    </body>
</html>
