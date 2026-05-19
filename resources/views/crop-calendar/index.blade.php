<x-app-layout>
    <x-slot name="title">{{ __('Seasonal Crop Calendar') }}</x-slot>

    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900">📅 {{ __('Seasonal Crop Calendar') }}</h1>
            <p class="text-gray-500 mt-2">{{ __('Plan your transport around India\'s growing and harvesting seasons') }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-3 text-left font-semibold text-gray-700 sticky left-0 bg-gray-50 z-10 min-w-[120px]">{{ __('Crop') }}</th>
                            @foreach(['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'] as $m)
                                <th class="px-2 py-3 text-center font-medium text-gray-500 min-w-[60px] {{ $m === date('M') ? 'bg-green-100 text-green-800 font-bold' : '' }}">{{ $m }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @php
                            $crops = [
                                ['name' => 'Rice (Kharif)', 'icon' => '🌾', 'sow' => [6,7], 'grow' => [8,9], 'harvest' => [10,11], 'transport' => [11,12]],
                                ['name' => 'Wheat (Rabi)', 'icon' => '🌾', 'sow' => [11,12], 'grow' => [1,2], 'harvest' => [3,4], 'transport' => [4,5]],
                                ['name' => 'Maize', 'icon' => '🌽', 'sow' => [6,7], 'grow' => [8,9], 'harvest' => [9,10], 'transport' => [10,11]],
                                ['name' => 'Cotton', 'icon' => '☁️', 'sow' => [4,5], 'grow' => [6,7,8], 'harvest' => [10,11,12], 'transport' => [11,12,1]],
                                ['name' => 'Sugarcane', 'icon' => '🎋', 'sow' => [1,2,3], 'grow' => [4,5,6,7,8,9], 'harvest' => [11,12,1,2], 'transport' => [12,1,2,3]],
                                ['name' => 'Soybean', 'icon' => '🫘', 'sow' => [6,7], 'grow' => [7,8,9], 'harvest' => [10,11], 'transport' => [11,12]],
                                ['name' => 'Onion', 'icon' => '🧅', 'sow' => [9,10], 'grow' => [11,12,1], 'harvest' => [1,2,3], 'transport' => [2,3,4]],
                                ['name' => 'Potato', 'icon' => '🥔', 'sow' => [10,11], 'grow' => [11,12,1], 'harvest' => [1,2,3], 'transport' => [2,3]],
                                ['name' => 'Tomato', 'icon' => '🍅', 'sow' => [7,8,9], 'grow' => [9,10,11], 'harvest' => [11,12,1,2], 'transport' => [12,1,2]],
                                ['name' => 'Mustard', 'icon' => '🌼', 'sow' => [10,11], 'grow' => [11,12,1], 'harvest' => [2,3], 'transport' => [3,4]],
                            ];
                        @endphp
                        @foreach($crops as $crop)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium text-gray-900 sticky left-0 bg-white z-10">
                                    <span class="mr-1">{{ $crop['icon'] }}</span> {{ $crop['name'] }}
                                </td>
                                @for($m = 1; $m <= 12; $m++)
                                    <td class="px-2 py-3 text-center {{ $m == date('n') ? 'border-l-2 border-r-2 border-green-300' : '' }}">
                                        @if(in_array($m, $crop['sow']))
                                            <span class="inline-block w-8 h-6 rounded bg-yellow-200 text-yellow-800 text-xs leading-6" title="{{ __('Sowing') }}">🌱</span>
                                        @elseif(in_array($m, $crop['grow']))
                                            <span class="inline-block w-8 h-6 rounded bg-green-200 text-green-800 text-xs leading-6" title="{{ __('Growing') }}">🌿</span>
                                        @elseif(in_array($m, $crop['harvest']))
                                            <span class="inline-block w-8 h-6 rounded bg-amber-200 text-amber-800 text-xs leading-6" title="{{ __('Harvest') }}">🌾</span>
                                        @elseif(in_array($m, $crop['transport']))
                                            <span class="inline-block w-8 h-6 rounded bg-blue-200 text-blue-800 text-xs leading-6" title="{{ __('Transport Peak') }}">🚛</span>
                                        @endif
                                    </td>
                                @endfor
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Legend -->
        <div class="mt-4 flex flex-wrap gap-4 justify-center text-sm">
            <span class="flex items-center gap-1"><span class="w-4 h-4 rounded bg-yellow-200 inline-block"></span> 🌱 {{ __('Sowing Season') }}</span>
            <span class="flex items-center gap-1"><span class="w-4 h-4 rounded bg-green-200 inline-block"></span> 🌿 {{ __('Growing Phase') }}</span>
            <span class="flex items-center gap-1"><span class="w-4 h-4 rounded bg-amber-200 inline-block"></span> 🌾 {{ __('Harvest Time') }}</span>
            <span class="flex items-center gap-1"><span class="w-4 h-4 rounded bg-blue-200 inline-block"></span> 🚛 {{ __('Transport Peak') }}</span>
            <span class="flex items-center gap-1"><span class="w-1 h-4 rounded bg-green-400 inline-block"></span> {{ __('Current Month') }}</span>
        </div>

        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-4 text-sm text-blue-800">
            💡 <strong>{{ __('Pro Tip:') }}</strong> {{ __('Book transport 1-2 weeks before harvest season to get the best rates. During peak transport months (🚛), prices increase by 20-40%.') }}
        </div>
    </div>
</x-app-layout>
