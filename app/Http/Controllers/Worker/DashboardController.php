<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use MultiempresaApp\Incidents\Models\Incident;
use MultiempresaApp\Notas\Models\Nota;
use MultiempresaApp\Presupuestos\Models\Presupuesto;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user    = $request->user();
        $company = $user->company;
        $companyId = $company ? $company->id : 0;

        // Incident stats for this worker
        $incidentStats = [
            'total'       => Incident::where('user_id', $user->id)->count(),
            'open'        => Incident::where('user_id', $user->id)->where('status', 'open')->count(),
            'in_progress' => Incident::where('user_id', $user->id)->whereIn('status', ['in_review', 'in_progress'])->count(),
            'resolved'    => Incident::where('user_id', $user->id)->whereIn('status', ['resolved', 'closed'])->count(),
        ];

        $recentIncidents = Incident::where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        // Presupuesto stats for this company
        $presupuestoStats = [
            'total'                  => Presupuesto::where('empresa_id', $companyId)->count(),
            'aceptados'              => Presupuesto::where('empresa_id', $companyId)->where('estado', 'aceptado')->count(),
            'rechazados'             => Presupuesto::where('empresa_id', $companyId)->where('estado', 'rechazado')->count(),
            'pendiente_revision'     => Presupuesto::where('empresa_id', $companyId)->where('estado', 'pendiente_revision')->count(),
        ];

        $presupuestosByStatus = Presupuesto::where('empresa_id', $companyId)
            ->selectRaw('estado, count(*) as count')
            ->groupBy('estado')
            ->get()
            ->pluck('count', 'estado');

        $recentPresupuestos = Presupuesto::where('empresa_id', $companyId)
            ->with(['cliente'])
            ->latest()
            ->take(5)
            ->get();

        // Notas (only if notes are enabled)
        $notasEnabled = $company && $company->canUseNotas();
        $recentNotas  = collect();
        $notaStats    = ['total' => 0, 'con_presupuesto' => 0, 'sin_presupuesto' => 0];

        if ($notasEnabled) {
            $recentNotas = Nota::where('empresa_id', $companyId)
                ->with(['cliente', 'presupuesto'])
                ->latest()
                ->take(5)
                ->get();

            $notaStats = [
                'total'           => Nota::where('empresa_id', $companyId)->count(),
                'con_presupuesto' => Nota::where('empresa_id', $companyId)->whereNotNull('presupuesto_id')->count(),
                'sin_presupuesto' => Nota::where('empresa_id', $companyId)->whereNull('presupuesto_id')->count(),
            ];
        }

        $canUseEnvioEnlace = $company ? $company->canUseEnvioEnlace() : false;
        $revisarPresupuestos = $user->revisar_presupuestos;

        return view('worker.dashboard', compact(
            'user', 'company',
            'incidentStats', 'recentIncidents',
            'presupuestoStats', 'presupuestosByStatus',
            'recentPresupuestos',
            'notasEnabled', 'recentNotas', 'notaStats',
            'canUseEnvioEnlace', 'revisarPresupuestos'
        ));
    }
}
