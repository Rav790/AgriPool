<?php

namespace App\Http\Controllers;

use App\Models\PriceAlert;
use Illuminate\Http\Request;

class PriceAlertController extends Controller
{
    public function index()
    {
        $alerts = PriceAlert::where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(10);

        $crops = ['Rice', 'Wheat', 'Tomato', 'Onion', 'Potato', 'Soybean', 'Cotton', 'Sugarcane', 'Maize', 'Mustard'];

        return view('price-alerts.index', compact('alerts', 'crops'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'crop_type' => ['required', 'string', 'max:100'],
            'target_price' => ['required', 'numeric', 'min:1'],
            'condition' => ['required', 'in:above,below'],
        ]);

        $alert = PriceAlert::create([
            'user_id' => auth()->id(),
            ...$validated,
        ]);

        // Notify all other farmers and transporters
        try {
            $users = \App\Models\User::whereIn('role', ['farmer', 'transporter'])
                ->where('id', '!=', auth()->id())
                ->get();
            foreach ($users as $user) {
                $user->notify(new \App\Notifications\PriceAlertCreatedNotification($alert, auth()->user()->name));
            }
        } catch (\Exception $e) {
            // Non-critical
        }

        return redirect()->back()->with('success', __('Price alert created! We\'ll notify you when :crop goes :condition ₹:price/quintal.', [
            'crop' => $validated['crop_type'],
            'condition' => $validated['condition'],
            'price' => $validated['target_price'],
        ]));
    }

    public function destroy(PriceAlert $alert)
    {
        if ($alert->user_id !== auth()->id()) {
            abort(403);
        }

        $alert->delete();

        return redirect()->back()->with('success', __('Alert deleted.'));
    }

    public function toggle(PriceAlert $alert)
    {
        if ($alert->user_id !== auth()->id()) {
            abort(403);
        }

        $alert->update(['is_active' => !$alert->is_active]);

        return redirect()->back()->with('success', $alert->is_active ? __('Alert enabled.') : __('Alert paused.'));
    }
}
