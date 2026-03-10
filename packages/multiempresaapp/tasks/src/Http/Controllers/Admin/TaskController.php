<?php

namespace MultiempresaApp\Tasks\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use MultiempresaApp\Tasks\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('admin.dashboard')->with('error', 'No tienes una empresa asignada.');
        }


        $tasks = Task::where('company_id', $company->id)
            ->with(['assignedUser', 'creator'])
            ->latest()
            ->paginate(15);

        return view('admin.tasks.index', compact('tasks', 'company'));
    }

    public function create(Request $request)
    {
        $user = $request->user();
        $company = $user->company;

        if (!$company) {
            return redirect()->route('admin.dashboard')->with('error', 'No tienes una empresa asignada.');
        }


        $workers = User::where('company_id', $company->id)
            ->whereHas('roles', fn ($q) => $q->whereIn('name', ['trabajador', 'administrador']))
            ->orderBy('name')
            ->get();

        return view('admin.tasks.create', compact('workers'));
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $company = $user->company;

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'priority'    => 'required|in:baja,media,alta,urgente',
            'status'      => 'required|in:pendiente,en_progreso,completada,cancelada',
            'due_date'    => 'nullable|date',
        ]);

        // Verify assigned_to belongs to the same company
        if (!empty($validated['assigned_to'])) {
            $assignee = User::where('id', $validated['assigned_to'])
                ->where('company_id', $company->id)
                ->first();
            if (!$assignee) {
                return back()->withErrors(['assigned_to' => 'El usuario seleccionado no pertenece a tu empresa.'])->withInput();
            }
        }

        Task::create(array_merge($validated, [
            'company_id' => $company->id,
            'created_by' => $user->id,
        ]));

        return redirect()->route('admin.tasks.index')->with('success', 'Tarea creada correctamente.');
    }

    public function edit(Request $request, Task $task)
    {
        $user = $request->user();
        $company = $user->company;

        if (!$company || $task->company_id !== $company->id) {
            abort(403);
        }

        $workers = User::where('company_id', $company->id)
            ->whereHas('roles', fn ($q) => $q->whereIn('name', ['trabajador', 'administrador']))
            ->orderBy('name')
            ->get();

        return view('admin.tasks.edit', compact('task', 'workers'));
    }

    public function update(Request $request, Task $task)
    {
        $user = $request->user();
        $company = $user->company;

        if (!$company || $task->company_id !== $company->id) {
            abort(403);
        }

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'priority'    => 'required|in:baja,media,alta,urgente',
            'status'      => 'required|in:pendiente,en_progreso,completada,cancelada',
            'due_date'    => 'nullable|date',
        ]);

        if (!empty($validated['assigned_to'])) {
            $assignee = User::where('id', $validated['assigned_to'])
                ->where('company_id', $company->id)
                ->first();
            if (!$assignee) {
                return back()->withErrors(['assigned_to' => 'El usuario seleccionado no pertenece a tu empresa.'])->withInput();
            }
        }

        $task->update($validated);

        return redirect()->route('admin.tasks.index')->with('success', 'Tarea actualizada correctamente.');
    }

    public function destroy(Task $task, Request $request)
    {
        $user = $request->user();
        $company = $user->company;

        if (!$company || $task->company_id !== $company->id) {
            abort(403);
        }

        $task->delete();

        return redirect()->route('admin.tasks.index')->with('success', 'Tarea eliminada correctamente.');
    }
}
