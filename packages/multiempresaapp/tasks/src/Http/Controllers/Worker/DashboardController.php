<?php

namespace MultiempresaApp\Tasks\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use MultiempresaApp\Tasks\Models\Task;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $company = $user->company;

        // If the company's plan does not include tasks, show a message
        if ($company && !$company->canUseTasks()) {
            return view('worker.dashboard', [
                'tasks'        => collect(),
                'stats'        => ['total' => 0, 'pendiente' => 0, 'en_progreso' => 0, 'completada' => 0],
                'user'         => $user,
                'tasksDisabled' => true,
            ]);
        }

        $tasks = Task::where('assigned_to', $user->id)
            ->orderByRaw("FIELD(priority, 'urgente', 'alta', 'media', 'baja')")
            ->orderBy('due_date')
            ->paginate(10);

        $stats = [
            'total'       => Task::where('assigned_to', $user->id)->count(),
            'pendiente'   => Task::where('assigned_to', $user->id)->where('status', 'pendiente')->count(),
            'en_progreso' => Task::where('assigned_to', $user->id)->where('status', 'en_progreso')->count(),
            'completada'  => Task::where('assigned_to', $user->id)->where('status', 'completada')->count(),
        ];

        return view('worker.dashboard', compact('tasks', 'stats', 'user') + ['tasksDisabled' => false]);
    }

    public function updateTaskStatus(Request $request, Task $task)
    {
        $user = $request->user();
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
