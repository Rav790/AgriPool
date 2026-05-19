<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Market;
use App\Models\TransportRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_farmers' => User::where('role', 'farmer')->count(),
            'total_transporters' => User::where('role', 'transporter')->count(),
            'total_agents' => User::where('role', 'agent')->count(),
            'total_bookings' => Booking::count(),
            'active_bookings' => Booking::whereNotIn('status', ['delivered', 'cancelled'])->count(),
            'total_revenue' => Booking::where('payment_status', 'released')->sum('total_price'),
            'total_markets' => Market::count(),
            'pending_requests' => TransportRequest::where('status', 'pending')->count(),
        ];

        $recentBookings = Booking::with(['farmer', 'transporter'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $topRoutes = Booking::join('transport_listings', 'bookings.listing_id', '=', 'transport_listings.id')
            ->select('transport_listings.route_from', 'transport_listings.route_to', DB::raw('COUNT(*) as trip_count'), DB::raw('SUM(bookings.total_price) as revenue'))
            ->groupBy('transport_listings.route_from', 'transport_listings.route_to')
            ->orderByDesc('trip_count')
            ->limit(5)
            ->get();

        $recentUsers = User::orderByDesc('created_at')->limit(10)->get();

        return view('admin.dashboard', compact('stats', 'recentBookings', 'topRoutes', 'recentUsers'));
    }

    public function bookings()
    {
        $bookings = Booking::with(['farmer', 'transporter', 'transportRequest'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function flagBooking(Booking $booking)
    {
        $booking->update(['status' => 'disputed']);

        return redirect()->back()->with('success', __('Booking has been flagged for dispute.'));
    }

    public function analytics()
    {
        $monthlyBookings = Booking::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(total_price) as revenue')
        )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $cropDemand = TransportRequest::select('crop_type', DB::raw('COUNT(*) as demand_count'), DB::raw('SUM(quantity_tons) as total_tons'))
            ->groupBy('crop_type')
            ->orderByDesc('demand_count')
            ->get();

        $transportDemand = TransportRequest::with('destinationMarket')
            ->select('destination_market_id', DB::raw('COUNT(*) as count'))
            ->groupBy('destination_market_id')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        // Booking status distribution for doughnut chart
        $statusDistribution = Booking::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        // User growth by month
        $userGrowth = User::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('COUNT(*) as count')
        )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Revenue by crop type
        $revenueByCrop = Booking::join('transport_requests', 'bookings.request_id', '=', 'transport_requests.id')
            ->select('transport_requests.crop_type', DB::raw('SUM(bookings.total_price) as revenue'))
            ->groupBy('transport_requests.crop_type')
            ->orderByDesc('revenue')
            ->get();

        // Payment status breakdown
        $paymentMethods = Booking::select('payment_status', DB::raw('COUNT(*) as count'))
            ->whereNotNull('payment_status')
            ->groupBy('payment_status')
            ->get();

        // KPI comparison
        $thisMonth = Booking::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
        $lastMonth = Booking::whereMonth('created_at', now()->subMonth()->month)->whereYear('created_at', now()->subMonth()->year);

        $kpis = [
            'revenue_this_month' => (clone $thisMonth)->sum('total_price'),
            'revenue_last_month' => (clone $lastMonth)->sum('total_price'),
            'bookings_this_month' => (clone $thisMonth)->count(),
            'bookings_last_month' => (clone $lastMonth)->count(),
            'avg_booking_value' => Booking::where('status', 'delivered')->avg('total_price') ?? 0,
            'completion_rate' => Booking::count() > 0
                ? round(Booking::where('status', 'delivered')->count() / Booking::count() * 100, 1)
                : 0,
        ];

        return view('admin.analytics', compact(
            'monthlyBookings', 'cropDemand', 'transportDemand',
            'statusDistribution', 'userGrowth', 'revenueByCrop',
            'paymentMethods', 'kpis'
        ));
    }

    // ─── Help Ticket Management ─────────────────────────────────

    public function helpTickets()
    {
        $tickets = \App\Models\HelpTicket::with('user')
            ->orderByRaw("CASE status WHEN 'open' THEN 1 WHEN 'in_progress' THEN 2 WHEN 'resolved' THEN 3 ELSE 4 END")
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.help-tickets.index', compact('tickets'));
    }

    public function helpTicketShow(\App\Models\HelpTicket $ticket)
    {
        $ticket->load('user');
        return view('admin.help-tickets.show', compact('ticket'));
    }

    public function helpTicketRespond(\Illuminate\Http\Request $request, \App\Models\HelpTicket $ticket)
    {
        $request->validate(['response' => 'required|string']);

        $updateData = [
            'admin_response' => $request->response,
            'status' => $request->input('status', 'in_progress'),
            'assigned_to' => auth()->id(),
        ];

        if (in_array($request->input('status'), ['resolved', 'closed'])) {
            $updateData['resolved_at'] = now();
        }

        $ticket->update($updateData);

        return redirect()->back()->with('success', __('Response sent successfully.'));
    }

    // ─── Dispute Management ─────────────────────────────────────

    public function disputes()
    {
        $disputes = \App\Models\Dispute::with(['user', 'booking'])
            ->orderByRaw("CASE status WHEN 'open' THEN 1 WHEN 'investigating' THEN 2 WHEN 'resolved' THEN 3 WHEN 'rejected' THEN 4 ELSE 5 END")
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.disputes.index', compact('disputes'));
    }

    public function disputeShow(\App\Models\Dispute $dispute)
    {
        $dispute->load(['user', 'booking.farmer', 'booking.transporter']);
        return view('admin.disputes.show', compact('dispute'));
    }

    public function disputeResolve(\Illuminate\Http\Request $request, \App\Models\Dispute $dispute)
    {
        $request->validate(['resolution_notes' => 'required|string', 'status' => 'required|in:investigating,resolved,rejected']);

        $dispute->update([
            'status' => $request->status,
            'resolution_notes' => $request->resolution_notes,
            'resolved_by' => auth()->id(),
            'resolved_at' => now(),
        ]);

        return redirect()->back()->with('success', __('Dispute updated successfully.'));
    }
}
