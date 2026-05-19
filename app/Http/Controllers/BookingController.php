<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\TransportListing;
use App\Models\TransportRequest;
use App\Models\User;
use App\Notifications\NewBookingNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'request_id' => ['required', 'exists:transport_requests,id'],
            'listing_id' => ['required', 'exists:transport_listings,id'],
            'allocated_tons' => ['required', 'numeric', 'min:0.1'],
        ]);

        $user = auth()->user();

        // Use database transaction with pessimistic locking to prevent race conditions
        $result = DB::transaction(function () use ($request, $user) {
            $transportRequest = TransportRequest::findOrFail($request->request_id);
            $listing = TransportListing::lockForUpdate()->findOrFail($request->listing_id);

            // Either farmer owns the request, OR transporter owns the listing
            $isFarmer = $transportRequest->farmer_id === $user->id;
            $isTransporter = $listing->transporter_id === $user->id;

            if (!$isFarmer && !$isTransporter) {
                abort(403, 'You are not authorized to create this booking.');
            }

            // Check capacity with locked row (prevents race condition)
            if ($request->allocated_tons > $listing->remaining_capacity) {
                return ['error' => __('Not enough capacity available. Only :tons tons remaining.', [
                    'tons' => $listing->remaining_capacity,
                ])];
            }

            $totalPrice = $request->allocated_tons * $listing->price_per_ton;

            $booking = Booking::create([
                'request_id' => $transportRequest->id,
                'listing_id' => $listing->id,
                'farmer_id' => $transportRequest->farmer_id,
                'transporter_id' => $listing->transporter_id,
                'allocated_tons' => $request->allocated_tons,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'payment_status' => 'unpaid',
            ]);

            // Update listing capacity
            $listing->decrement('remaining_capacity', $request->allocated_tons);

            if ($listing->remaining_capacity <= 0) {
                $listing->update(['status' => 'fully_booked']);
            } else {
                $listing->update(['status' => 'partially_booked']);
            }

            // Only mark transport request as 'booked' when fully allocated
            // This preserves the pooling model where multiple bookings can fill one request
            $totalBooked = $transportRequest->bookings()->sum('allocated_tons');
            if ($totalBooked >= $transportRequest->quantity_tons) {
                $transportRequest->update(['status' => 'booked']);
            } else {
                $transportRequest->update(['status' => 'partially_booked']);
            }

            return ['booking' => $booking, 'totalPrice' => $totalPrice, 'isTransporter' => $isTransporter];
        });

        // Handle capacity error
        if (isset($result['error'])) {
            return redirect()->back()->with('error', $result['error']);
        }

        $booking = $result['booking'];
        $totalPrice = $result['totalPrice'];

        // Notify both parties (outside transaction — non-critical)
        try {
            $farmer = User::find($booking->farmer_id);
            $transporter = User::find($booking->transporter_id);
            if ($farmer) {
                $farmer->notify(new NewBookingNotification($booking, 'Your produce request has been accepted!'));
            }
            if ($transporter) {
                $transporter->notify(new NewBookingNotification($booking, 'New booking created for your listing.'));
            }
        } catch (\Exception $e) {
            // Notifications are non-critical
        }

        // Redirect based on role
        if ($result['isTransporter']) {
            return redirect()->route('transporter.bookings.show', $booking)
                ->with('success', __('Booking accepted! Total fare: ₹:price', ['price' => number_format($totalPrice)]));
        }

        return redirect()->route('farmer.bookings.show', $booking)
            ->with('success', __('Booking created successfully! Total cost: ₹:price', ['price' => number_format($totalPrice)]));
    }
}

