<x-app-layout>
    <x-slot name="title">{{ __('Markets') }}</x-slot>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('Markets') }}</h1>
            <a href="{{ route('admin.markets.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700">+ {{ __('Add Market') }}</a>
        </div>
    </x-slot>
    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-xl border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50"><tr>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Name') }}</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('City') }}</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('State') }}</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">{{ __('Type') }}</th>
                    </tr></thead>
                    <tbody class="divide-y">
                        @foreach($markets as $m)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium">{{ $m->name }}</td>
                            <td class="px-4 py-3">{{ $m->city }}</td>
                            <td class="px-4 py-3">{{ $m->state }}</td>
                            <td class="px-4 py-3 capitalize">{{ $m->type }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t">{{ $markets->links() }}</div>
        </div>
    </div>
</x-app-layout>
