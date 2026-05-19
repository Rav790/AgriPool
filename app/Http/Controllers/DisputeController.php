<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Dispute;
use Illuminate\Http\Request;

class DisputeController extends Controller
{
    public function create(Booking $booking)
    {
        $user = auth()->user();
        if ($booking->farmer_id !== $user->id && $booking->transporter_id !== $user->id) {
            abort(403);
        }

        return view('disputes.create', compact('booking'));
    }

    public function store(Request $request, Booking $booking)
    {
        $user = auth()->user();
        if ($booking->farmer_id !== $user->id && $booking->transporter_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'category' => ['required', 'in:damaged_goods,late_delivery,payment_issue,wrong_delivery,other'],
            'description' => ['required', 'string', 'max:2000'],
            'priority' => ['required', 'in:low,medium,high,critical'],
        ]);

        Dispute::create([
            'booking_id' => $booking->id,
            'raised_by' => $user->id,
            'category' => $validated['category'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
        ]);

        $booking->update(['status' => 'disputed']);

        return redirect()->back()->with('success', __('Dispute raised successfully. Our team will review it within 24 hours.'));
    }

    public function show(Dispute $dispute)
    {
        $user = auth()->user();
        $dispute->load(['booking.farmer', 'booking.transporter', 'raisedBy', 'resolvedBy']);

        if (!$user->isAdmin() && $dispute->raised_by !== $user->id) {
            abort(403);
        }

        return view('disputes.show', compact('dispute'));
    }

    public function myDisputes()
    {
        $disputes = Dispute::where('raised_by', auth()->id())
            ->with(['booking'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('disputes.index', compact('disputes'));
    }
}
