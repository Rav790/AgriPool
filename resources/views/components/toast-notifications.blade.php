<!-- Toast Notification System -->
<div x-data="toastManager()" class="fixed top-20 right-4 z-50 space-y-3 print:hidden" @toast.window="addToast($event.detail)">
    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="toast.visible" x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-8"
             class="min-w-[320px] max-w-sm bg-white rounded-xl shadow-xl border border-gray-200 p-4 flex items-start gap-3">
            <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-sm"
                 :class="{
                     'bg-green-100 text-green-600': toast.type === 'success',
                     'bg-red-100 text-red-600': toast.type === 'error',
                     'bg-blue-100 text-blue-600': toast.type === 'info',
                     'bg-amber-100 text-amber-600': toast.type === 'warning',
                 }">
                <span x-text="toast.type === 'success' ? '✓' : toast.type === 'error' ? '✕' : toast.type === 'warning' ? '⚠' : 'ℹ'"></span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-gray-900" x-html="toast.title"></p>
                <p class="text-xs text-gray-500 mt-0.5" x-text="toast.message" x-show="toast.message"></p>
            </div>
            <button @click="toast.visible = false" class="text-gray-400 hover:text-gray-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </template>
</div>

<script>
function toastManager() {
    return {
        toasts: [],
        addToast(detail) {
            const id = Date.now();
            this.toasts.push({ id, visible: true, ...detail });
            setTimeout(() => {
                const t = this.toasts.find(t => t.id === id);
                if (t) t.visible = false;
                setTimeout(() => this.toasts = this.toasts.filter(t => t.id !== id), 300);
            }, detail.duration || 4000);
        },
        init() {
            @if(session('success'))
                this.addToast({ type: 'success', title: @json(session('success')) });
            @endif
            @if(session('error'))
                this.addToast({ type: 'error', title: @json(session('error')) });
            @endif
            @if(session('info'))
                this.addToast({ type: 'info', title: @json(session('info')) });
            @endif
        }
    };
}
</script>
