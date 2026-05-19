<?php

namespace Database\Seeders;

use App\Models\Market;
use App\Models\MarketPrice;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MarketPriceSeeder extends Seeder
{
    public function run(): void
    {
        $crops = [
            'Rice' => [1800, 2200],
            'Wheat' => [2000, 2500],
            'Tomato' => [800, 2500],
            'Onion' => [1000, 3000],
            'Potato' => [600, 1500],
            'Soybean' => [3500, 4500],
            'Cotton' => [5000, 6500],
            'Sugarcane' => [280, 350],
            'Maize' => [1500, 2000],
            'Mustard' => [4000, 5500],
        ];

        $agents = User::where('role', 'agent')->pluck('id')->toArray();
        $markets = Market::all();

        foreach ($markets as $market) {
            foreach ($crops as $crop => [$min, $max]) {
                // Generate 30 days of price data
                for ($day = 30; $day >= 0; $day--) {
                    $basePrice = rand($min, $max);
                    // Add some daily variance (±5%)
                    $variance = $basePrice * (rand(-5, 5) / 100);

                    MarketPrice::create([
                        'market_id' => $market->id,
                        'crop_type' => $crop,
                        'price_per_quintal' => round($basePrice + $variance, 2),
                        'recorded_date' => Carbon::today()->subDays($day),
                        'recorded_by' => $agents[array_rand($agents)] ?? null,
                    ]);
                }
            }
        }
    }
}
