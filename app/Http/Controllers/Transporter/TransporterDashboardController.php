<?php

namespace App\Http\Controllers\Transporter;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\TransportListing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransporterDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'total_listings' => $user->transportListings()->count(),
            'active_listings' => $user->transportListings()->whereIn('status', ['available', 'partially_booked'])->count(),
            'active_bookings' => $user->transporterBookings()->whereNotIn('status', ['delivered', 'cancelled'])->count(),
            'completed_trips' => $user->transporterBookings()->where('status', 'delivered')->count(),
            'total_revenue' => $user->transporterBookings()->where('payment_status', 'released')->sum('total_price'),
            'avg_rating' => $user->averageRating(),
            'wallet_balance' => $user->wallet->balance ?? 0,
            'trust_score' => $user->trust_score ?? 20,
        ];

        $activeListings = $user->transportListings()
            ->whereIn('status', ['available', 'partially_booked'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $activeBookings = $user->transporterBookings()
            ->with(['farmer', 'transportRequest.destinationMarket'])
            ->whereNotIn('status', ['delivered', 'cancelled'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // Monthly earnings for chart
        $monthlyEarnings = $user->transporterBookings()
            ->where('payment_status', 'released')
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(total_price) as earnings'),
                DB::raw('COUNT(*) as trips')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')->orderBy('month')
            ->get();

        // Recent completed deliveries
        $recentDeliveries = $user->transporterBookings()
            ->with(['farmer', 'transportRequest'])
            ->where('status', 'delivered')
            ->orderByDesc('delivery_confirmed_at')
            ->limit(5)
            ->get();

        // Activity feed
        $activities = collect();
        foreach ($user->transporterBookings()->with('farmer')->orderByDesc('updated_at')->limit(8)->get() as $b) {
            $activities->push([
                'type' => $b->status === 'delivered' ? 'delivery' : 'booking',
                'icon' => $b->status === 'delivered' ? '✅' : ($b->status === 'in_transit' ? '🚛' : '📋'),
                'message' => match($b->status) {
                    'delivered' => __('Delivered to') . ' ' . ($b->farmer->name ?? '—'),
                    'in_transit' => __('In transit — ') . ($b->farmer->name ?? '—'),
                    'confirmed' => __('New booking from') . ' ' . ($b->farmer->name ?? '—'),
                    default => __('Booking') . ' #' . $b->id . ' — ' . ucfirst($b->status),
                },
                'time' => $b->updated_at->diffForHumans(),
            ]);
        }
        $activities = $activities->take(10);

        // Fetch all open/pending farmer produce requests available for acceptance
        $availableRequests = \App\Models\TransportRequest::with(['farmer', 'destinationMarket'])
            ->where('status', 'pending')
            ->orderByDesc('created_at')
            ->get();

        // Fetch transporter's active listings to map to a request
        $activeListingsForSelect = $user->transportListings()
            ->whereIn('status', ['available', 'partially_booked'])
            ->where('remaining_capacity', '>', 0)
            ->get();

        return view('transporter.dashboard', compact(
            'stats', 'activeListings', 'activeBookings',
            'monthlyEarnings', 'recentDeliveries', 'activities',
            'availableRequests', 'activeListingsForSelect'
        ));
    }
}
