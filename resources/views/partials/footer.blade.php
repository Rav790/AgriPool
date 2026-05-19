<!-- Footer -->
<footer class="bg-gray-900 text-gray-400 py-12">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
            <!-- Brand -->
            <div>
                <div class="flex items-center space-x-2 mb-4">
                    <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-white font-bold text-lg">AgriPool</span>
                </div>
                <p class="text-sm">{{ __('India\'s #1 transport pooling platform for agricultural produce.') }}</p>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-white font-semibold mb-3">{{ __('Platform') }}</h4>
                <div class="space-y-2 text-sm">
                    <a href="/load-board" class="block hover:text-green-400 transition">{{ __('Load Board') }}</a>
                    <a href="/fare-calculator" class="block hover:text-green-400 transition">{{ __('Fare Calculator') }}</a>
                    <a href="/leaderboard" class="block hover:text-green-400 transition">{{ __('Leaderboard') }}</a>
                    <a href="/crop-calendar" class="block hover:text-green-400 transition">{{ __('Crop Calendar') }}</a>
                </div>
            </div>

            <!-- Company -->
            <div>
                <h4 class="text-white font-semibold mb-3">{{ __('Company') }}</h4>
                <div class="space-y-2 text-sm">
                    <a href="/about" class="block hover:text-green-400 transition">{{ __('About Us') }}</a>
                    <a href="/contact" class="block hover:text-green-400 transition">{{ __('Contact') }}</a>
                    <a href="/terms" class="block hover:text-green-400 transition">{{ __('Terms of Service') }}</a>
                    <a href="/privacy" class="block hover:text-green-400 transition">{{ __('Privacy Policy') }}</a>
                </div>
            </div>

            <!-- Support -->
            <div>
                <h4 class="text-white font-semibold mb-3">{{ __('Support') }}</h4>
                <div class="space-y-2 text-sm">
                    <p>📧 support@agripool.in</p>
                    <p>📱 +91 1800-XXX-XXXX</p>
                    <p>💬 WhatsApp: +91 98XXX XXXXX</p>
                </div>
                <div class="flex gap-3 mt-4">
                    <a href="#" class="w-8 h-8 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-green-600 transition text-sm">𝕏</a>
                    <a href="#" class="w-8 h-8 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-green-600 transition text-sm">📘</a>
                    <a href="#" class="w-8 h-8 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-green-600 transition text-sm">📸</a>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-800 pt-6 flex flex-wrap justify-between items-center text-sm">
            <p>© {{ date('Y') }} AgriPool Technologies Pvt. Ltd. {{ __('All rights reserved.') }}</p>
            <p class="text-xs text-gray-600">{{ __('Made with ❤️ in India') }}</p>
        </div>
    </div>
</footer>
