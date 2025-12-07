<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::with(['patient', 'doctor', 'agent'])
            ->latest()
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,agent,doctor,patient',
            'status' => 'required|in:active,pending,inactive',
            'phone' => 'nullable|string',
            // Role-specific fields
            'specialty' => 'nullable|string',
            'license_number' => 'nullable|string',
            'employee_id' => 'nullable|string',
            'city' => 'nullable|string',
            'village' => 'nullable|string',
            'age' => 'nullable|integer',
            'gender' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'status' => $validated['status'],
                'phone' => $validated['phone'] ?? null,
            ]);

            // Create role-specific records
            if ($validated['role'] === 'doctor' && !empty($validated['specialty'])) {
                Doctor::create([
                    'user_id' => $user->id,
                    'specialty' => $validated['specialty'],
                    'license_number' => $validated['license_number'] ?? null,
                ]);
            } elseif ($validated['role'] === 'agent') {
                Agent::create([
                    'user_id' => $user->id,
                    'employee_id' => $validated['employee_id'] ?? null,
                ]);
            } elseif ($validated['role'] === 'patient' && !empty($validated['city'])) {
                Patient::create([
                    'user_id' => $user->id,
                    'phone' => $validated['phone'] ?? '',
                    'city' => $validated['city'],
                    'village' => $validated['village'] ?? '',
                    'age' => $validated['age'] ?? 0,
                    'gender' => $validated['gender'] ?? null,
                ]);
            }

            return redirect()->route('admin.users.index')
                ->with('success', 'User created successfully!');
        });
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'status' => 'required|in:active,pending,inactive',
            'phone' => 'nullable|string',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account!');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully!');
    }
}
