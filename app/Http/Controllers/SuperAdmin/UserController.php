<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['company', 'roles']);

        if ($search = $request->get('search')) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        if ($role = $request->get('role')) {
            $query->role($role);
        }

        if ($company = $request->get('company_id')) {
            $query->where('company_id', $company);
        }

        $users = $query->latest()->paginate(15)->withQueryString();
        $roles = Role::all();
        $companies = Company::where('active', true)->get();

        return view('superadmin.users.index', compact('users', 'roles', 'companies'));
    }

    public function create()
    {
        $roles = Role::all();
        $companies = Company::where('active', true)->get();

        return view('superadmin.users.create', compact('roles', 'companies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|string|min:8|confirmed',
            'company_id' => 'nullable|exists:companies,id',
            'role'       => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name'              => $validated['name'],
            'email'             => $validated['email'],
            'password'          => bcrypt($validated['password']),
            'company_id'        => $validated['company_id'] ?? null,
            'email_verified_at' => now(),
        ]);

        $user->assignRole($validated['role']);

        return redirect()->route('superadmin.users.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $companies = Company::where('active', true)->get();
        $userRole = $user->roles->first()?->name;

        return view('superadmin.users.edit', compact('user', 'roles', 'companies', 'userRole'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'password'   => 'nullable|string|min:8|confirmed',
            'company_id' => 'nullable|exists:companies,id',
            'role'       => 'required|exists:roles,name',
        ]);

        $updateData = [
            'name'       => $validated['name'],
            'email'      => $validated['email'],
            'company_id' => $validated['company_id'] ?? null,
        ];

        if (! empty($validated['password'])) {
            $updateData['password'] = bcrypt($validated['password']);
        }

        $user->update($updateData);
        $user->syncRoles([$validated['role']]);

        return redirect()->route('superadmin.users.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $user->delete();

        return redirect()->route('superadmin.users.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }
}
