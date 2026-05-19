<x-app-layout>
    <x-slot name="title">{{ __('Invoice') }} #{{ $booking->id }}</x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <a href="{{ url()->previous() }}" class="text-green-600 hover:underline text-sm">← {{ __('Back') }}</a>
            <button onclick="window.print()" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition flex items-center gap-2 print:hidden">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                {{ __('Print Invoice') }}
            </button>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8" id="invoice">
            <!-- Header -->
            <div class="flex items-start justify-between border-b border-gray-200 pb-6 mb-6">
                <div>
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">AgriPool</h1>
                            <p class="text-xs text-gray-500">{{ __('Transport Sharing Platform') }}</p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-3">AgriPool Technologies Pvt. Ltd.</p>
                    <p class="text-xs text-gray-400">Sector 62, Noida, UP 201301</p>
                </div>
                <div class="text-right">
                    <h2 class="text-2xl font-bold text-gray-300">{{ __('INVOICE') }}</h2>
                    <p class="text-sm font-medium text-gray-700 mt-2">#AGP-{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ __('Date') }}: {{ ($booking->delivery_confirmed_at ?? $booking->created_at)->format('M d, Y') }}</p>
                </div>
            </div>

            <!-- Parties -->
            <div class="grid grid-cols-2 gap-8 mb-8">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">{{ __('From (Farmer)') }}</p>
                    <p class="font-semibold text-gray-900">{{ $booking->farmer->name ?? '—' }}</p>
                    <p class="text-sm text-gray-500">{{ $booking->farmer->email ?? '' }}</p>
                    <p class="text-sm text-gray-500">{{ $booking->farmer->phone ?? '' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">{{ __('Transport Provider') }}</p>
                    <p class="font-semibold text-gray-900">{{ $booking->transporter->name ?? '—' }}</p>
                    <p class="text-sm text-gray-500">{{ $booking->transporter->email ?? '' }}</p>
                    <p class="text-sm text-gray-500">{{ $booking->transporter->phone ?? '' }}</p>
                </div>
            </div>

            <!-- Line Items -->
            <table class="w-full text-sm mb-8">
                <thead>
                    <tr class="border-b-2 border-gray-200">
                        <th class="text-left pb-3 font-semibold text-gray-700">{{ __('Description') }}</th>
                        <th class="text-right pb-3 font-semibold text-gray-700">{{ __('Qty (tons)') }}</th>
                        <th class="text-right pb-3 font-semibold text-gray-700">{{ __('Rate') }}</th>
                        <th class="text-right pb-3 font-semibold text-gray-700">{{ __('Amount') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr>
                        <td class="py-3">
                            <p class="font-medium text-gray-900">{{ $booking->transportRequest->crop_type ?? __('Transport Service') }}</p>
                            <p class="text-xs text-gray-500">{{ $booking->transportListing->route_from ?? '' }} → {{ $booking->transportListing->route_to ?? '' }}</p>
                        </td>
                        <td class="py-3 text-right text-gray-700">{{ number_format($booking->allocated_tons, 2) }}</td>
                        <td class="py-3 text-right text-gray-700">₹{{ number_format($booking->transportListing->price_per_ton ?? 0) }}/ton</td>
                        <td class="py-3 text-right font-medium text-gray-900">₹{{ number_format($booking->total_price, 2) }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Totals -->
            <div class="flex justify-end">
                <div class="w-64 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">{{ __('Subtotal') }}</span>
                        <span class="font-medium">₹{{ number_format($booking->total_price, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">{{ __('Platform Fee (5%)') }}</span>
                        <span class="font-medium">₹{{ number_format($booking->total_price * 0.05, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">{{ __('GST (18%)') }}</span>
                        <span class="font-medium">₹{{ number_format($booking->total_price * 0.05 * 0.18, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-base font-bold border-t-2 border-gray-900 pt-2">
                        <span>{{ __('Total') }}</span>
                        <span class="text-green-600">₹{{ number_format($booking->total_price + ($booking->total_price * 0.05 * 1.18), 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Payment Status -->
            <div class="mt-8 p-4 rounded-lg {{ $booking->payment_status === 'released' ? 'bg-green-50 border border-green-200' : 'bg-yellow-50 border border-yellow-200' }}">
                <div class="flex items-center gap-2">
                    <span class="text-xl">{{ $booking->payment_status === 'released' ? '✅' : '⏳' }}</span>
                    <div>
                        <p class="font-semibold {{ $booking->payment_status === 'released' ? 'text-green-800' : 'text-yellow-800' }}">
                            {{ $booking->payment_status === 'released' ? __('Payment Released') : __('Payment in Escrow') }}
                        </p>
                        <p class="text-xs {{ $booking->payment_status === 'released' ? 'text-green-600' : 'text-yellow-600' }}">
                            {{ $booking->payment_status === 'released' ? __('Funds have been released to the transporter.') : __('Funds are held securely until delivery is confirmed.') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-8 pt-6 border-t border-gray-200 text-center text-xs text-gray-400">
                <p>{{ __('Thank you for using AgriPool!') }}</p>
                <p class="mt-1">{{ __('For queries, contact support@agripool.in') }}</p>
                <p class="mt-1 font-mono">{{ __('Invoice ID') }}: AGP-{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}-{{ strtoupper(substr(md5($booking->id . $booking->created_at), 0, 6)) }}</p>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        @media print {
            nav, aside, header, .print\:hidden { display: none !important; }
            .max-w-3xl { max-width: 100% !important; }
            body { background: white !important; }
            #invoice { box-shadow: none !important; border: none !important; }
        }
    </style>
    @endpush
</x-app-layout>
