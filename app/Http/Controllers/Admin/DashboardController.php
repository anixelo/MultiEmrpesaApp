<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use MultiempresaApp\Tasks\Models\Task;
use MultiempresaApp\Presupuestos\Models\Presupuesto;
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
                'presupuestosByStatus' => collect(),
                'company' => null,
            ]);
        }

        $stats = [
            'total_workers'          => User::where('company_id', $company->id)->role('trabajador')->count(),
            'total_tasks'            => Task::where('company_id', $company->id)->count(),
            'pending_tasks'          => Task::where('company_id', $company->id)->where('status', 'pendiente')->count(),
            'completed_tasks'        => Task::where('company_id', $company->id)->where('status', 'completada')->count(),
            'total_presupuestos'     => Presupuesto::where('empresa_id', $company->id)->count(),
            'presupuestos_aceptados' => Presupuesto::where('empresa_id', $company->id)->where('estado', 'aceptado')->count(),
            'presupuestos_rechazados'=> Presupuesto::where('empresa_id', $company->id)->where('estado', 'rechazado')->count(),
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

        $presupuestosByStatus = Presupuesto::where('empresa_id', $company->id)
            ->selectRaw('estado, count(*) as count')
            ->groupBy('estado')
            ->get()
            ->pluck('count', 'estado');

        $recentPresupuestos = Presupuesto::where('empresa_id', $company->id)
            ->with(['cliente', 'negocio'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'tasksByStatus', 'presupuestosByStatus', 'recentPresupuestos', 'company'));
    }
}
