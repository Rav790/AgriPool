<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\TransportListing;
use App\Models\TransportRequest;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $bookedRequests = TransportRequest::whereIn('status', ['booked', 'delivered'])->get();
        $listings = TransportListing::where('status', '!=', 'cancelled')->get();

        if ($listings->isEmpty()) return;

        $statuses = ['confirmed', 'picked_up', 'in_transit', 'delivered', 'delivered'];
        $paymentStatuses = ['held', 'held', 'released', 'released', 'unpaid'];

        foreach ($bookedRequests as $request) {
            $listing = $listings->random();

            $allocatedTons = min($request->quantity_tons, $listing->remaining_capacity);
            if ($allocatedTons <= 0) $allocatedTons = $request->quantity_tons;

            $totalPrice = $allocatedTons * $listing->price_per_ton;
            $status = $statuses[array_rand($statuses)];
            $paymentStatus = $paymentStatuses[array_rand($paymentStatuses)];

            Booking::create([
                'request_id' => $request->id,
                'listing_id' => $listing->id,
                'farmer_id' => $request->farmer_id,
                'transporter_id' => $listing->transporter_id,
                'allocated_tons' => $allocatedTons,
                'total_price' => $totalPrice,
                'status' => $status,
                'payment_status' => $paymentStatus,
                'payment_mode' => ['upi', 'cash', 'wallet'][array_rand(['upi', 'cash', 'wallet'])],
                'pickup_confirmed_at' => in_array($status, ['picked_up', 'in_transit', 'delivered']) ? Carbon::now()->subDays(rand(1, 5)) : null,
                'delivery_confirmed_at' => $status === 'delivered' ? Carbon::now()->subDays(rand(0, 2)) : null,
            ]);
        }
    }
}
