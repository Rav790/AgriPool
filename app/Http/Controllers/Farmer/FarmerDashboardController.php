<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\TransportRequest;
use Illuminate\Http\Request;

class FarmerDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'total_requests' => $user->transportRequests()->count(),
            'pending_requests' => $user->transportRequests()->where('status', 'pending')->count(),
            'active_bookings' => $user->farmerBookings()->whereNotIn('status', ['delivered', 'cancelled'])->count(),
            'delivered' => $user->farmerBookings()->where('status', 'delivered')->count(),
            'wallet_balance' => $user->wallet->balance ?? 0,
            'total_spent' => $user->farmerBookings()->where('payment_status', 'released')->sum('total_price'),
            'trust_score' => $user->trust_score ?? 20,
        ];

        $recentRequests = $user->transportRequests()
            ->with('destinationMarket')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $activeBookings = $user->farmerBookings()
            ->with(['transportListing.transporter', 'transportRequest'])
            ->whereNotIn('status', ['delivered', 'cancelled'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $recentDeliveries = $user->farmerBookings()
            ->with(['transporter', 'transportRequest'])
            ->where('status', 'delivered')
            ->orderByDesc('delivery_confirmed_at')
            ->limit(5)
            ->get();

        // Build activity feed from recent bookings
        $activities = collect();
        foreach ($user->farmerBookings()->with('transporter')->orderByDesc('updated_at')->limit(8)->get() as $b) {
            $activities->push([
                'type' => $b->status === 'delivered' ? 'delivery' : ($b->payment_status === 'released' ? 'payment' : 'booking'),
                'icon' => $b->status === 'delivered' ? '✅' : ($b->status === 'in_transit' ? '🚛' : '📋'),
                'message' => match($b->status) {
                    'delivered' => __('Delivery completed') . ' — ' . ($b->transporter->name ?? ''),
                    'in_transit' => __('Shipment in transit with') . ' ' . ($b->transporter->name ?? ''),
                    'confirmed' => __('Booking confirmed') . ' #' . $b->id,
                    default => __('Booking') . ' #' . $b->id . ' — ' . ucfirst($b->status),
                },
                'time' => $b->updated_at->diffForHumans(),
            ]);
        }
        foreach ($user->transportRequests()->orderByDesc('created_at')->limit(4)->get() as $r) {
            $activities->push([
                'type' => 'request',
                'icon' => '📝',
                'message' => __('Request posted') . ': ' . $r->crop_type . ' — ' . $r->quantity_tons . ' ' . __('tons'),
                'time' => $r->created_at->diffForHumans(),
            ]);
        }
        $activities = $activities->sortByDesc('time')->values()->take(10);

        // Fetch available vehicles/listings for direct booking
        $availableVehicles = \App\Models\TransportListing::with('transporter')
            ->whereIn('status', ['available', 'partially_booked'])
            ->where('remaining_capacity', '>', 0)
            ->orderByDesc('created_at')
            ->get();

        // Fetch farmer's pending requests for matching with available vehicles
        $pendingRequests = $user->transportRequests()->where('status', 'pending')->get();

        return view('farmer.dashboard', compact('stats', 'recentRequests', 'activeBookings', 'recentDeliveries', 'activities', 'availableVehicles', 'pendingRequests'));
    }
}
