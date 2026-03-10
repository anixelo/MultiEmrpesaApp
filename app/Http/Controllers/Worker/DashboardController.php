<?php

namespace App\Http\Controllers\Worker;

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

        // Task data (only if tasks are enabled)
        $tasksEnabled = $company && $company->canUseTasks();
        $tasks        = collect();
        $taskStats    = ['total' => 0, 'pendiente' => 0, 'en_progreso' => 0, 'completada' => 0];

        if ($tasksEnabled) {
            $tasks = Task::where('assigned_to', $user->id)
                ->orderByRaw("FIELD(priority, 'urgente', 'alta', 'media', 'baja')")
                ->orderBy('due_date')
                ->paginate(5);

            $taskStats = [
                'total'       => Task::where('assigned_to', $user->id)->count(),
                'pendiente'   => Task::where('assigned_to', $user->id)->where('status', 'pendiente')->count(),
                'en_progreso' => Task::where('assigned_to', $user->id)->where('status', 'en_progreso')->count(),
                'completada'  => Task::where('assigned_to', $user->id)->where('status', 'completada')->count(),
            ];
        }

        $recentPresupuestos = Presupuesto::where('empresa_id', $companyId)
            ->with(['cliente'])
            ->latest()
            ->limit(5)
            ->get();

        return view('worker.dashboard', compact(
            'user', 'company',
            'incidentStats', 'recentIncidents',
            'presupuestoStats', 'presupuestosByStatus',
            'recentPresupuestos',
            'tasksEnabled', 'tasks', 'taskStats'
        ));
    }

    public function updateTaskStatus(Request $request, Task $task)
    {
        $user    = $request->user();
        $company = $user->company;

        if ($company && !$company->canUseTasks()) {
            abort(403, 'Tu plan no incluye gestión de tareas.');
        }

        if ($task->assigned_to !== $user->id) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pendiente,en_progreso,completada,cancelada',
        ]);

        $task->update(['status' => $request->status]);

        return back()->with('success', 'Estado de la tarea actualizado.');
    }
}
