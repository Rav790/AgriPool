<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Review;
use App\Models\TransportListing;
use App\Models\User;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function index()
    {
        // Top Transporters by rating
        $topTransporters = User::where('role', 'transporter')
            ->withCount(['reviewsReceived', 'transporterBookings'])
            ->withAvg('reviewsReceived', 'rating')
            ->having('reviews_received_count', '>=', 1)
            ->orderByDesc('reviews_received_avg_rating')
            ->limit(10)
            ->get();

        // Top Farmers by booking count
        $topFarmers = User::where('role', 'farmer')
            ->withCount('farmerBookings')
            ->orderByDesc('farmer_bookings_count')
            ->limit(10)
            ->get();

        // Most active routes
        $topRoutes = Booking::join('transport_listings', 'bookings.listing_id', '=', 'transport_listings.id')
            ->selectRaw('transport_listings.route_from, transport_listings.route_to, COUNT(*) as trip_count, SUM(bookings.total_price) as total_revenue')
            ->groupBy('transport_listings.route_from', 'transport_listings.route_to')
            ->orderByDesc('trip_count')
            ->limit(10)
            ->get();

        // Platform stats
        $stats = [
            'total_shipments' => Booking::where('status', 'delivered')->count(),
            'total_volume' => Booking::where('status', 'delivered')->sum('allocated_tons'),
            'total_saved' => Booking::where('status', 'delivered')->sum('total_price') * 0.4, // assumed 40% savings
            'active_users' => User::where('is_verified', true)->count(),
        ];

        return view('leaderboard.index', compact('topTransporters', 'topFarmers', 'topRoutes', 'stats'));
    }
}
