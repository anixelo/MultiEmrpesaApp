<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Task;
use App\Models\User;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'     => User::count(),
            'total_companies' => Company::count(),
            'total_tasks'     => Task::count(),
            'total_roles'     => Role::count(),
            'active_companies' => Company::where('active', true)->count(),
            'pending_tasks'   => Task::where('status', 'pendiente')->count(),
        ];

        $recentUsers = User::with(['company', 'roles'])->latest()->take(5)->get();
        $recentCompanies = Company::withCount('users')->latest()->take(5)->get();

        return view('superadmin.dashboard', compact('stats', 'recentUsers', 'recentCompanies'));
    }
}
