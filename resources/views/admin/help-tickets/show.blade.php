<x-app-layout>
    <x-slot name="title">{{ __('Ticket') }} #{{ $ticket->id }}</x-slot>

    <div class="max-w-4xl mx-auto">
        <a href="{{ route('admin.help.index') }}" class="text-green-600 hover:underline text-sm mb-4 inline-block">← {{ __('Back to Tickets') }}</a>

        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Main content -->
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">{{ $ticket->subject }}</h1>
                            <p class="text-sm text-gray-500 mt-1">{{ __('By') }} {{ $ticket->user->name ?? '—' }} · {{ $ticket->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $ticket->status === 'open' ? 'bg-yellow-100 text-yellow-800' : ($ticket->status === 'resolved' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800') }}">
                            {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                        </span>
                    </div>
                    <div class="mt-4 bg-gray-50 rounded-lg p-4">
                        <p class="whitespace-pre-wrap text-gray-700">{{ $ticket->description }}</p>
                    </div>
                </div>

                <!-- Admin Response -->
                @if($ticket->admin_response)
                <div class="bg-green-50 rounded-xl border border-green-200 p-6">
                    <p class="text-sm font-semibold text-green-800 mb-2">✅ {{ __('Admin Response') }}</p>
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $ticket->admin_response }}</p>
                    <p class="text-xs text-green-600 mt-2">{{ $ticket->resolved_at?->diffForHumans() ?? '' }}</p>
                </div>
                @endif

                <!-- Respond Form -->
                @if($ticket->status !== 'resolved')
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="font-semibold text-gray-800 mb-3">💬 {{ __('Send Response') }}</h2>
                    <form method="POST" action="{{ route('admin.help.respond', $ticket) }}">
                        @csrf
                        <textarea name="response" rows="4" required placeholder="{{ __('Type your response...') }}" class="w-full rounded-lg border-gray-300 focus:ring-green-500 mb-3">{{ old('response') }}</textarea>
                        <div class="flex items-center gap-3">
                            <button type="submit" name="status" value="in_progress" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">{{ __('Reply & Mark In Progress') }}</button>
                            <button type="submit" name="status" value="resolved" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700">{{ __('Reply & Resolve') }}</button>
                        </div>
                    </form>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <h3 class="font-semibold text-gray-800 mb-3">{{ __('Ticket Details') }}</h3>
                    <dl class="space-y-3 text-sm">
                        <div><dt class="text-gray-500">{{ __('Category') }}</dt><dd class="font-medium text-gray-900 mt-0.5">{{ ucfirst($ticket->category ?? 'general') }}</dd></div>
                        <div><dt class="text-gray-500">{{ __('Priority') }}</dt>
                            <dd class="mt-0.5"><span class="px-2 py-0.5 rounded text-xs font-medium {{ ($ticket->priority ?? 'medium') === 'high' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700' }}">{{ ucfirst($ticket->priority ?? 'medium') }}</span></dd>
                        </div>
                        <div><dt class="text-gray-500">{{ __('Status') }}</dt><dd class="font-medium text-gray-900 mt-0.5 capitalize">{{ str_replace('_', ' ', $ticket->status) }}</dd></div>
                        <div><dt class="text-gray-500">{{ __('Created') }}</dt><dd class="font-medium text-gray-900 mt-0.5">{{ $ticket->created_at->diffForHumans() }}</dd></div>
                    </dl>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <h3 class="font-semibold text-gray-800 mb-3">{{ __('User Info') }}</h3>
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-green-100 text-green-700 rounded-full flex items-center justify-center font-bold">{{ strtoupper(substr($ticket->user->name ?? '?', 0, 1)) }}</div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $ticket->user->name ?? '—' }}</p>
                            <p class="text-xs text-gray-500 capitalize">{{ $ticket->user->role ?? '' }}</p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500">📧 {{ $ticket->user->email ?? '' }}</p>
                    <p class="text-xs text-gray-500 mt-1">📱 {{ $ticket->user->phone ?? __('Not set') }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
