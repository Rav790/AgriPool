@props(['title', 'value', 'icon' => null, 'color' => 'green', 'prefix' => ''])

@php
$colors = [
    'green' => 'bg-green-50 text-green-600 border-green-100',
    'amber' => 'bg-amber-50 text-amber-600 border-amber-100',
    'blue' => 'bg-blue-50 text-blue-600 border-blue-100',
    'red' => 'bg-red-50 text-red-600 border-red-100',
    'purple' => 'bg-purple-50 text-purple-600 border-purple-100',
    'indigo' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
];
$colorClass = $colors[$color] ?? $colors['green'];
@endphp

<div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition-shadow duration-200">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500">{{ $title }}</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $prefix }}{{ $value }}</p>
        </div>
        @if($icon)
        <div class="w-12 h-12 rounded-lg {{ $colorClass }} border flex items-center justify-center">
            {!! $icon !!}
        </div>
        @endif
    </div>
    @if(isset($footer))
        <div class="mt-3 pt-3 border-t border-gray-100 text-sm text-gray-500">
            {{ $footer }}
        </div>
    @endif
</div>
