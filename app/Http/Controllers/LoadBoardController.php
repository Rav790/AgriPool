<?php

namespace App\Http\Controllers;

use App\Models\TransportListing;
use App\Models\TransportRequest;
use Illuminate\Http\Request;

class LoadBoardController extends Controller
{
    public function index(Request $request)
    {
        // Public load board showing available listings and open requests
        $listings = TransportListing::with('transporter')
            ->where('status', '!=', 'completed')
            ->where('remaining_capacity', '>', 0)
            ->when($request->route_from, fn($q, $v) => $q->where('route_from', 'like', "%{$v}%"))
            ->when($request->route_to, fn($q, $v) => $q->where('route_to', 'like', "%{$v}%"))
            ->when($request->min_capacity, fn($q, $v) => $q->where('remaining_capacity', '>=', $v))
            ->orderBy('available_date')
            ->paginate(12);

        $openRequests = TransportRequest::with(['farmer', 'destinationMarket'])
            ->where('status', 'pending')
            ->when($request->crop, fn($q, $v) => $q->where('crop_type', $v))
            ->orderBy('required_date')
            ->paginate(12);

        $crops = ['Rice', 'Wheat', 'Tomato', 'Onion', 'Potato', 'Soybean', 'Cotton', 'Sugarcane', 'Maize', 'Mustard'];

        return view('load-board.index', compact('listings', 'openRequests', 'crops'));
    }
}
