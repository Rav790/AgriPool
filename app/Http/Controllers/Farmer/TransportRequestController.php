<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\Market;
use App\Models\TransportListing;
use App\Models\TransportRequest;
use App\Services\TransportMatchingService;
use Illuminate\Http\Request;

class TransportRequestController extends Controller
{
    public function index()
    {
        $requests = auth()->user()->transportRequests()
            ->with('destinationMarket')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('farmer.requests.index', compact('requests'));
    }

    public function create()
    {
        $markets = Market::orderBy('name')->get();
        $crops = ['Rice', 'Wheat', 'Tomato', 'Onion', 'Potato', 'Soybean', 'Cotton', 'Sugarcane', 'Maize', 'Mustard'];
        $packaging = ['sacks', 'crates', 'loose', 'boxes'];

        return view('farmer.requests.create', compact('markets', 'crops', 'packaging'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'crop_type' => ['required', 'string', 'max:100'],
            'quantity_tons' => ['required', 'numeric', 'min:0.1', 'max:50'],
            'packaging_type' => ['required', 'in:sacks,crates,loose,boxes'],
            'pickup_address' => ['required', 'string', 'max:255'],
            'pickup_lat' => ['nullable', 'numeric'],
            'pickup_lng' => ['nullable', 'numeric'],
            'destination_market_id' => ['required', 'exists:markets,id'],
            'required_date' => ['required', 'date', 'after_or_equal:today'],
            'is_perishable' => ['boolean'],
            'special_instructions' => ['nullable', 'string', 'max:500'],
        ]);

        $validated['farmer_id'] = auth()->id();
        $validated['is_perishable'] = $request->boolean('is_perishable');
        $validated['status'] = 'pending';

        // Use farm address as default if no pickup specified
        $profile = auth()->user()->farmerProfile;
        if (empty($validated['pickup_lat']) && $profile) {
            $validated['pickup_lat'] = $profile->farm_location_lat;
            $validated['pickup_lng'] = $profile->farm_location_lng;
        }

        $transportRequest = TransportRequest::create($validated);

        // Notify all transporters about new produce request
        try {
            $transportRequest->load('farmer');
            $transporters = \App\Models\User::where('role', 'transporter')->get();
            foreach ($transporters as $transporter) {
                $transporter->notify(new \App\Notifications\NewProduceRequestNotification($transportRequest));
            }
        } catch (\Exception $e) {
            // Non-critical — don't break the flow
        }

        return redirect()->route('farmer.requests.matches', $transportRequest)
            ->with('success', __('Transport request created! Here are matching transporters.'));
    }

    public function show(TransportRequest $request)
    {
        if ($request->farmer_id !== auth()->id()) {
            abort(403);
        }

        $request->load(['destinationMarket', 'bookings.transporter']);

        return view('farmer.requests.show', compact('request'));
    }

    public function edit(TransportRequest $request)
    {
        if ($request->farmer_id !== auth()->id()) {
            abort(403);
        }

        if ($request->status !== 'pending') {
            return redirect()->back()->with('error', __('Cannot edit a non-pending request.'));
        }

        $markets = Market::orderBy('name')->get();
        $crops = ['Rice', 'Wheat', 'Tomato', 'Onion', 'Potato', 'Soybean', 'Cotton', 'Sugarcane', 'Maize', 'Mustard'];
        $packaging = ['sacks', 'crates', 'loose', 'boxes'];

        return view('farmer.requests.edit', compact('request', 'markets', 'crops', 'packaging'));
    }

    public function update(Request $request, TransportRequest $transportRequest)
    {
        if ($transportRequest->farmer_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'crop_type' => ['required', 'string', 'max:100'],
            'quantity_tons' => ['required', 'numeric', 'min:0.1', 'max:50'],
            'packaging_type' => ['required', 'in:sacks,crates,loose,boxes'],
            'pickup_address' => ['required', 'string', 'max:255'],
            'destination_market_id' => ['required', 'exists:markets,id'],
            'required_date' => ['required', 'date', 'after_or_equal:today'],
            'is_perishable' => ['boolean'],
            'special_instructions' => ['nullable', 'string', 'max:500'],
        ]);

        $validated['is_perishable'] = $request->boolean('is_perishable');

        $transportRequest->update($validated);

        return redirect()->route('farmer.requests.show', $transportRequest)
            ->with('success', __('Transport request updated.'));
    }

    public function destroy(TransportRequest $request)
    {
        if ($request->farmer_id !== auth()->id()) {
            abort(403);
        }

        if ($request->status !== 'pending') {
            return redirect()->back()->with('error', __('Cannot delete a non-pending request.'));
        }

        $request->delete();

        return redirect()->route('farmer.requests.index')
            ->with('success', __('Transport request deleted.'));
    }

    public function matches(TransportRequest $request)
    {
        if ($request->farmer_id !== auth()->id()) {
            abort(403);
        }

        $matcher = new TransportMatchingService();
        $matches = $matcher->findMatchesForRequest($request);

        return view('farmer.requests.matches', compact('request', 'matches'));
    }
}
