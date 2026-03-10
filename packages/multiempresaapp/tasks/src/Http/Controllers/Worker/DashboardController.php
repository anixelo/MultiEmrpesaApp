<?php

namespace MultiempresaApp\Tasks\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use MultiempresaApp\Incidents\Models\Incident;
use MultiempresaApp\Tasks\Models\Task;
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
            'total'      => Presupuesto::where('empresa_id', $companyId)->count(),
            'aceptados'  => Presupuesto::where('empresa_id', $companyId)->where('estado', 'aceptado')->count(),
            'rechazados' => Presupuesto::where('empresa_id', $companyId)->where('estado', 'rechazado')->count(),
        ];

        $presupuestosByStatus = Presupuesto::where('empresa_id', $companyId)
            ->selectRaw('estado, count(*) as count')
            ->groupBy('estado')
            ->get()
            ->pluck('count', 'estado');


        return view('worker.dashboard', compact(
            'user', 'company',
            'incidentStats', 'recentIncidents',
            'presupuestoStats', 'presupuestosByStatus'
        ));
    }


}
