<?php

namespace App\Http\Controllers\Transporter;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class TransporterBookingController extends Controller
{
    public function index()
    {
        $bookings = auth()->user()->transporterBookings()
            ->with(['farmer', 'transportRequest.destinationMarket', 'transportListing'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('transporter.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        if ($booking->transporter_id !== auth()->id()) {
            abort(403);
        }

        $booking->load([
            'farmer',
            'transportRequest.destinationMarket',
            'transportListing',
            'trackingUpdates',
            'reviews',
        ]);

        $canReview = $booking->canBeReviewedBy(auth()->user());

        return view('transporter.bookings.show', compact('booking', 'canReview'));
    }

    public function accept(Booking $booking)
    {
        if ($booking->transporter_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->status !== 'pending') {
            return redirect()->back()->with('error', __('Booking cannot be accepted.'));
        }

        $booking->update(['status' => 'confirmed']);

        return redirect()->back()->with('success', __('Booking accepted!'));
    }

    public function pickup(Booking $booking)
    {
        if ($booking->transporter_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->status !== 'confirmed') {
            return redirect()->back()->with('error', __('Booking must be confirmed before pickup.'));
        }

        $booking->update([
            'status' => 'picked_up',
            'pickup_confirmed_at' => now(),
        ]);

        return redirect()->back()->with('success', __('Pickup confirmed!'));
    }

    public function deliver(Booking $booking)
    {
        if ($booking->transporter_id !== auth()->id()) {
            abort(403);
        }

        if (!in_array($booking->status, ['picked_up', 'confirmed'])) {
            return redirect()->back()->with('error', __('Booking must be picked up before marking in transit.'));
        }

        $booking->update([
            'status' => 'in_transit',
        ]);

        return redirect()->back()->with('success', __('Status updated to In Transit.'));
    }
}
