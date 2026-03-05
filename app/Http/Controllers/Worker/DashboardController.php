<?php

namespace App\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $tasks = Task::where('assigned_to', $user->id)
            ->orderByRaw("FIELD(priority, 'urgente', 'alta', 'media', 'baja')")
            ->orderBy('due_date')
            ->paginate(10);

        $stats = [
            'total'     => Task::where('assigned_to', $user->id)->count(),
            'pendiente' => Task::where('assigned_to', $user->id)->where('status', 'pendiente')->count(),
            'en_progreso' => Task::where('assigned_to', $user->id)->where('status', 'en_progreso')->count(),
            'completada'  => Task::where('assigned_to', $user->id)->where('status', 'completada')->count(),
        ];

        return view('worker.dashboard', compact('tasks', 'stats', 'user'));
    }

    public function updateTaskStatus(Request $request, Task $task)
    {
        if ($task->assigned_to !== $request->user()->id) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pendiente,en_progreso,completada,cancelada',
        ]);

        $task->update(['status' => $request->status]);

        return back()->with('success', 'Estado de la tarea actualizado.');
    }
}
