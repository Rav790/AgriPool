<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class FarmerBookingController extends Controller
{
    public function index()
    {
        $bookings = auth()->user()->farmerBookings()
            ->with(['transportListing.transporter', 'transportRequest.destinationMarket'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('farmer.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        if ($booking->farmer_id !== auth()->id()) {
            abort(403);
        }

        $booking->load([
            'transporter',
            'transportRequest.destinationMarket',
            'transportListing',
            'trackingUpdates',
            'reviews',
            'messages.sender',
        ]);

        $canReview = $booking->canBeReviewedBy(auth()->user());

        return view('farmer.bookings.show', compact('booking', 'canReview'));
    }

    public function confirmDelivery(Booking $booking)
    {
        if ($booking->farmer_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->status !== 'in_transit' && $booking->status !== 'picked_up') {
            return redirect()->back()->with('error', __('Booking is not eligible for delivery confirmation.'));
        }

        $booking->update([
            'status' => 'delivered',
            'delivery_confirmed_at' => now(),
        ]);

        // Release escrow payment if held
        if ($booking->payment_status === 'held') {
            $transporterWallet = $booking->transporter->wallet;
            if ($transporterWallet) {
                $transporterWallet->credit($booking->total_price, $booking->id, __('Payment released for booking #:id', ['id' => $booking->id]));
            }
            $booking->update(['payment_status' => 'released']);
        }

        return redirect()->back()->with('success', __('Delivery confirmed! Payment released to transporter.'));
    }
}
