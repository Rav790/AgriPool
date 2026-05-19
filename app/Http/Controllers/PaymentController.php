<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Wallet;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function wallet()
    {
        $user = auth()->user();
        $wallet = $user->wallet ?? Wallet::create(['user_id' => $user->id, 'balance' => 0]);
        $transactions = $wallet->transactions()->orderByDesc('created_at')->paginate(15);

        return view('payments.wallet', compact('wallet', 'transactions'));
    }

    public function topup(Request $request)
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:1', 'max:100000'],
        ]);

        $user = auth()->user();
        $wallet = $user->wallet ?? Wallet::create(['user_id' => $user->id, 'balance' => 0]);
        $wallet->credit($request->amount, null, __('Wallet top-up'));

        return redirect()->back()->with('success', __('Wallet topped up with ₹:amount', ['amount' => $request->amount]));
    }

    public function pay(Request $request, Booking $booking)
    {
        $user = auth()->user();

        if ($booking->farmer_id !== $user->id) {
            abort(403);
        }

        if ($booking->payment_status !== 'unpaid') {
            return redirect()->back()->with('error', __('Payment already processed.'));
        }

        $request->validate([
            'payment_mode' => ['required', 'in:upi,cash,wallet'],
        ]);

        $booking->update(['payment_mode' => $request->payment_mode]);

        if ($request->payment_mode === 'wallet') {
            $wallet = $user->wallet;

            if (!$wallet || $wallet->balance < $booking->total_price) {
                return redirect()->back()->with('error', __('Insufficient wallet balance. Please top up.'));
            }

            // Hold payment in escrow
            $wallet->hold($booking->total_price, $booking->id);
            $booking->update(['payment_status' => 'held']);

            return redirect()->back()->with('success', __('Payment of ₹:amount held in escrow.', ['amount' => $booking->total_price]));
        }

        if ($request->payment_mode === 'upi') {
            // Simulate UPI payment
            $booking->update(['payment_status' => 'held']);
            return redirect()->back()->with('success', __('UPI payment simulated. ₹:amount held in escrow.', ['amount' => $booking->total_price]));
        }

        // Cash payment
        $booking->update(['payment_status' => 'held']);
        return redirect()->back()->with('success', __('Cash payment will be collected at pickup.'));
    }

    public function invoice(Booking $booking)
    {
        $user = auth()->user();

        if ($booking->farmer_id !== $user->id && $booking->transporter_id !== $user->id) {
            abort(403);
        }

        $booking->load(['farmer', 'transporter', 'transportRequest.destinationMarket', 'transportListing']);

        $pdf = Pdf::loadView('payments.invoice', compact('booking'));

        return $pdf->download('invoice-booking-' . $booking->id . '.pdf');
    }
}
