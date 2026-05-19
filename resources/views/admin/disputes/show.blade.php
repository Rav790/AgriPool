<x-app-layout>
    <x-slot name="title">{{ __('Dispute') }} #{{ $dispute->id }}</x-slot>

    <div class="max-w-4xl mx-auto">
        <a href="{{ route('admin.disputes.index') }}" class="text-green-600 hover:underline text-sm mb-4 inline-block">← {{ __('Back to Disputes') }}</a>

        <div class="grid lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">{{ ucfirst(str_replace('_', ' ', $dispute->category)) }}</h1>
                            <p class="text-sm text-gray-500 mt-1">{{ __('Booking') }} #{{ $dispute->booking_id }} · {{ __('Filed by') }} {{ $dispute->user->name ?? '—' }}</p>
                        </div>
                        @php $sc = ['open'=>'bg-yellow-100 text-yellow-800','investigating'=>'bg-blue-100 text-blue-800','resolved'=>'bg-green-100 text-green-800','rejected'=>'bg-red-100 text-red-800']; @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $sc[$dispute->status] ?? '' }}">{{ ucfirst($dispute->status) }}</span>
                    </div>
                    <div class="mt-4 bg-gray-50 rounded-lg p-4">
                        <p class="whitespace-pre-wrap text-gray-700">{{ $dispute->description }}</p>
                    </div>
                </div>

                @if($dispute->resolution_notes)
                <div class="bg-green-50 rounded-xl border border-green-200 p-6">
                    <p class="text-sm font-semibold text-green-800 mb-2">✅ {{ __('Resolution') }}</p>
                    <p class="text-gray-700">{{ $dispute->resolution_notes }}</p>
                </div>
                @endif

                @if(!in_array($dispute->status, ['resolved', 'rejected']))
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="font-semibold text-gray-800 mb-3">⚖️ {{ __('Resolve Dispute') }}</h2>
                    <form method="POST" action="{{ route('admin.disputes.resolve', $dispute) }}">
                        @csrf
                        <textarea name="resolution_notes" rows="4" required placeholder="{{ __('Explain the resolution decision...') }}" class="w-full rounded-lg border-gray-300 focus:ring-green-500 mb-3">{{ old('resolution_notes') }}</textarea>
                        <div class="flex items-center gap-3">
                            <button type="submit" name="status" value="investigating" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">🔍 {{ __('Mark Investigating') }}</button>
                            <button type="submit" name="status" value="resolved" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700">✅ {{ __('Resolve') }}</button>
                            <button type="submit" name="status" value="rejected" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700">❌ {{ __('Reject') }}</button>
                        </div>
                    </form>
                </div>
                @endif
            </div>

            <div class="space-y-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <h3 class="font-semibold text-gray-800 mb-3">{{ __('Details') }}</h3>
                    <dl class="space-y-3 text-sm">
                        <div><dt class="text-gray-500">{{ __('Category') }}</dt><dd class="font-medium mt-0.5">{{ ucfirst(str_replace('_', ' ', $dispute->category)) }}</dd></div>
                        <div><dt class="text-gray-500">{{ __('Priority') }}</dt>
                            @php $pc = ['low'=>'🟢','medium'=>'🟡','high'=>'🟠','critical'=>'🔴']; @endphp
                            <dd class="font-medium mt-0.5">{{ $pc[$dispute->priority] ?? '' }} {{ ucfirst($dispute->priority) }}</dd>
                        </div>
                        <div><dt class="text-gray-500">{{ __('Filed') }}</dt><dd class="font-medium mt-0.5">{{ $dispute->created_at->format('M d, Y') }}</dd></div>
                        <div><dt class="text-gray-500">{{ __('Booking Value') }}</dt><dd class="font-medium mt-0.5">₹{{ number_format($dispute->booking->total_price ?? 0) }}</dd></div>
                    </dl>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <h3 class="font-semibold text-gray-800 mb-3">{{ __('Parties') }}</h3>
                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-green-100 text-green-700 rounded-full flex items-center justify-center text-xs font-bold">🌾</div>
                            <div><p class="text-sm font-medium">{{ $dispute->booking->farmer->name ?? '—' }}</p><p class="text-xs text-gray-400">{{ __('Farmer') }}</p></div>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-blue-100 text-blue-700 rounded-full flex items-center justify-center text-xs font-bold">🚛</div>
                            <div><p class="text-sm font-medium">{{ $dispute->booking->transporter->name ?? '—' }}</p><p class="text-xs text-gray-400">{{ __('Transporter') }}</p></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
