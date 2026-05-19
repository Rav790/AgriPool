<?php

namespace App\Http\Controllers;

use App\Models\HelpTicket;
use Illuminate\Http\Request;

class HelpController extends Controller
{
    public function index()
    {
        if (auth()->user()->isAdmin()) {
            $tickets = HelpTicket::with('user')
                ->orderByRaw("CASE status WHEN 'open' THEN 1 WHEN 'in_progress' THEN 2 WHEN 'resolved' THEN 3 WHEN 'closed' THEN 4 ELSE 5 END")
                ->orderByDesc('created_at')
                ->paginate(15);
        } else {
            $tickets = HelpTicket::where('user_id', auth()->id())
                ->orderByDesc('created_at')
                ->paginate(10);
        }

        return view('help.index', compact('tickets'));
    }

    public function create()
    {
        return view('help.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'category' => ['required', 'in:account,payment,booking,technical,other'],
            'description' => ['required', 'string', 'max:3000'],
            'priority' => ['required', 'in:low,normal,high,urgent'],
        ]);

        HelpTicket::create([
            'user_id' => auth()->id(),
            ...$validated,
        ]);

        return redirect()->route('help.index')
            ->with('success', __('Support ticket created! We\'ll get back to you within 24 hours.'));
    }

    public function show(HelpTicket $ticket)
    {
        if (!auth()->user()->isAdmin() && $ticket->user_id !== auth()->id()) {
            abort(403);
        }

        $ticket->load('user');

        return view('help.show', compact('ticket'));
    }

    public function respond(Request $request, HelpTicket $ticket)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'admin_response' => ['required', 'string', 'max:3000'],
            'status' => ['required', 'in:in_progress,resolved,closed'],
        ]);

        $ticket->update([
            'admin_response' => $request->admin_response,
            'status' => $request->status,
            'assigned_to' => auth()->id(),
            'resolved_at' => in_array($request->status, ['resolved', 'closed']) ? now() : null,
        ]);

        return redirect()->back()->with('success', __('Response sent.'));
    }
}
