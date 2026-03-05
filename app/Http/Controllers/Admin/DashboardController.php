<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use MultiempresaApp\Tasks\Models\Task;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $company = $request->user()->company;

        if (! $company) {
            return view('admin.dashboard', [
                'stats' => [],
                'recentUsers' => collect(),
                'tasksByStatus' => collect(),
                'company' => null,
            ]);
        }

        $stats = [
            'total_workers'   => User::where('company_id', $company->id)->role('trabajador')->count(),
            'total_tasks'     => Task::where('company_id', $company->id)->count(),
            'pending_tasks'   => Task::where('company_id', $company->id)->where('status', 'pendiente')->count(),
            'completed_tasks' => Task::where('company_id', $company->id)->where('status', 'completada')->count(),
        ];

        $recentUsers = User::where('company_id', $company->id)
            ->with('roles')
            ->latest()
            ->take(5)
            ->get();

        $tasksByStatus = Task::where('company_id', $company->id)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        return view('admin.dashboard', compact('stats', 'recentUsers', 'tasksByStatus', 'company'));
    }
}
