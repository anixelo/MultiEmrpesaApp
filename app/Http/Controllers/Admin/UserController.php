<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $admin = $request->user();
        $company = $admin->company;

        if (!$company) {
            return redirect()->route('admin.dashboard')->with('error', 'No tienes una empresa asignada.');
        }

        $plan = $company->subscription?->plan;
        $maxUsers = $plan?->max_users ?? '∞';
        $currentCount = $company->users()->count();

        $users = User::where('company_id', $company->id)
            ->with('roles')
            ->latest()
            ->paginate(15);

        return view('admin.users.index', compact('users', 'company', 'maxUsers', 'currentCount'));
    }

    public function create(Request $request)
    {
        $admin = $request->user();
        $company = $admin->company;

        if (!$company) {
            return redirect()->route('admin.dashboard')->with('error', 'No tienes una empresa asignada.');
        }

        if (!$company->canAddUser()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Has alcanzado el límite de usuarios de tu plan. Actualiza tu plan para añadir más usuarios.');
        }

        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $admin = $request->user();
        $company = $admin->company;

        if (!$company) {
            return redirect()->route('admin.dashboard')->with('error', 'No tienes una empresa asignada.');
        }

        if (!$company->canAddUser()) {
            return back()->with('error', 'Has alcanzado el límite de usuarios de tu plan.');
        }

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|in:trabajador,administrador',
        ]);

        $user = User::create([
            'name'              => $validated['name'],
            'email'             => $validated['email'],
            'password'          => Hash::make($validated['password']),
            'company_id'        => $company->id,
            'email_verified_at' => now(),
        ]);

        $user->assignRole($validated['role']);

        return redirect()->route('admin.users.index')->with('success', 'Usuario creado correctamente.');
    }

    public function edit(Request $request, User $user)
    {
        $admin = $request->user();
        $company = $admin->company;

        if (!$company || $user->company_id !== $company->id) {
            abort(403);
        }

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $admin = $request->user();
        $company = $admin->company;

        if (!$company || $user->company_id !== $company->id) {
            abort(403);
        }

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role'     => 'required|in:trabajador,administrador',
        ]);

        $user->update([
            'name'  => $validated['name'],
            'email' => $validated['email'],
        ]);

        if (!empty($validated['password'])) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        $user->syncRoles([$validated['role']]);

        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(Request $request, User $user)
    {
        $admin = $request->user();
        $company = $admin->company;

        if (!$company || $user->company_id !== $company->id) {
            abort(403);
        }

        // Prevent deleting yourself
        if ($user->id === $admin->id) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
