<?php

namespace App\Http\Controllers\Transporter;

use App\Http\Controllers\Controller;
use App\Models\TransportListing;
use App\Services\TransportMatchingService;
use Illuminate\Http\Request;

class TransportListingController extends Controller
{
    public function index()
    {
        $listings = auth()->user()->transportListings()
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('transporter.listings.index', compact('listings'));
    }

    public function create()
    {
        return view('transporter.listings.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'route_from' => ['required', 'string', 'max:255'],
            'route_to' => ['required', 'string', 'max:255'],
            'route_from_lat' => ['nullable', 'numeric'],
            'route_from_lng' => ['nullable', 'numeric'],
            'route_to_lat' => ['nullable', 'numeric'],
            'route_to_lng' => ['nullable', 'numeric'],
            'available_date' => ['required', 'date', 'after_or_equal:today'],
            'total_capacity' => ['required', 'numeric', 'min:0.1', 'max:1000000'],
            'price_per_ton' => ['required', 'numeric', 'min:0'],
        ]);

        $validated['transporter_id'] = auth()->id();
        $validated['remaining_capacity'] = $validated['total_capacity'];
        $validated['status'] = 'available';

        $listing = TransportListing::create($validated);

        return redirect()->route('transporter.listings.show', $listing)
            ->with('success', __('Transport listing created successfully!'));
    }

    public function show(TransportListing $listing)
    {
        if ($listing->transporter_id !== auth()->id()) {
            abort(403);
        }

        $listing->load('bookings.farmer');

        return view('transporter.listings.show', compact('listing'));
    }

    public function edit(TransportListing $listing)
    {
        if ($listing->transporter_id !== auth()->id()) {
            abort(403);
        }

        return view('transporter.listings.edit', compact('listing'));
    }

    public function update(Request $request, TransportListing $listing)
    {
        if ($listing->transporter_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'route_from' => ['required', 'string', 'max:255'],
            'route_to' => ['required', 'string', 'max:255'],
            'available_date' => ['required', 'date'],
            'price_per_ton' => ['required', 'numeric', 'min:0'],
        ]);

        $listing->update($validated);

        return redirect()->route('transporter.listings.show', $listing)
            ->with('success', __('Listing updated.'));
    }

    public function destroy(TransportListing $listing)
    {
        if ($listing->transporter_id !== auth()->id()) {
            abort(403);
        }

        $listing->update(['status' => 'cancelled']);

        return redirect()->route('transporter.listings.index')
            ->with('success', __('Listing cancelled.'));
    }

    public function nearbyRequests(TransportListing $listing)
    {
        if ($listing->transporter_id !== auth()->id()) {
            abort(403);
        }

        $matcher = new TransportMatchingService();
        $requests = $matcher->findRequestsForListing($listing);

        return view('transporter.listings.requests', compact('listing', 'requests'));
    }

    public function capacity(TransportListing $listing)
    {
        return response()->json([
            'total_capacity' => $listing->total_capacity,
            'remaining_capacity' => $listing->remaining_capacity,
            'used_capacity' => $listing->usedCapacity(),
            'percentage' => $listing->capacityPercentage(),
            'status' => $listing->status,
        ]);
    }
}
