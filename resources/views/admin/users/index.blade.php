<x-app-layout>
    <x-slot name="title">{{ __('User Management') }}</x-slot>
    <x-slot name="header"><h1 class="text-2xl font-bold text-gray-900">{{ __('User Management') }}</h1></x-slot>
    <div class="max-w-7xl mx-auto">
        <!-- Filters -->
        <form method="GET" class="bg-white rounded-xl border p-4 mb-6 flex flex-wrap gap-3 items-end">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">{{ __('Search') }}</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Name, email, phone...') }}" class="rounded-lg border-gray-300 text-sm w-60">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">{{ __('Role') }}</label>
                <select name="role" class="rounded-lg border-gray-300 text-sm">
                    <option value="">{{ __('All Roles') }}</option>
                    <option value="farmer" {{ request('role') === 'farmer' ? 'selected' : '' }}>{{ __('Farmer') }}</option>
                    <option value="transporter" {{ request('role') === 'transporter' ? 'selected' : '' }}>{{ __('Transporter') }}</option>
                    <option value="agent" {{ request('role') === 'agent' ? 'selected' : '' }}>{{ __('Agent') }}</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>{{ __('Admin') }}</option>
                </select>
            </div>
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700">{{ __('Filter') }}</button>
        </form>

        <div class="bg-white rounded-xl border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50"><tr>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Name') }}</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Email') }}</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Role') }}</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Status') }}</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('KYC') }}</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Joined') }}</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Actions') }}</th>
                    </tr></thead>
                    <tbody class="divide-y">
                        @foreach($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium">{{ $user->name }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $user->email }}</td>
                            <td class="px-4 py-3 capitalize">{{ $user->role }}</td>
                            <td class="px-4 py-3">
                                <span class="text-xs px-2 py-0.5 rounded-full {{ $user->is_verified ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ $user->is_verified ? __('Verified') : __('Unverified') }}</span>
                            </td>
                            <td class="px-4 py-3 font-medium text-xs">
                                <span class="{{ $user->kyc_status === 'pending' ? 'text-amber-600 font-bold bg-amber-50 px-2 py-0.5 rounded' : '' }}">
                                    {{ $user->kycBadge() }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-500">{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="px-4 py-3 space-x-1">
                                <a href="{{ route('admin.users.show', $user) }}" class="text-green-600 text-xs font-medium">{{ __('View') }}</a>
                                @if(!$user->is_verified)
                                <form action="{{ route('admin.users.verify', $user) }}" method="POST" class="inline">@csrf<button type="submit" class="text-blue-600 text-xs font-medium">{{ __('Verify') }}</button></form>
                                @else
                                <form action="{{ route('admin.users.suspend', $user) }}" method="POST" class="inline">@csrf<button type="submit" class="text-red-600 text-xs font-medium">{{ __('Suspend') }}</button></form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t">{{ $users->appends(request()->query())->links() }}</div>
        </div>
    </div>
</x-app-layout>
