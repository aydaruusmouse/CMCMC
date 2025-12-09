<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required',
        ]);

        // Find user by phone
        $user = \App\Models\User::where('phone', $request->phone)->first();

        if ($user && \Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
            if (!$user->isActive()) {
                throw ValidationException::withMessages([
                    'phone' => 'Your account is not active. Please contact administrator.',
                ]);
            }

            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            return $this->redirectToRole($user->role);
        }

        throw ValidationException::withMessages([
            'phone' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    protected function redirectToRole(string $role): \Illuminate\Http\RedirectResponse
    {
        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'agent' => redirect()->route('agent.dashboard'),
            'doctor' => redirect()->route('doctor.dashboard'),
            'patient' => redirect()->route('patient.dashboard'),
            default => redirect('/'),
        };
    }
}