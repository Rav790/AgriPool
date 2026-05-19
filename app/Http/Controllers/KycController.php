<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KycController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('kyc.index', compact('user'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'aadhaar_number' => ['required', 'string', 'max:20'],
            'pan_number' => ['nullable', 'string', 'max:20'],
            'bank_account_number' => ['nullable', 'string', 'max:30'],
            'bank_ifsc' => ['nullable', 'string', 'max:20'],
            'bank_name' => ['nullable', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'pincode' => ['required', 'string', 'max:10'],
            'aadhaar_document' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'pan_document' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        $user = auth()->user();

        // Upload files once and store paths directly in the update data
        if ($request->hasFile('aadhaar_document')) {
            $validated['aadhaar_document'] = cloudinary()->upload($request->file('aadhaar_document')->getRealPath(), [
                'folder' => 'agripool/kyc/' . $user->id
            ])->getSecurePath();
        } else {
            unset($validated['aadhaar_document']);
        }

        if ($request->hasFile('pan_document')) {
            $validated['pan_document'] = cloudinary()->upload($request->file('pan_document')->getRealPath(), [
                'folder' => 'agripool/kyc/' . $user->id
            ])->getSecurePath();
        } else {
            unset($validated['pan_document']);
        }

        $validated['kyc_status'] = 'pending';

        $user->update($validated);

        return redirect()->route('kyc.index')->with('success', __('KYC documents submitted successfully! Verification takes 1-2 business days.'));
    }
}
