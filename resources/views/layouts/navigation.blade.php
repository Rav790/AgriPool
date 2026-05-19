<nav class="bg-white border-b border-gray-200 fixed w-full z-30 top-0 shadow-sm" x-data="{ mobileMenu: false, notifOpen: false }">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Left: Logo + Hamburger -->
            <div class="flex items-center">
                @auth
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 mr-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                @endauth

                <a href="{{ route('home') }}" class="flex items-center space-x-2">
                    <div class="w-9 h-9 bg-green-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-xl font-bold text-green-700">{{ __('AgriPool') }}</span>
                </a>
            </div>

            <!-- Right: Actions -->
            <div class="flex items-center space-x-3">
                <!-- Language Switcher -->
                <div class="relative" x-data="{ langOpen: false }">
                    <button @click="langOpen = !langOpen" class="p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 text-sm font-medium">
                        {{ app()->getLocale() === 'hi' ? 'हिं' : 'EN' }}
                    </button>
                    <div x-show="langOpen" @click.away="langOpen = false" x-transition class="absolute right-0 mt-2 w-32 bg-white rounded-lg shadow-lg border z-50">
                        <a href="{{ route('language.switch', 'en') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 {{ app()->getLocale() === 'en' ? 'font-bold text-green-600' : '' }}">English</a>
                        <a href="{{ route('language.switch', 'hi') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 {{ app()->getLocale() === 'hi' ? 'font-bold text-green-600' : '' }}">हिन्दी</a>
                    </div>
                </div>

                <!-- Low Data Toggle -->
                <button @click="lowData = !lowData; localStorage.setItem('lowData', lowData)" class="p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100" :title="lowData ? '{{ __('Normal Mode') }}' : '{{ __('Low Data Mode') }}'">
                    <svg x-show="!lowData" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <svg x-show="lowData" class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </button>

                <!-- Dark Mode Toggle -->
                <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode); document.documentElement.classList.toggle('dark', darkMode)" class="p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100" :title="darkMode ? '{{ __('Light Mode') }}' : '{{ __('Dark Mode') }}'">
                    <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    <svg x-show="darkMode" class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.25a.75.75 0 01.75.75v2.25a.75.75 0 01-1.5 0V3a.75.75 0 01.75-.75zM7.5 12a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM18.894 6.166a.75.75 0 00-1.06-1.06l-1.591 1.59a.75.75 0 101.06 1.061l1.591-1.59zM21.75 12a.75.75 0 01-.75.75h-2.25a.75.75 0 010-1.5H21a.75.75 0 01.75.75zM17.834 18.894a.75.75 0 001.06-1.06l-1.59-1.591a.75.75 0 10-1.061 1.06l1.59 1.591zM12 18a.75.75 0 01.75.75V21a.75.75 0 01-1.5 0v-2.25A.75.75 0 0112 18zM7.758 17.303a.75.75 0 00-1.061-1.06l-1.591 1.59a.75.75 0 001.06 1.061l1.591-1.59zM6 12a.75.75 0 01-.75.75H3a.75.75 0 010-1.5h2.25A.75.75 0 016 12zM6.697 7.757a.75.75 0 001.06-1.06l-1.59-1.591a.75.75 0 00-1.061 1.06l1.59 1.591z"/></svg>
                </button>

                @auth
                <!-- Notifications Bell -->
                <div class="relative" x-data="{ count: 0 }" x-init="fetch('/api/notifications/count').then(r=>r.json()).then(d=>{count=d.count})">
                    <button @click="notifOpen = !notifOpen" class="p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 relative">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        <span x-show="count > 0" x-text="count" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold"></span>
                    </button>
                    <div x-show="notifOpen" @click.away="notifOpen = false" x-transition class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border z-50">
                        <div class="p-3 border-b flex justify-between items-center">
                            <span class="font-semibold text-gray-800">{{ __('Notifications') }}</span>
                            <form method="POST" action="{{ route('notifications.mark-all-read') }}">
                                @csrf
                                <button type="submit" class="text-xs text-green-600 hover:text-green-800">{{ __('Mark all read') }}</button>
                            </form>
                        </div>
                        <div class="max-h-64 overflow-y-auto">
                            @forelse(auth()->user()->unreadNotifications->take(5) as $notification)
                                <div class="px-3 py-2 border-b hover:bg-gray-50 text-sm">
                                    <p class="font-medium text-gray-800">{{ $notification->data['message'] ?? $notification->data['title'] ?? __('Notification') }}</p>
                                    <p class="text-gray-500 text-xs mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                </div>
                            @empty
                                <div class="px-3 py-4 text-center text-gray-400 text-sm">{{ __('No new notifications') }}</div>
                            @endforelse
                        </div>
                        <a href="{{ route('notifications.index') }}" class="block text-center py-2 text-sm text-green-600 hover:text-green-800 border-t">{{ __('View All') }}</a>
                    </div>
                </div>

                <!-- Messages Badge -->
                <div x-data="{ msgCount: 0 }" x-init="fetch('/api/messages/count').then(r=>r.json()).then(d=>{msgCount=d.count})">
                    <a href="{{ route('messages.hub') }}" class="p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 relative inline-block" title="{{ __('Messages') }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                        <span x-show="msgCount > 0" x-text="msgCount" class="absolute -top-1 -right-1 bg-amber-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold"></span>
                    </a>
                </div>

                <!-- Profile Dropdown -->
                <div class="relative" x-data="{ profileOpen: false }">
                    <button @click="profileOpen = !profileOpen" class="flex items-center space-x-2 p-1 rounded-lg hover:bg-gray-100">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <span class="text-green-700 font-semibold text-sm">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                        </div>
                        <span class="hidden sm:block text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="profileOpen" @click.away="profileOpen = false" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border z-50">
                        <div class="px-4 py-2 border-b">
                            <p class="text-sm font-medium text-gray-800">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500 capitalize">{{ auth()->user()->role }}</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">{{ __('Profile') }}</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">{{ __('Log Out') }}</button>
                        </form>
                    </div>
                </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-green-600">{{ __('Login') }}</a>
                    <a href="{{ route('register') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition">{{ __('Register') }}</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<!-- Spacer for fixed nav -->
<div class="h-16"></div>
