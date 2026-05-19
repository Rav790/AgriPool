<x-app-layout>
    <x-slot name="title">{{ __('Ticket') }} #{{ $ticket->id }}</x-slot>

    <div class="max-w-3xl mx-auto">
        <a href="{{ route('help.index') }}" class="text-green-600 hover:underline text-sm mb-4 inline-block">← {{ __('Back to Help Center') }}</a>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-bold text-gray-900">{{ $ticket->subject }}</h1>
                    <p class="text-sm text-gray-500 mt-0.5">{{ ucfirst($ticket->category) }} · {{ $ticket->created_at->format('M d, Y h:i A') }} · {{ __('by') }} {{ $ticket->user->name }}</p>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $ticket->status === 'open' ? 'bg-yellow-100 text-yellow-800' : ($ticket->status === 'resolved' ? 'bg-green-100 text-green-800' : ($ticket->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')) }}">
                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                </span>
            </div>

            <div class="p-6">
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <p class="text-gray-800 whitespace-pre-wrap">{{ $ticket->description }}</p>
                </div>

                @if($ticket->admin_response)
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <p class="text-sm font-semibold text-green-800 mb-1">📩 {{ __('Admin Response') }}</p>
                        <p class="text-gray-800 whitespace-pre-wrap">{{ $ticket->admin_response }}</p>
                        @if($ticket->resolved_at)
                            <p class="text-xs text-green-600 mt-2">{{ __('Resolved') }}: {{ $ticket->resolved_at->format('M d, Y h:i A') }}</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        @if(auth()->user()->isAdmin() && $ticket->status !== 'closed')
            <form method="POST" action="{{ route('admin.help.respond', $ticket) }}" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mt-6">
                @csrf
                <h3 class="font-semibold text-gray-800 mb-3">✍️ {{ __('Admin Response') }}</h3>
                <textarea name="admin_response" rows="4" required placeholder="{{ __('Type your response...') }}" class="w-full rounded-lg border-gray-300 focus:ring-green-500 mb-3">{{ old('admin_response') }}</textarea>
                <div class="flex items-center gap-3">
                    <select name="status" required class="rounded-lg border-gray-300 focus:ring-green-500">
                        <option value="in_progress">{{ __('In Progress') }}</option>
                        <option value="resolved">{{ __('Resolved') }}</option>
                        <option value="closed">{{ __('Closed') }}</option>
                    </select>
                    <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition">{{ __('Send Response') }}</button>
                </div>
            </form>
        @endif
    </div>
</x-app-layout>
