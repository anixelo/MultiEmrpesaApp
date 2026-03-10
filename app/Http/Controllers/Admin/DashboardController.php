<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use MultiempresaApp\Notas\Models\Nota;
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
                'notasByCliente' => collect(),
                'presupuestosByStatus' => collect(),
                'recentNotas' => collect(),
                'company' => null,
            ]);
        }

        $stats = [
            'total_workers'          => User::where('company_id', $company->id)->role('trabajador')->count(),
            'total_notas'            => Nota::where('empresa_id', $company->id)->count(),
            'notas_con_presupuesto'  => Nota::where('empresa_id', $company->id)->whereNotNull('presupuesto_id')->count(),
            'notas_sin_presupuesto'  => Nota::where('empresa_id', $company->id)->whereNull('presupuesto_id')->count(),
            'total_presupuestos'     => Presupuesto::where('empresa_id', $company->id)->count(),
            'presupuestos_aceptados' => Presupuesto::where('empresa_id', $company->id)->where('estado', 'aceptado')->count(),
            'presupuestos_rechazados'=> Presupuesto::where('empresa_id', $company->id)->where('estado', 'rechazado')->count(),
        ];

        $recentUsers = User::where('company_id', $company->id)
            ->with('roles')
            ->latest()
            ->take(5)
            ->get();

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

        $recentNotas = Nota::where('empresa_id', $company->id)
            ->with(['cliente'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'presupuestosByStatus', 'recentPresupuestos', 'recentNotas', 'company'));
    }
}
