<x-app-layout>
    <x-slot name="title">{{ __('User Details') }} - {{ $user->name }}</x-slot>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('admin.users.index') }}" class="text-sm text-green-600 hover:underline mb-1 inline-block">← {{ __('Back to Users') }}</a>
                <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
            </div>
            <div class="flex items-center gap-2">
                @if(!$user->is_verified)
                    <form action="{{ route('admin.users.verify', $user) }}" method="POST">
                        @csrf
                        <button class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-green-700 transition shadow-sm">
                            {{ __('Verify Account') }}
                        </button>
                    </form>
                @else
                    <form action="{{ route('admin.users.suspend', $user) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to suspend this user?') }}')">
                        @csrf
                        <button class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-red-700 transition shadow-sm">
                            {{ __('Suspend Account') }}
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    @php
        $userBookings = $user->farmerBookings->merge($user->transporterBookings)->sortByDesc('created_at');
    @endphp

    <div class="max-w-6xl mx-auto space-y-6">
        <!-- Overview Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('Role') }}</p>
                <p class="text-lg font-bold text-gray-900 mt-1 capitalize">{{ $user->role }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ __('Joined') }} {{ $user->created_at->format('M d, Y') }}</p>
            </div>
            
            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('Account Status') }}</p>
                <div class="mt-1">
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $user->is_verified ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $user->is_verified ? __('Verified') : __('Suspended / Unverified') }}
                    </span>
                </div>
                <p class="text-xs text-gray-500 mt-1.5">{{ __('Trust Score:') }} {{ $user->trustScore() }}%</p>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('KYC Status') }}</p>
                <p class="text-sm font-bold text-gray-900 mt-1">{{ $user->kycBadge() }}</p>
                <p class="text-xs text-gray-500 mt-1 capitalize">{{ str_replace('_', ' ', $user->kyc_status) }}</p>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('Total Bookings') }}</p>
                <p class="text-2xl font-extrabold text-green-600 mt-0.5">{{ $userBookings->count() }}</p>
                <p class="text-xs text-gray-500">{{ __('Lifetime orders') }}</p>
            </div>
        </div>

        <!-- Contact & Profile Fields -->
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <h2 class="text-base font-bold text-gray-900 mb-4">{{ __('Contact Information') }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <p class="text-xs text-gray-400 font-medium">{{ __('Email Address') }}</p>
                    <p class="font-bold text-gray-800 mt-0.5">{{ $user->email }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-medium">{{ __('Phone Number') }}</p>
                    <p class="font-bold text-gray-800 mt-0.5">{{ $user->phone ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-medium">{{ __('Average Rating') }}</p>
                    <div class="mt-0.5 flex items-center gap-1.5">
                        <x-rating-stars :rating="$user->averageRating()" />
                        <span class="text-xs text-gray-500">({{ $user->reviewsReceived->count() }})</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed KYC Submission Details -->
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h2 class="font-bold text-gray-900">📋 {{ __('KYC Details & Verification') }}</h2>
                @if($user->kyc_status === 'pending')
                    <span class="animate-pulse bg-amber-100 text-amber-800 text-xs font-bold px-2.5 py-1 rounded-full">
                        {{ __('Action Required') }}
                    </span>
                @endif
            </div>

            <div class="p-6">
                @if($user->kyc_status !== 'not_submitted' || $user->aadhaar_number || $user->pan_number || $user->bank_account_number)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Identity Docs -->
                        <div class="space-y-4">
                            <h3 class="text-xs font-bold text-green-700 uppercase tracking-wider">{{ __('Identity Documents') }}</h3>
                            
                            <div class="bg-gray-50 p-3.5 rounded-xl border border-gray-100">
                                <p class="text-xs text-gray-500 font-medium">{{ __('Aadhaar Number') }}</p>
                                <p class="font-mono font-bold text-gray-900 tracking-wider mt-0.5">{{ $user->aadhaar_number ?? '—' }}</p>
                                @if($user->aadhaar_document)
                                    <a href="{{ asset('storage/' . $user->aadhaar_document) }}" target="_blank" class="text-xs text-green-600 font-bold hover:underline mt-2 inline-flex items-center gap-1 break-all">
                                        📎 {{ __('View Aadhaar Document') }}
                                    </a>
                                @endif
                            </div>

                            <div class="bg-gray-50 p-3.5 rounded-xl border border-gray-100">
                                <p class="text-xs text-gray-500 font-medium">{{ __('PAN Number') }}</p>
                                <p class="font-mono font-bold text-gray-900 tracking-wider mt-0.5">{{ $user->pan_number ?? '—' }}</p>
                                @if($user->pan_document)
                                    <a href="{{ asset('storage/' . $user->pan_document) }}" target="_blank" class="text-xs text-green-600 font-bold hover:underline mt-2 inline-flex items-center gap-1 break-all">
                                        📎 {{ __('View PAN Document') }}
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Banking & Address Details -->
                        <div class="space-y-4">
                            <h3 class="text-xs font-bold text-green-700 uppercase tracking-wider">{{ __('Banking & Location') }}</h3>
                            
                            <div class="bg-gray-50 p-3.5 rounded-xl border border-gray-100 space-y-2">
                                <div>
                                    <p class="text-[11px] text-gray-400 font-medium">{{ __('Bank Name') }}</p>
                                    <p class="text-xs font-bold text-gray-800">{{ $user->bank_name ?? '—' }}</p>
                                </div>
                                <div class="grid grid-cols-2 gap-2 pt-1 border-t border-gray-200/60">
                                    <div>
                                        <p class="text-[11px] text-gray-400 font-medium">{{ __('Account Number') }}</p>
                                        <p class="text-xs font-mono font-bold text-gray-800">{{ $user->bank_account_number ?? '—' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-[11px] text-gray-400 font-medium">{{ __('IFSC Code') }}</p>
                                        <p class="text-xs font-mono font-bold text-gray-800">{{ $user->bank_ifsc ?? '—' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 p-3.5 rounded-xl border border-gray-100">
                                <p class="text-xs text-gray-500 font-medium">{{ __('Full Address') }}</p>
                                <p class="text-xs font-bold text-gray-800 mt-1">
                                    {{ $user->address ?? '—' }}<br>
                                    {{ collect([$user->city, $user->state, $user->pincode])->filter()->join(', ') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- KYC Actions -->
                    <div class="mt-8 pt-5 border-t border-gray-200 flex items-center justify-end gap-3">
                        @if($user->kyc_status !== 'verified')
                            <form action="{{ route('admin.users.verify-kyc', $user) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-5 py-2.5 bg-green-600 text-white rounded-xl font-bold text-sm hover:bg-green-700 transition flex items-center gap-1.5 shadow-sm">
                                    ✅ {{ __('Verify KYC Details') }}
                                </button>
                            </form>
                        @endif

                        @if($user->kyc_status !== 'rejected')
                            <form action="{{ route('admin.users.reject-kyc', $user) }}" method="POST" onsubmit="return confirm('{{ __('Reject these KYC details?') }}')">
                                @csrf
                                <button type="submit" class="px-5 py-2.5 bg-red-50 text-red-700 border border-red-200 rounded-xl font-bold text-sm hover:bg-red-100 transition flex items-center gap-1.5">
                                    ❌ {{ __('Reject KYC') }}
                                </button>
                            </form>
                        @endif
                    </div>
                @else
                    <div class="py-8 text-center text-gray-400">
                        <p class="text-3xl mb-2">📋</p>
                        <p class="text-sm">{{ __('This user has not submitted any KYC documents yet.') }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Bookings Associated With User -->
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h2 class="font-bold text-gray-900">📦 {{ __('Bookings & Orders History') }} ({{ $userBookings->count() }})</h2>
            </div>

            @if($userBookings->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="border-b border-gray-200 text-xs font-bold text-gray-400 uppercase bg-gray-50/50">
                                <th class="py-3 px-6">{{ __('Booking ID') }}</th>
                                <th class="py-3 px-6">{{ __('Role Context') }}</th>
                                <th class="py-3 px-6">{{ __('Crop / Route') }}</th>
                                <th class="py-3 px-6">{{ __('Total Price') }}</th>
                                <th class="py-3 px-6">{{ __('Status') }}</th>
                                <th class="py-3 px-6">{{ __('Created Date') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($userBookings as $booking)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-3 px-6 font-mono font-bold text-gray-900">
                                        #{{ $booking->id }}
                                    </td>
                                    <td class="py-3 px-6">
                                        @if($booking->farmer_id === $user->id)
                                            <span class="text-xs bg-amber-50 text-amber-800 font-bold px-2 py-0.5 rounded">🧑‍🌾 {{ __('Ordered as Farmer') }}</span>
                                        @else
                                            <span class="text-xs bg-blue-50 text-blue-800 font-bold px-2 py-0.5 rounded">🚛 {{ __('Accepted as Transporter') }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-6 text-gray-800 font-medium">
                                        {{ $booking->transportRequest->crop_type ?? '—' }}<br>
                                        <span class="text-xs text-gray-400">
                                            {{ \Illuminate\Support\Str::limit($booking->transportRequest->pickup_address ?? ($booking->transportListing->route_from ?? ''), 25) }} →
                                            {{ \Illuminate\Support\Str::limit($booking->transportRequest->delivery_address ?? ($booking->transportListing->route_to ?? ''), 25) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-6 font-bold text-gray-900">
                                        ₹{{ number_format($booking->total_price) }}
                                    </td>
                                    <td class="py-3 px-6">
                                        <span class="px-2 py-0.5 rounded-full text-xs font-bold
                                            {{ $booking->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                              ($booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800') }}">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-6 text-xs text-gray-500">
                                        {{ $booking->created_at->format('M d, Y') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-12 text-center text-gray-400">
                    <p class="text-sm">{{ __('No bookings or orders found for this user.') }}</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
