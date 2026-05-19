<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Market;
use App\Models\MarketPrice;
use Illuminate\Http\Request;

class MarketPriceController extends Controller
{
    public function index()
    {
        $prices = MarketPrice::with('market')
            ->orderByDesc('recorded_date')
            ->paginate(20);

        return view('agent.prices.index', compact('prices'));
    }

    public function create()
    {
        $markets = Market::orderBy('name')->get();
        $crops = ['Rice', 'Wheat', 'Tomato', 'Onion', 'Potato', 'Soybean', 'Cotton', 'Sugarcane', 'Maize', 'Mustard'];

        return view('agent.prices.create', compact('markets', 'crops'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'market_id' => ['required', 'exists:markets,id'],
            'crop_type' => ['required', 'string', 'max:100'],
            'price_per_quintal' => ['required', 'numeric', 'min:1'],
            'recorded_date' => ['required', 'date'],
        ]);

        $validated['recorded_by'] = auth()->id();

        MarketPrice::create($validated);

        return redirect()->route('agent.prices.index')
            ->with('success', __('Price recorded successfully.'));
    }
}
