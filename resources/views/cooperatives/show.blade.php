<x-app-layout>
    <x-slot name="title">{{ $cooperative->name }}</x-slot>

    <div class="max-w-4xl mx-auto">
        <a href="{{ route('farmer.cooperatives.index') }}" class="text-green-600 hover:underline text-sm mb-4 inline-block">← {{ __('Back to Cooperatives') }}</a>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $cooperative->name }}</h1>
                    <p class="text-gray-500 mt-1">📍 {{ $cooperative->region }} · {{ __('Created by') }} {{ $cooperative->creator->name }}</p>
                    @if($cooperative->description)
                        <p class="text-gray-600 mt-3">{{ $cooperative->description }}</p>
                    @endif
                </div>
                <div class="text-center bg-green-50 rounded-xl p-4 min-w-[100px]">
                    <p class="text-3xl font-bold text-green-600">{{ $cooperative->member_count }}</p>
                    <p class="text-xs text-green-700">{{ __('members') }}</p>
                </div>
            </div>

            <!-- Invite Code -->
            <div class="mt-4 bg-gray-50 rounded-lg p-4 flex items-center justify-between" x-data="{ copied: false }">
                <div>
                    <p class="text-sm text-gray-500">{{ __('Invite Code') }}</p>
                    <p class="text-2xl font-mono font-bold tracking-widest text-gray-900">{{ $cooperative->invite_code }}</p>
                </div>
                <button @click="navigator.clipboard.writeText('{{ $cooperative->invite_code }}'); copied = true; setTimeout(() => copied = false, 2000)"
                        class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium hover:bg-gray-50 transition">
                    <span x-show="!copied">📋 {{ __('Copy') }}</span>
                    <span x-show="copied" class="text-green-600">✅ {{ __('Copied!') }}</span>
                </button>
            </div>
        </div>

        <!-- Group Discussion Board -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6 flex flex-col h-[500px]">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-white flex justify-between items-center">
                <h2 class="font-bold text-gray-900 flex items-center gap-2">💬 {{ __('Group Discussion Board') }}</h2>
                <span class="text-xs text-gray-500">{{ $cooperative->messages->count() }} {{ __('messages') }}</span>
            </div>
            
            <!-- Message Feed -->
            <div class="flex-1 p-6 overflow-y-auto space-y-4 bg-gray-50/50">
                @forelse($cooperative->messages as $msg)
                    <div class="flex flex-col {{ $msg->user_id === auth()->id() ? 'items-end' : 'items-start' }}">
                        <div class="flex items-baseline gap-2 mb-1 px-1">
                            <span class="text-xs font-bold text-gray-700">{{ $msg->user->name ?? __('Member') }}</span>
                            <span class="text-[10px] text-gray-400">{{ $msg->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="max-w-lg rounded-2xl px-4 py-3 {{ $msg->user_id === auth()->id() ? 'bg-green-600 text-white rounded-br-none' : 'bg-white text-gray-800 border border-gray-200 shadow-sm rounded-bl-none' }}">
                            @if($msg->message)
                                <p class="text-sm whitespace-pre-wrap break-words">{{ $msg->message }}</p>
                            @endif
                            
                            @if($msg->attachment_path)
                                <div class="mt-2 pt-2 border-t {{ $msg->user_id === auth()->id() ? 'border-white/20' : 'border-gray-100' }}">
                                    <a href="{{ asset('storage/' . $msg->attachment_path) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-bold underline hover:opacity-80 break-all">
                                        📎 {{ basename($msg->attachment_path) }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="h-full flex flex-col items-center justify-center text-gray-400">
                        <p class="text-4xl mb-2">💬</p>
                        <p class="text-sm">{{ __('Start the conversation! Send a message or upload an attachment below.') }}</p>
                    </div>
                @endforelse
            </div>
            
            <!-- Message Input Form -->
            <form action="{{ route('farmer.cooperatives.messages.store', $cooperative) }}" method="POST" enctype="multipart/form-data" class="p-4 border-t border-gray-200 bg-white">
                @csrf
                <div class="flex items-end gap-2">
                    <div class="flex-1 relative">
                        <textarea name="message" rows="2" class="w-full rounded-xl border-gray-300 pr-10 focus:ring-green-500 focus:border-green-500 text-sm placeholder-gray-400 resize-none" placeholder="{{ __('Type a message to the group...') }}"></textarea>
                        
                        <label class="absolute right-3 bottom-2.5 cursor-pointer text-gray-400 hover:text-green-600 transition" title="{{ __('Attach File / Document') }}">
                            <input type="file" name="attachment" class="hidden" onchange="this.parentElement.style.color = '#16a34a'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                        </label>
                    </div>
                    
                    <button type="submit" class="h-[52px] px-5 bg-green-600 text-white rounded-xl font-bold text-sm hover:bg-green-700 transition flex items-center justify-center gap-1.5 shadow-sm">
                        <span>{{ __('Send') }}</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    </button>
                </div>
            </form>
        </div>

        <!-- Members -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="font-semibold text-gray-800">👥 {{ __('Members') }}</h2>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($cooperative->members as $member)
                    <div class="flex items-center px-6 py-3">
                        <div class="w-10 h-10 rounded-full bg-green-100 text-green-700 flex items-center justify-center font-bold">{{ strtoupper(substr($member->name, 0, 1)) }}</div>
                        <div class="ml-3 flex-1">
                            <p class="font-medium text-gray-900">{{ $member->name }}</p>
                            <p class="text-xs text-gray-500">{{ $member->phone ?? $member->email }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs rounded-full {{ $member->pivot->role === 'admin' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-600' }}">
                            {{ $member->pivot->role === 'admin' ? '👑 Admin' : '👤 Member' }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        @if($cooperative->members->contains(auth()->user()) && $cooperative->created_by !== auth()->id())
            <form method="POST" action="{{ route('farmer.cooperatives.leave', $cooperative) }}" class="mt-4" onsubmit="return confirm('{{ __('Are you sure you want to leave?') }}')">
                @csrf @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">{{ __('Leave Group') }}</button>
            </form>
        @endif
    </div>
</x-app-layout>
