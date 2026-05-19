<x-app-layout>
    <x-slot name="title">{{ __('Disputes') }}</x-slot>

    <div class="max-w-6xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">⚖️ {{ __('Dispute Management') }}</h1>
                <p class="text-gray-500 mt-1">{{ __('Review and resolve booking disputes') }}</p>
            </div>
            <div class="flex gap-2">
                <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">{{ $disputes->where('status', 'open')->count() }} {{ __('Open') }}</span>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">{{ $disputes->where('status', 'investigating')->count() }} {{ __('Investigating') }}</span>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('ID') }}</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Filed By') }}</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Booking') }}</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Category') }}</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Priority') }}</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Status') }}</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Date') }}</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($disputes as $dispute)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-mono text-xs text-gray-400">#{{ $dispute->id }}</td>
                        <td class="px-4 py-3 font-medium text-gray-900">{{ $dispute->user->name ?? '—' }}</td>
                        <td class="px-4 py-3"><span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded">#{{ $dispute->booking_id }}</span></td>
                        <td class="px-4 py-3 text-gray-700">{{ ucfirst(str_replace('_', ' ', $dispute->category)) }}</td>
                        <td class="px-4 py-3">
                            @php $pc = ['low'=>'bg-green-100 text-green-700','medium'=>'bg-yellow-100 text-yellow-700','high'=>'bg-orange-100 text-orange-700','critical'=>'bg-red-100 text-red-700']; @endphp
                            <span class="px-2 py-0.5 rounded text-xs font-medium {{ $pc[$dispute->priority] ?? 'bg-gray-100 text-gray-700' }}">{{ ucfirst($dispute->priority) }}</span>
                        </td>
                        <td class="px-4 py-3">
                            @php $sc = ['open'=>'bg-yellow-100 text-yellow-800','investigating'=>'bg-blue-100 text-blue-800','resolved'=>'bg-green-100 text-green-800','rejected'=>'bg-red-100 text-red-800']; @endphp
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $sc[$dispute->status] ?? 'bg-gray-100 text-gray-700' }}">{{ ucfirst($dispute->status) }}</span>
                        </td>
                        <td class="px-4 py-3 text-gray-500 text-xs">{{ $dispute->created_at->diffForHumans() }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.disputes.show', $dispute) }}" class="text-green-600 hover:text-green-800 text-sm font-medium">{{ __('Review') }}</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="px-4 py-12 text-center text-gray-400">✅ {{ __('No disputes. Everything is running smoothly!') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
            @if($disputes->hasPages())
            <div class="px-4 py-3 border-t border-gray-200">{{ $disputes->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
