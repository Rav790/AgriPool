<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\TrackingUpdate;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function show(Booking $booking)
    {
        $user = auth()->user();

        if ($booking->farmer_id !== $user->id && $booking->transporter_id !== $user->id) {
            abort(403);
        }

        $updates = $booking->trackingUpdates()->orderBy('created_at', 'asc')->get();

        return view('tracking.show', compact('booking', 'updates'));
    }

    public function update(Request $request, Booking $booking)
    {
        $user = auth()->user();

        if ($booking->transporter_id !== $user->id) {
            abort(403);
        }

        $request->validate([
            'lat' => ['nullable', 'numeric', 'between:-90,90'],
            'lng' => ['nullable', 'numeric', 'between:-180,180'],
            'status' => ['required', 'in:picked_up,in_transit,checkpoint,delivered'],
            'status_note' => ['nullable', 'string', 'max:255'],
        ]);

        TrackingUpdate::create([
            'booking_id' => $booking->id,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'status' => $request->status,
            'status_note' => $request->status_note,
        ]);

        // Update booking status based on tracking
        if ($request->status === 'picked_up' && $booking->status !== 'picked_up') {
            $booking->update(['status' => 'picked_up', 'pickup_confirmed_at' => now()]);
        } elseif ($request->status === 'in_transit') {
            $booking->update(['status' => 'in_transit']);
        } elseif ($request->status === 'delivered') {
            $booking->update(['status' => 'delivered', 'delivery_confirmed_at' => now()]);
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', __('Tracking updated.'));
    }

    public function latest(Booking $booking)
    {
        $user = auth()->user();

        // Only allow farmer and transporter of this booking to view tracking
        if ($booking->farmer_id !== $user->id && $booking->transporter_id !== $user->id && !$user->isAdmin()) {
            abort(403);
        }

        $latest = $booking->trackingUpdates()->orderByDesc('created_at')->first();
        $timeline = $booking->trackingUpdates()->orderBy('created_at')->get();

        return response()->json([
            'latest' => $latest,
            'timeline' => $timeline,
            'booking_status' => $booking->status,
        ]);
    }
}
