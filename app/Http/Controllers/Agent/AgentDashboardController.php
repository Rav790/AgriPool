<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Market;
use App\Models\MarketPrice;
use Illuminate\Http\Request;

class AgentDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_markets' => Market::count(),
            'prices_today' => MarketPrice::whereDate('recorded_date', today())->count(),
            'pending_deliveries' => Booking::where('status', 'in_transit')->count(),
            'completed_deliveries' => Booking::where('status', 'delivered')->count(),
        ];

        $recentPrices = MarketPrice::with('market')
            ->where('recorded_by', auth()->id())
            ->orderByDesc('recorded_date')
            ->limit(10)
            ->get();

        $pendingDeliveries = Booking::with(['farmer', 'transporter', 'transportRequest.destinationMarket'])
            ->whereIn('status', ['in_transit', 'picked_up'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('agent.dashboard', compact('stats', 'recentPrices', 'pendingDeliveries'));
    }

    public function deliveries()
    {
        $deliveries = Booking::with(['farmer', 'transporter', 'transportRequest.destinationMarket'])
            ->whereIn('status', ['in_transit', 'picked_up'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('agent.deliveries', compact('deliveries'));
    }

    public function confirmDelivery(Booking $booking)
    {
        if (!in_array($booking->status, ['in_transit', 'picked_up'])) {
            return redirect()->back()->with('error', __('This booking is not eligible for delivery confirmation.'));
        }

        $booking->update([
            'status' => 'delivered',
            'delivery_confirmed_at' => now(),
        ]);

        // Release escrow
        if ($booking->payment_status === 'held') {
            $transporterWallet = $booking->transporter->wallet;
            if ($transporterWallet) {
                $transporterWallet->credit($booking->total_price, $booking->id, __('Payment released for booking #:id', ['id' => $booking->id]));
            }
            $booking->update(['payment_status' => 'released']);
        }

        return redirect()->back()->with('success', __('Delivery confirmed and payment released.'));
    }
}
