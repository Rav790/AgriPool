@props(['status'])

@php
$styles = [
    'pending' => 'bg-yellow-100 text-yellow-800',
    'confirmed' => 'bg-blue-100 text-blue-800',
    'matched' => 'bg-indigo-100 text-indigo-800',
    'booked' => 'bg-purple-100 text-purple-800',
    'picked_up' => 'bg-cyan-100 text-cyan-800',
    'in_transit' => 'bg-amber-100 text-amber-800',
    'delivered' => 'bg-green-100 text-green-800',
    'cancelled' => 'bg-red-100 text-red-800',
    'disputed' => 'bg-red-100 text-red-800',
    'available' => 'bg-green-100 text-green-800',
    'partially_booked' => 'bg-amber-100 text-amber-800',
    'fully_booked' => 'bg-blue-100 text-blue-800',
    'completed' => 'bg-green-100 text-green-800',
    'unpaid' => 'bg-gray-100 text-gray-800',
    'held' => 'bg-amber-100 text-amber-800',
    'released' => 'bg-green-100 text-green-800',
    'refunded' => 'bg-blue-100 text-blue-800',
];
$style = $styles[$status] ?? 'bg-gray-100 text-gray-800';
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $style }}">
    {{ __(ucwords(str_replace('_', ' ', $status))) }}
</span>
