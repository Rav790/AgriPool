<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\KycStatusUpdatedNotification;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%")
                    ->orWhere('phone', 'like', "%{$request->search}%");
            });
        }

        $users = $query->orderByDesc('created_at')->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load([
            'farmerProfile', 
            'transporterProfile', 
            'reviewsReceived',
            'farmerBookings.transportRequest',
            'transporterBookings.transportListing'
        ]);
        return view('admin.users.show', compact('user'));
    }

    public function verify(User $user)
    {
        $user->update(['is_verified' => true]);
        if ($user->transporterProfile) {
            $user->transporterProfile->update(['is_verified' => true]);
        }
        return redirect()->back()->with('success', __(':name verified.', ['name' => $user->name]));
    }

    public function suspend(User $user)
    {
        $user->update(['is_verified' => false]);
        return redirect()->back()->with('success', __(':name suspended.', ['name' => $user->name]));
    }

    public function verifyKyc(User $user)
    {
        $user->update(['kyc_status' => 'verified']);
        $user->notify(new KycStatusUpdatedNotification(__('Your KYC documents have been successfully verified!')));
        return redirect()->back()->with('success', __('KYC verified successfully for :name.', ['name' => $user->name]));
    }

    public function rejectKyc(User $user)
    {
        $user->update(['kyc_status' => 'rejected']);
        $user->notify(new KycStatusUpdatedNotification(__('Your KYC submission was rejected. Please review your documents and submit again.')));
        return redirect()->back()->with('success', __('KYC rejected for :name.', ['name' => $user->name]));
    }
}
