<x-app-layout>
    <x-slot name="title">{{ __('Notifications') }}</x-slot>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('Notifications') }}</h1>
            <form method="POST" action="{{ route('notifications.mark-all-read') }}">@csrf
                <button type="submit" class="text-sm text-green-600 hover:text-green-800 font-medium">{{ __('Mark All as Read') }}</button>
            </form>
        </div>
    </x-slot>
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-xl border overflow-hidden">
            <div class="divide-y">
                @forelse($notifications as $notification)
                <div class="p-4 {{ $notification->read_at ? '' : 'bg-green-50' }} hover:bg-gray-50">
                    <div class="flex justify-between">
                        <div>
                            <p class="font-medium text-sm {{ $notification->read_at ? 'text-gray-700' : 'text-gray-900' }}">
                                {{ $notification->data['message'] ?? $notification->data['title'] ?? __('Notification') }}
                            </p>
                            @if(isset($notification->data['body']))
                                <p class="text-sm text-gray-500 mt-1">{{ $notification->data['body'] }}</p>
                            @endif
                            <div class="flex flex-wrap gap-2 mt-2">
                                @if(isset($notification->data['crop']) && $notification->data['crop'] !== '—')
                                    <span class="text-xs bg-green-50 text-green-700 font-semibold px-2 py-0.5 rounded">🌱 {{ $notification->data['crop'] }}</span>
                                @endif
                                @if(isset($notification->data['amount']))
                                    <span class="text-xs bg-indigo-50 text-indigo-700 font-semibold px-2 py-0.5 rounded">💵 ₹{{ number_format($notification->data['amount']) }}</span>
                                @endif
                                @if(isset($notification->data['tons']))
                                    <span class="text-xs bg-amber-50 text-amber-700 font-semibold px-2 py-0.5 rounded">⚖️ {{ $notification->data['tons'] }} {{ __('Tons') }}</span>
                                @endif
                                @if(isset($notification->data['target_price']))
                                    <span class="text-xs bg-purple-50 text-purple-700 font-semibold px-2 py-0.5 rounded">🎯 ₹{{ number_format($notification->data['target_price']) }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0 ml-4">
                            <p class="text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</p>
                            @if(!$notification->read_at)
                            <form method="POST" action="{{ route('notifications.read', $notification->id) }}" class="mt-1">@csrf
                                <button type="submit" class="text-xs text-green-600">{{ __('Mark read') }}</button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-12 text-center text-gray-400">{{ __('No notifications yet.') }}</div>
                @endforelse
            </div>
            <div class="p-4 border-t">{{ $notifications->links() }}</div>
        </div>
    </div>
</x-app-layout>
