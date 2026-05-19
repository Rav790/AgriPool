<x-app-layout>
    <x-slot name="title">{{ __('Messages') }}</x-slot>

    <div class="max-w-5xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">💬 {{ __('Messages') }}</h1>
                <p class="text-gray-500 text-sm mt-1">{{ __('Chat with farmers and transporters on the platform') }}</p>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Conversations List -->
            <div class="lg:col-span-2 space-y-3">
                <h2 class="font-semibold text-gray-700 text-sm uppercase tracking-wider">{{ __('Your Conversations') }}</h2>

                @if(count($conversations) > 0)
                @foreach($conversations as $convo)
                <a href="{{ route('messages.direct', $convo['user']) }}" class="block bg-white rounded-xl border border-gray-200 p-4 hover:shadow-md hover:border-green-300 transition-all {{ $convo['unread_count'] > 0 ? 'border-l-4 border-l-green-500' : '' }}">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-{{ $convo['user']->role === 'farmer' ? 'amber' : 'blue' }}-100 text-{{ $convo['user']->role === 'farmer' ? 'amber' : 'blue' }}-700 rounded-full flex items-center justify-center font-bold text-lg flex-shrink-0">
                            {{ strtoupper(substr($convo['user']->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <p class="font-semibold text-gray-900 truncate">{{ $convo['user']->name }}</p>
                                <span class="text-xs text-gray-400">{{ $convo['last_message']?->created_at?->diffForHumans() ?? '' }}</span>
                            </div>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="text-xs px-2 py-0.5 rounded-full {{ $convo['user']->role === 'farmer' ? 'bg-amber-50 text-amber-600' : 'bg-blue-50 text-blue-600' }}">{{ ucfirst($convo['user']->role) }}</span>
                                <p class="text-sm text-gray-500 truncate">{{ Str::limit($convo['last_message']?->message ?? '—', 50) }}</p>
                            </div>
                        </div>
                        @if($convo['unread_count'] > 0)
                        <div class="w-6 h-6 bg-green-600 text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">
                            {{ $convo['unread_count'] }}
                        </div>
                        @endif
                    </div>
                </a>
                @endforeach
                @else
                <div class="bg-white rounded-xl border p-8 text-center text-gray-400">
                    <p class="text-3xl mb-2">📭</p>
                    <p class="font-medium">{{ __('No conversations yet') }}</p>
                    <p class="text-sm mt-1">{{ __('Start a new conversation from the directory →') }}</p>
                </div>
                @endif
            </div>

            <!-- User Directory -->
            <div>
                <h2 class="font-semibold text-gray-700 text-sm uppercase tracking-wider mb-3">{{ __('Start New Chat') }}</h2>
                <div class="bg-white rounded-xl border border-gray-200 divide-y divide-gray-100 max-h-[500px] overflow-y-auto">
                    @forelse($directory as $person)
                    <a href="{{ route('messages.direct', $person) }}" class="flex items-center gap-3 p-3 hover:bg-gray-50 transition">
                        <div class="w-9 h-9 bg-{{ $person->role === 'farmer' ? 'amber' : 'blue' }}-100 text-{{ $person->role === 'farmer' ? 'amber' : 'blue' }}-700 rounded-full flex items-center justify-center font-bold text-sm flex-shrink-0">
                            {{ strtoupper(substr($person->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $person->name }}</p>
                            <p class="text-xs text-gray-400 capitalize">{{ $person->role }} · {{ $person->city ?? $person->state ?? '' }}</p>
                        </div>
                        <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    @empty
                    <div class="p-4 text-center text-gray-400 text-sm">{{ __('No new users to chat with') }}</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
