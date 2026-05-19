<?php

namespace Database\Seeders;

use App\Models\Market;
use Illuminate\Database\Seeder;

class MarketSeeder extends Seeder
{
    public function run(): void
    {
        $markets = [
            ['name' => 'Azadpur Mandi', 'location' => 'Azadpur', 'city' => 'Delhi', 'state' => 'Delhi', 'lat' => 28.7041, 'lng' => 77.1025, 'type' => 'mandi'],
            ['name' => 'Vashi APMC', 'location' => 'Vashi', 'city' => 'Navi Mumbai', 'state' => 'Maharashtra', 'lat' => 19.0771, 'lng' => 72.9987, 'type' => 'mandi'],
            ['name' => 'Koyambedu Market', 'location' => 'Koyambedu', 'city' => 'Chennai', 'state' => 'Tamil Nadu', 'lat' => 13.0694, 'lng' => 80.1948, 'type' => 'wholesale'],
            ['name' => 'Yeshwanthpur APMC', 'location' => 'Yeshwanthpur', 'city' => 'Bengaluru', 'state' => 'Karnataka', 'lat' => 13.0220, 'lng' => 77.5440, 'type' => 'mandi'],
            ['name' => 'Jamalpur Mandi', 'location' => 'Jamalpur', 'city' => 'Ahmedabad', 'state' => 'Gujarat', 'lat' => 23.0258, 'lng' => 72.5873, 'type' => 'mandi'],
            ['name' => 'Gultekdi Market', 'location' => 'Gultekdi', 'city' => 'Pune', 'state' => 'Maharashtra', 'lat' => 18.4965, 'lng' => 73.8756, 'type' => 'wholesale'],
            ['name' => 'Bowbazar Market', 'location' => 'Bowbazar', 'city' => 'Kolkata', 'state' => 'West Bengal', 'lat' => 22.5697, 'lng' => 88.3591, 'type' => 'retail'],
            ['name' => 'Muhana Mandi', 'location' => 'Muhana', 'city' => 'Jaipur', 'state' => 'Rajasthan', 'lat' => 26.7867, 'lng' => 75.8286, 'type' => 'mandi'],
            ['name' => 'Gaddiannaram Market', 'location' => 'Gaddiannaram', 'city' => 'Hyderabad', 'state' => 'Telangana', 'lat' => 17.3503, 'lng' => 78.5242, 'type' => 'wholesale'],
            ['name' => 'Chawri Bazaar', 'location' => 'Old Delhi', 'city' => 'Delhi', 'state' => 'Delhi', 'lat' => 28.6507, 'lng' => 77.2334, 'type' => 'retail'],
            ['name' => 'Lucknow Mandi', 'location' => 'Aishbagh', 'city' => 'Lucknow', 'state' => 'Uttar Pradesh', 'lat' => 26.8467, 'lng' => 80.9462, 'type' => 'mandi'],
            ['name' => 'Patna Market', 'location' => 'Kankarbagh', 'city' => 'Patna', 'state' => 'Bihar', 'lat' => 25.5941, 'lng' => 85.1376, 'type' => 'wholesale'],
            ['name' => 'Indore Mandi', 'location' => 'Khatiwala Tank', 'city' => 'Indore', 'state' => 'Madhya Pradesh', 'lat' => 22.7196, 'lng' => 75.8577, 'type' => 'mandi'],
            ['name' => 'Ludhiana Grain Market', 'location' => 'Jagraon', 'city' => 'Ludhiana', 'state' => 'Punjab', 'lat' => 30.9010, 'lng' => 75.8573, 'type' => 'mandi'],
            ['name' => 'Siliguri Market', 'location' => 'Bidhan Road', 'city' => 'Siliguri', 'state' => 'West Bengal', 'lat' => 26.7271, 'lng' => 88.3953, 'type' => 'wholesale'],
            ['name' => 'Chandigarh Mandi', 'location' => 'Sector 26', 'city' => 'Chandigarh', 'state' => 'Chandigarh', 'lat' => 30.7333, 'lng' => 76.7794, 'type' => 'mandi'],
            ['name' => 'Nagpur APMC', 'location' => 'Kalamna', 'city' => 'Nagpur', 'state' => 'Maharashtra', 'lat' => 21.1458, 'lng' => 79.0882, 'type' => 'mandi'],
            ['name' => 'Bhopal Mandi', 'location' => 'Karond', 'city' => 'Bhopal', 'state' => 'Madhya Pradesh', 'lat' => 23.2599, 'lng' => 77.4126, 'type' => 'wholesale'],
            ['name' => 'Visakhapatnam Market', 'location' => 'Rythu Bazaar', 'city' => 'Visakhapatnam', 'state' => 'Andhra Pradesh', 'lat' => 17.6868, 'lng' => 83.2185, 'type' => 'retail'],
            ['name' => 'Guwahati Market', 'location' => 'Fancy Bazaar', 'city' => 'Guwahati', 'state' => 'Assam', 'lat' => 26.1862, 'lng' => 91.7458, 'type' => 'wholesale'],
        ];

        foreach ($markets as $market) {
            Market::create($market);
        }
    }
}
