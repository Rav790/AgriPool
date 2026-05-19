<x-app-layout>
    <x-slot name="title">{{ $market->name }}</x-slot>
    <x-slot name="header"><h1 class="text-2xl font-bold text-gray-900">{{ $market->name }}</h1></x-slot>
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl border p-6">
            <div class="grid grid-cols-2 gap-6">
                <div><p class="text-sm text-gray-500">{{ __('City') }}</p><p class="font-semibold mt-1">{{ $market->city }}</p></div>
                <div><p class="text-sm text-gray-500">{{ __('State') }}</p><p class="font-semibold mt-1">{{ $market->state }}</p></div>
                <div><p class="text-sm text-gray-500">{{ __('Type') }}</p><p class="font-semibold mt-1 capitalize">{{ $market->type }}</p></div>
                <div><p class="text-sm text-gray-500">{{ __('Location') }}</p><p class="font-semibold mt-1">{{ $market->location ?? '—' }}</p></div>
            </div>
        </div>
    </div>
</x-app-layout>
