<?php

namespace Database\Seeders;

use App\Models\TransportListing;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TransportListingSeeder extends Seeder
{
    public function run(): void
    {
        $transporters = User::where('role', 'transporter')->with('transporterProfile')->get();

        $routes = [
            ['from' => 'Najafgarh, Delhi', 'to' => 'Azadpur Mandi, Delhi', 'from_lat' => 28.6139, 'from_lng' => 77.2090, 'to_lat' => 28.7041, 'to_lng' => 77.1025],
            ['from' => 'Sanganer, Jaipur', 'to' => 'Muhana Mandi, Jaipur', 'from_lat' => 26.9124, 'from_lng' => 75.7873, 'to_lat' => 26.7867, 'to_lng' => 75.8286],
            ['from' => 'Sanand, Ahmedabad', 'to' => 'Jamalpur Mandi, Ahmedabad', 'from_lat' => 23.0225, 'from_lng' => 72.5714, 'to_lat' => 23.0258, 'to_lng' => 72.5873],
            ['from' => 'Virar, Mumbai', 'to' => 'Vashi APMC, Navi Mumbai', 'from_lat' => 19.0760, 'from_lng' => 72.8777, 'to_lat' => 19.0771, 'to_lng' => 72.9987],
            ['from' => 'Dharwad, Karnataka', 'to' => 'Yeshwanthpur APMC, Bengaluru', 'from_lat' => 15.3173, 'from_lng' => 75.7139, 'to_lat' => 13.0220, 'to_lng' => 77.5440],
            ['from' => 'Medak, Telangana', 'to' => 'Gaddiannaram, Hyderabad', 'from_lat' => 17.3850, 'from_lng' => 78.4867, 'to_lat' => 17.3503, 'to_lng' => 78.5242],
            ['from' => 'Kanchipuram, TN', 'to' => 'Koyambedu Market, Chennai', 'from_lat' => 13.0827, 'from_lng' => 80.2707, 'to_lat' => 13.0694, 'to_lng' => 80.1948],
            ['from' => 'Barasat, West Bengal', 'to' => 'Bowbazar, Kolkata', 'from_lat' => 22.5726, 'from_lng' => 88.3639, 'to_lat' => 22.5697, 'to_lng' => 88.3591],
            ['from' => 'Unnao, UP', 'to' => 'Lucknow Mandi, Lucknow', 'from_lat' => 26.8467, 'from_lng' => 80.9462, 'to_lat' => 26.8467, 'to_lng' => 80.9462],
            ['from' => 'Najafgarh, Delhi', 'to' => 'Chawri Bazaar, Delhi', 'from_lat' => 28.6139, 'from_lng' => 77.2090, 'to_lat' => 28.6507, 'to_lng' => 77.2334],
        ];

        $statuses = ['available', 'available', 'available', 'partially_booked', 'completed'];

        foreach ($transporters as $transporter) {
            $capacity = $transporter->transporterProfile->capacity_tons ?? 5;

            for ($i = 0; $i < 2; $i++) {
                $route = $routes[array_rand($routes)];
                $status = $statuses[array_rand($statuses)];
                $remaining = $status === 'completed' ? 0 : rand(1, (int)($capacity * 10)) / 10;
                $remaining = min($remaining, $capacity);

                if ($status === 'partially_booked') {
                    $remaining = $capacity * rand(2, 7) / 10;
                }

                TransportListing::create([
                    'transporter_id' => $transporter->id,
                    'route_from' => $route['from'],
                    'route_to' => $route['to'],
                    'route_from_lat' => $route['from_lat'],
                    'route_from_lng' => $route['from_lng'],
                    'route_to_lat' => $route['to_lat'],
                    'route_to_lng' => $route['to_lng'],
                    'available_date' => Carbon::today()->addDays(rand(-5, 10)),
                    'total_capacity' => $capacity,
                    'remaining_capacity' => $remaining,
                    'price_per_ton' => rand(500, 3000),
                    'status' => $status,
                ]);
            }
        }
    }
}
