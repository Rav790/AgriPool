<?php

namespace Database\Seeders;

use App\Models\FarmerProfile;
use App\Models\TransporterProfile;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin ────────────────────────────────────────────
        $admin = User::create([
            'name' => 'Admin User',
            'phone' => '9000000001',
            'email' => 'admin@agripool.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_verified' => true,
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');
        Wallet::create(['user_id' => $admin->id, 'balance' => 0]);

        // ── Farmers (10) ─────────────────────────────────────
        $farmerNames = [
            'Ramesh Kumar', 'Suresh Patel', 'Lakshmi Devi',
            'Harish Yadav', 'Priya Sharma', 'Gopal Singh',
            'Meena Kumari', 'Rajesh Verma', 'Anita Joshi', 'Vijay Reddy',
        ];

        $indianCities = [
            ['lat' => 28.6139, 'lng' => 77.2090, 'address' => 'Najafgarh, Delhi'],
            ['lat' => 26.9124, 'lng' => 75.7873, 'address' => 'Sanganer, Jaipur, Rajasthan'],
            ['lat' => 25.3176, 'lng' => 82.9739, 'address' => 'Kashi, Varanasi, UP'],
            ['lat' => 23.0225, 'lng' => 72.5714, 'address' => 'Sanand, Ahmedabad, Gujarat'],
            ['lat' => 19.0760, 'lng' => 72.8777, 'address' => 'Virar, Mumbai, Maharashtra'],
            ['lat' => 15.3173, 'lng' => 75.7139, 'address' => 'Dharwad, Karnataka'],
            ['lat' => 17.3850, 'lng' => 78.4867, 'address' => 'Medak, Telangana'],
            ['lat' => 13.0827, 'lng' => 80.2707, 'address' => 'Kanchipuram, Tamil Nadu'],
            ['lat' => 22.5726, 'lng' => 88.3639, 'address' => 'Barasat, West Bengal'],
            ['lat' => 26.8467, 'lng' => 80.9462, 'address' => 'Unnao, Lucknow, UP'],
        ];

        foreach ($farmerNames as $i => $name) {
            $user = User::create([
                'name' => $name,
                'phone' => '98000' . str_pad($i + 10, 5, '0', STR_PAD_LEFT),
                'email' => strtolower(str_replace(' ', '.', $name)) . '@example.com',
                'password' => Hash::make('password'),
                'role' => 'farmer',
                'is_verified' => true,
                'email_verified_at' => now(),
            ]);
            $user->assignRole('farmer');

            FarmerProfile::create([
                'user_id' => $user->id,
                'farm_location_lat' => $indianCities[$i]['lat'],
                'farm_location_lng' => $indianCities[$i]['lng'],
                'farm_address' => $indianCities[$i]['address'],
                'total_land_acres' => rand(2, 50),
            ]);

            Wallet::create(['user_id' => $user->id, 'balance' => rand(500, 10000)]);
        }

        // ── Transporters (5) ─────────────────────────────────
        $transporterData = [
            ['name' => 'Arun Transport', 'vehicle' => 'truck', 'capacity' => 10, 'number' => 'DL01AB1234'],
            ['name' => 'Bharat Logistics', 'vehicle' => 'truck', 'capacity' => 8, 'number' => 'RJ14CD5678'],
            ['name' => 'Chandan Carriers', 'vehicle' => 'mini-truck', 'capacity' => 4, 'number' => 'UP32EF9012'],
            ['name' => 'Deepak Freight', 'vehicle' => 'pickup', 'capacity' => 2, 'number' => 'GJ06GH3456'],
            ['name' => 'Eshan Movers', 'vehicle' => 'tractor', 'capacity' => 5, 'number' => 'MH12IJ7890'],
        ];

        foreach ($transporterData as $i => $data) {
            $user = User::create([
                'name' => $data['name'],
                'phone' => '97000' . str_pad($i + 20, 5, '0', STR_PAD_LEFT),
                'email' => strtolower(str_replace(' ', '.', $data['name'])) . '@example.com',
                'password' => Hash::make('password'),
                'role' => 'transporter',
                'is_verified' => true,
                'email_verified_at' => now(),
            ]);
            $user->assignRole('transporter');

            TransporterProfile::create([
                'user_id' => $user->id,
                'vehicle_type' => $data['vehicle'],
                'vehicle_number' => $data['number'],
                'license_number' => 'LIC' . str_pad($i + 1, 8, '0', STR_PAD_LEFT),
                'capacity_tons' => $data['capacity'],
                'is_verified' => true,
            ]);

            Wallet::create(['user_id' => $user->id, 'balance' => rand(1000, 20000)]);
        }

        // ── Market Agents (3) ────────────────────────────────
        $agentNames = ['Sunil Agent', 'Kamla Agent', 'Ravi Agent'];

        foreach ($agentNames as $i => $name) {
            $user = User::create([
                'name' => $name,
                'phone' => '96000' . str_pad($i + 30, 5, '0', STR_PAD_LEFT),
                'email' => strtolower(str_replace(' ', '.', $name)) . '@example.com',
                'password' => Hash::make('password'),
                'role' => 'agent',
                'is_verified' => true,
                'email_verified_at' => now(),
            ]);
            $user->assignRole('agent');
            Wallet::create(['user_id' => $user->id, 'balance' => 0]);
        }
    }
}
