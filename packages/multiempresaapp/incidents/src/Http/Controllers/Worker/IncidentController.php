<?php

namespace MultiempresaApp\Incidents\Http\Controllers\Worker;

use App\Http\Controllers\Controller;
use MultiempresaApp\Incidents\Models\Incident;
use MultiempresaApp\Incidents\Models\IncidentComment;
use MultiempresaApp\Incidents\Notifications\NewCommentNotification;
use MultiempresaApp\Incidents\Notifications\NewIncidentNotification;
use Illuminate\Http\Request;

class IncidentController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $incidents = Incident::where('user_id', $user->id)
            ->with('user')
            ->latest()
            ->paginate(10);
        return view('worker.incidents.index', compact('incidents'));
    }

    public function create(Request $request)
    {
        $user = $request->user();
        $company = $user->company;

        if ($company && !$company->canCreateIncident()) {
            return back()->with('error', 'Has alcanzado el límite de incidencias de tu plan. Actualiza tu plan para crear más.');
        }

        return view('worker.incidents.create');
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $company = $user->company;

        if ($company && !$company->canCreateIncident()) {
            return back()->with('error', 'Has alcanzado el límite de incidencias de tu plan.');
        }

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'priority'    => 'required|in:baja,media,alta,urgente',
        ]);

        $incident = Incident::create([
            'empresa_id'  => $user->company_id,
            'user_id'     => $user->id,
            'title'       => $validated['title'],
            'description' => $validated['description'],
            'priority'    => $validated['priority'],
            'status'      => 'open',
        ]);

        // Notify company admins
        if ($company) {
            $admins = $company->users()->whereHas('roles', fn ($q) => $q->whereIn('name', ['administrador', 'superadministrador']))->get();
            foreach ($admins as $admin) {
                $admin->notify(new NewIncidentNotification($incident));
            }
        }

        return redirect()->route('incidents.show', $incident)
            ->with('success', 'Incidencia creada correctamente.');
    }

    public function show(Incident $incident, Request $request)
    {
        $user = $request->user();

        // Workers can only see their own incidents
        if ($user->isWorker() && $incident->user_id !== $user->id) {
            abort(403);
        }
        // Admin can only see their company's incidents
        if ($user->isAdmin() && $incident->empresa_id !== $user->company_id) {
            abort(403);
        }

        $incident->load(['user', 'comments.user']);
        return view('incidents.show', compact('incident'));
    }

    public function comment(Request $request, Incident $incident)
    {
        $user = $request->user();

        // Check access
        if ($user->isWorker() && $incident->user_id !== $user->id) {
            abort(403);
        }
        if ($user->isAdmin() && $incident->empresa_id !== $user->company_id) {
            abort(403);
        }

        $request->validate(['comment' => 'required|string|max:2000']);

        $comment = IncidentComment::create([
            'incident_id' => $incident->id,
            'user_id'     => $user->id,
            'comment'     => $request->comment,
        ]);

        // Notify the incident creator (if not themselves)
        if ($incident->user_id !== $user->id) {
            $incident->user->notify(new NewCommentNotification($comment));
        }

        return back()->with('success', 'Comentario añadido.');
    }
}
