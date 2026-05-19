<x-app-layout>
    <x-slot name="title">{{ __('My Cooperatives') }}</x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">🤝 {{ __('Farmer Cooperatives') }}</h1>
                <p class="text-gray-500 mt-1">{{ __('Pool produce with nearby farmers to reduce transport costs') }}</p>
            </div>
            <a href="{{ route('farmer.cooperatives.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition">+ {{ __('Create Group') }}</a>
        </div>

        <!-- Join by Code -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
            <form method="POST" action="{{ route('farmer.cooperatives.join') }}" class="flex items-end gap-3">
                @csrf
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Have an invite code?') }}</label>
                    <input type="text" name="invite_code" maxlength="8" placeholder="e.g. A1B2C3D4" required class="w-full rounded-lg border-gray-300 uppercase text-center tracking-widest font-mono text-lg focus:ring-green-500">
                </div>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition">{{ __('Join Group') }}</button>
            </form>
        </div>

        <!-- My Groups -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($myGroups as $group)
                <a href="{{ route('farmer.cooperatives.show', $group) }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:shadow-md transition block">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="font-bold text-gray-900 text-lg">{{ $group->name }}</h3>
                            <p class="text-sm text-gray-500 mt-1">📍 {{ $group->region }}</p>
                        </div>
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">{{ $group->members_count }} {{ __('members') }}</span>
                    </div>
                    @if($group->description)
                        <p class="text-sm text-gray-600 mt-3 line-clamp-2">{{ $group->description }}</p>
                    @endif
                    <div class="mt-3 flex items-center gap-2 text-xs text-gray-400">
                        <span>🔑 {{ $group->invite_code }}</span>
                        <span>· {{ $group->pivot->role === 'admin' ? '👑 Admin' : '👤 Member' }}</span>
                    </div>
                </a>
            @empty
                <div class="col-span-2 text-center py-12 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300">
                    <div class="text-5xl mb-3">🌾</div>
                    <p class="text-gray-500 font-medium">{{ __('No cooperatives yet') }}</p>
                    <p class="text-sm text-gray-400 mt-1">{{ __('Create a group or join one using an invite code') }}</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
