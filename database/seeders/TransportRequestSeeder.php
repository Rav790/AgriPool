<?php

namespace Database\Seeders;

use App\Models\Market;
use App\Models\TransportRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TransportRequestSeeder extends Seeder
{
    public function run(): void
    {
        $farmers = User::where('role', 'farmer')->with('farmerProfile')->get();
        $markets = Market::all();

        $crops = ['Rice', 'Wheat', 'Tomato', 'Onion', 'Potato', 'Soybean', 'Cotton', 'Sugarcane', 'Maize', 'Mustard'];
        $perishable = ['Tomato', 'Onion', 'Potato'];
        $packaging = ['sacks', 'crates', 'loose', 'boxes'];
        $statuses = ['pending', 'pending', 'matched', 'booked', 'delivered'];

        foreach ($farmers as $farmer) {
            $profile = $farmer->farmerProfile;
            if (!$profile) continue;

            $numRequests = rand(1, 3);
            for ($i = 0; $i < $numRequests; $i++) {
                $crop = $crops[array_rand($crops)];
                $market = $markets->random();

                TransportRequest::create([
                    'farmer_id' => $farmer->id,
                    'crop_type' => $crop,
                    'quantity_tons' => rand(1, 10) * 0.5,
                    'packaging_type' => $packaging[array_rand($packaging)],
                    'pickup_lat' => $profile->farm_location_lat,
                    'pickup_lng' => $profile->farm_location_lng,
                    'pickup_address' => $profile->farm_address,
                    'destination_market_id' => $market->id,
                    'required_date' => Carbon::today()->addDays(rand(0, 14)),
                    'is_perishable' => in_array($crop, $perishable),
                    'status' => $statuses[array_rand($statuses)],
                    'special_instructions' => rand(0, 1) ? 'Handle with care, fragile produce' : null,
                ]);
            }
        }
    }
}
