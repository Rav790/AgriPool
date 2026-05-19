<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Booking $booking)
    {
        $user = auth()->user();

        if (!$booking->canBeReviewedBy($user)) {
            return redirect()->back()->with('error', __('You cannot review this booking.'));
        }

        $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:500'],
        ]);

        // Determine reviewee
        $revieweeId = $booking->farmer_id === $user->id
            ? $booking->transporter_id
            : $booking->farmer_id;

        Review::create([
            'reviewer_id' => $user->id,
            'reviewee_id' => $revieweeId,
            'booking_id' => $booking->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', __('Review submitted successfully.'));
    }
}
