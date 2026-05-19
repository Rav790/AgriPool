<?php

namespace App\Http\Controllers;

use App\Models\CooperativeGroup;
use Illuminate\Http\Request;

class CooperativeController extends Controller
{
    public function index()
    {
        $myGroups = auth()->user()->cooperativeGroups()
            ->withCount('members')
            ->get();

        return view('cooperatives.index', compact('myGroups'));
    }

    public function create()
    {
        return view('cooperatives.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'region' => ['required', 'string', 'max:255'],
        ]);

        $group = CooperativeGroup::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'created_by' => auth()->id(),
            'region' => $validated['region'],
            'invite_code' => CooperativeGroup::generateInviteCode(),
            'member_count' => 1,
        ]);

        $group->members()->attach(auth()->id(), ['role' => 'admin']);

        return redirect()->route('farmer.cooperatives.show', $group)
            ->with('success', __('Cooperative created! Share code: :code', ['code' => $group->invite_code]));
    }

    public function show(CooperativeGroup $cooperative)
    {
        $cooperative->load(['members', 'creator', 'messages.user']);

        if (!$cooperative->members->contains(auth()->user())) {
            abort(403);
        }

        return view('cooperatives.show', compact('cooperative'));
    }

    public function join(Request $request)
    {
        $request->validate([
            'invite_code' => ['required', 'string', 'size:8'],
        ]);

        $group = CooperativeGroup::where('invite_code', $request->invite_code)->firstOrFail();

        if ($group->members->contains(auth()->user())) {
            return redirect()->back()->with('error', __('You are already a member.'));
        }

        $group->members()->attach(auth()->id(), ['role' => 'member']);
        $group->update(['member_count' => $group->members()->count()]);

        return redirect()->route('farmer.cooperatives.show', $group)
            ->with('success', __('Joined :name successfully!', ['name' => $group->name]));
    }

    public function leave(CooperativeGroup $cooperative)
    {
        $cooperative->members()->detach(auth()->id());
        $cooperative->update(['member_count' => $cooperative->members()->count()]);

        return redirect()->route('farmer.cooperatives.index')
            ->with('success', __('Left :name.', ['name' => $cooperative->name]));
    }

    public function sendMessage(Request $request, CooperativeGroup $cooperative)
    {
        if (!$cooperative->members->contains(auth()->user())) {
            abort(403);
        }

        $request->validate([
            'message' => ['required_without:attachment', 'nullable', 'string', 'max:2000'],
            'attachment' => ['nullable', 'file', 'max:10240'], // Max 10MB
        ]);

        $path = null;
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('cooperative_attachments', 'public');
        }

        $cooperative->messages()->create([
            'user_id' => auth()->id(),
            'message' => $request->message,
            'attachment_path' => $path,
        ]);

        return redirect()->back()->with('success', __('Message posted to group!'));
    }
}
