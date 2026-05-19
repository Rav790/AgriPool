<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class FareCalculatorController extends Controller
{
    public function index()
    {
        return view('fare-calculator.index');
    }

    public function calculate(Request $request)
    {
        $validated = $request->validate([
            'distance_km' => ['required', 'numeric', 'min:1', 'max:5000'],
            'weight_tons' => ['required', 'numeric', 'min:0.1', 'max:50'],
            'vehicle_type' => ['required', 'in:truck,mini-truck,pickup,tractor'],
            'is_perishable' => ['boolean'],
        ]);

        // Dynamic pricing model
        $baseRates = [
            'truck' => 12,       // ₹/km/ton
            'mini-truck' => 15,
            'pickup' => 20,
            'tractor' => 10,
        ];

        $baseRate = $baseRates[$validated['vehicle_type']];
        $baseCost = $validated['distance_km'] * $validated['weight_tons'] * $baseRate;

        // Perishable surcharge
        $perishableSurcharge = $request->boolean('is_perishable') ? $baseCost * 0.15 : 0;

        // Platform fee (5%)
        $platformFee = $baseCost * 0.05;

        // Insurance (2%)
        $insurance = $baseCost * 0.02;

        $totalCost = $baseCost + $perishableSurcharge + $platformFee + $insurance;

        // Pooling savings estimate (average 35%)
        $pooledCost = $totalCost * 0.65;
        $savings = $totalCost - $pooledCost;

        return response()->json([
            'base_cost' => round($baseCost, 2),
            'perishable_surcharge' => round($perishableSurcharge, 2),
            'platform_fee' => round($platformFee, 2),
            'insurance' => round($insurance, 2),
            'total_cost' => round($totalCost, 2),
            'pooled_cost' => round($pooledCost, 2),
            'savings' => round($savings, 2),
            'savings_percent' => 35,
            'rate_per_km' => round($baseRate * $validated['weight_tons'], 2),
        ]);
    }
}
