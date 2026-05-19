<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Market;
use Illuminate\Http\Request;

class AdminMarketController extends Controller
{
    public function index()
    {
        $markets = Market::orderBy('state')->orderBy('name')->paginate(20);
        return view('admin.markets.index', compact('markets'));
    }

    public function create()
    {
        return view('admin.markets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'lat' => ['nullable', 'numeric'],
            'lng' => ['nullable', 'numeric'],
            'type' => ['required', 'in:mandi,wholesale,retail'],
        ]);

        Market::create($validated);
        return redirect()->route('admin.markets.index')->with('success', __('Market created.'));
    }

    public function show(Market $market)
    {
        $market->load('prices');
        return view('admin.markets.show', compact('market'));
    }

    public function edit(Market $market)
    {
        return view('admin.markets.edit', compact('market'));
    }

    public function update(Request $request, Market $market)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'type' => ['required', 'in:mandi,wholesale,retail'],
        ]);

        $market->update($validated);
        return redirect()->route('admin.markets.index')->with('success', __('Market updated.'));
    }

    public function destroy(Market $market)
    {
        $market->delete();
        return redirect()->route('admin.markets.index')->with('success', __('Market deleted.'));
    }
}
