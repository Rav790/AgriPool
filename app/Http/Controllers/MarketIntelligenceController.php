<?php

namespace App\Http\Controllers;

use App\Models\Market;
use App\Models\MarketPrice;
use Illuminate\Http\Request;

class MarketIntelligenceController extends Controller
{
    public function index()
    {
        $crops = ['Rice', 'Wheat', 'Tomato', 'Onion', 'Potato', 'Soybean', 'Cotton', 'Sugarcane', 'Maize', 'Mustard'];
        $markets = Market::all();

        $selectedCrop = request('crop', 'Rice');

        // Latest prices per market for the selected crop
        $latestPrices = MarketPrice::where('crop_type', $selectedCrop)
            ->whereIn('market_id', $markets->pluck('id'))
            ->whereDate('recorded_date', MarketPrice::where('crop_type', $selectedCrop)->max('recorded_date'))
            ->with('market')
            ->orderByDesc('price_per_quintal')
            ->get();

        // Best market recommendation
        $bestMarket = $latestPrices->first();

        return view('market-intelligence.index', compact('crops', 'markets', 'selectedCrop', 'latestPrices', 'bestMarket'));
    }

    public function priceData(string $crop)
    {
        // Get last 30 days of price data aggregated across markets
        $data = MarketPrice::where('crop_type', $crop)
            ->where('recorded_date', '>=', now()->subDays(30))
            ->selectRaw('recorded_date, AVG(price_per_quintal) as avg_price, MIN(price_per_quintal) as min_price, MAX(price_per_quintal) as max_price')
            ->groupBy('recorded_date')
            ->orderBy('recorded_date')
            ->get()
            ->map(function ($item) {
                $item->recorded_date = \Carbon\Carbon::parse($item->recorded_date)->format('M d');
                return $item;
            });

        return response()->json($data);
    }
}
