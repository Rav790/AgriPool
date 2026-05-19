<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\FarmerProfile;
use App\Models\TransporterProfile;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:15'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:farmer,transporter,agent'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Assign Spatie role
        $user->assignRole($request->role);

        // Create role-specific profile
        if ($request->role === 'farmer') {
            FarmerProfile::create(['user_id' => $user->id]);
        } elseif ($request->role === 'transporter') {
            TransporterProfile::create(['user_id' => $user->id]);
        }

        // Create wallet for all users
        Wallet::create(['user_id' => $user->id, 'balance' => 0]);

        event(new Registered($user));

        Auth::login($user);

        // Redirect to role-based dashboard
        return redirect($this->dashboardRoute($user->role));
    }

    /**
     * Get dashboard route based on user role.
     */
    private function dashboardRoute(string $role): string
    {
        return match ($role) {
            'farmer' => route('farmer.dashboard', absolute: false),
            'transporter' => route('transporter.dashboard', absolute: false),
            'agent' => route('agent.dashboard', absolute: false),
            'admin' => route('admin.dashboard', absolute: false),
            default => route('dashboard', absolute: false),
        };
    }
}
