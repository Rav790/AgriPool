<x-app-layout>
    <x-slot name="title">{{ __('Help Tickets') }}</x-slot>

    <div class="max-w-6xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">🎫 {{ __('Support Tickets') }}</h1>
                <p class="text-gray-500 mt-1">{{ __('Manage all user support requests') }}</p>
            </div>
            <div class="flex gap-2">
                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">{{ $tickets->where('status', 'open')->count() }} {{ __('Open') }}</span>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">{{ $tickets->where('status', 'in_progress')->count() }} {{ __('In Progress') }}</span>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('ID') }}</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('User') }}</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Subject') }}</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Category') }}</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Priority') }}</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Status') }}</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Date') }}</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($tickets as $ticket)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-mono text-xs text-gray-400">#{{ $ticket->id }}</td>
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-900">{{ $ticket->user->name ?? '—' }}</p>
                            <p class="text-xs text-gray-400 capitalize">{{ $ticket->user->role ?? '' }}</p>
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-800 max-w-[200px] truncate">{{ $ticket->subject }}</td>
                        <td class="px-4 py-3"><span class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded text-xs">{{ ucfirst($ticket->category ?? 'general') }}</span></td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 rounded text-xs font-medium {{ ($ticket->priority ?? 'medium') === 'high' ? 'bg-red-100 text-red-700' : (($ticket->priority ?? 'medium') === 'medium' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700') }}">
                                {{ ucfirst($ticket->priority ?? 'medium') }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $ticket->status === 'open' ? 'bg-yellow-100 text-yellow-800' : ($ticket->status === 'resolved' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800') }}">
                                {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-500 text-xs">{{ $ticket->created_at->format('M d, h:i A') }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.help.show', $ticket) }}" class="text-green-600 hover:text-green-800 text-sm font-medium">{{ __('View') }}</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="px-4 py-12 text-center text-gray-400">{{ __('No support tickets yet.') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
            @if($tickets->hasPages())
            <div class="px-4 py-3 border-t border-gray-200">{{ $tickets->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
