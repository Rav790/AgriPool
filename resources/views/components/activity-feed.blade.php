<!-- Live Activity Feed — Auto-refreshes via Alpine polling -->
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden" x-data="{ activities: {{ json_encode($activities ?? []) }} }">
    <div class="px-5 py-4 border-b border-gray-200 flex items-center justify-between">
        <h3 class="font-semibold text-gray-800">⚡ {{ __('Recent Activity') }}</h3>
        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse" title="{{ __('Live') }}"></span>
    </div>
    <div class="divide-y divide-gray-50 max-h-80 overflow-y-auto">
        <template x-for="(a, i) in activities" :key="i">
            <div class="px-5 py-3 flex items-start gap-3 hover:bg-gray-50 transition">
                <div class="w-8 h-8 rounded-full flex-shrink-0 flex items-center justify-center text-sm"
                     :class="{
                         'bg-green-100 text-green-600': a.type === 'booking',
                         'bg-blue-100 text-blue-600': a.type === 'request',
                         'bg-amber-100 text-amber-600': a.type === 'delivery',
                         'bg-purple-100 text-purple-600': a.type === 'payment',
                         'bg-gray-100 text-gray-600': !['booking','request','delivery','payment'].includes(a.type)
                     }">
                    <span x-text="a.icon || '📋'"></span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-gray-800" x-text="a.message"></p>
                    <p class="text-xs text-gray-400 mt-0.5" x-text="a.time"></p>
                </div>
            </div>
        </template>
        <div x-show="activities.length === 0" class="px-5 py-8 text-center text-gray-400 text-sm">
            {{ __('No recent activity') }}
        </div>
    </div>
</div>
