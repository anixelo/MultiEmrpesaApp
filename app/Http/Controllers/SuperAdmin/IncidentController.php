<?php
namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Incident;
use App\Models\IncidentComment;
use App\Notifications\IncidentResolvedNotification;
use App\Notifications\NewCommentNotification;
use Illuminate\Http\Request;

class IncidentController extends Controller
{
    public function index(Request $request)
    {
        $query = Incident::with(['user', 'company']);

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        if ($company = $request->get('company_id')) {
            $query->where('empresa_id', $company);
        }

        $incidents = $query->latest()->paginate(20);
        return view('superadmin.incidents.index', compact('incidents'));
    }

    public function show(Incident $incident)
    {
        $incident->load(['user', 'company', 'comments.user']);
        return view('incidents.show', compact('incident'));
    }

    public function updateStatus(Request $request, Incident $incident)
    {
        $request->validate([
            'status' => 'required|in:open,in_review,in_progress,resolved,closed',
        ]);

        $oldStatus = $incident->status;
        $incident->update(['status' => $request->status]);

        if ($request->status === 'resolved' && $oldStatus !== 'resolved') {
            $incident->user->notify(new IncidentResolvedNotification($incident));
        }

        return back()->with('success', 'Estado actualizado correctamente.');
    }

    public function comment(Request $request, Incident $incident)
    {
        $request->validate(['comment' => 'required|string|max:2000']);

        $comment = IncidentComment::create([
            'incident_id' => $incident->id,
            'user_id' => $request->user()->id,
            'comment' => $request->comment,
        ]);

        if ($incident->user_id !== $request->user()->id) {
            $incident->user->notify(new NewCommentNotification($comment));
        }

        return back()->with('success', 'Comentario añadido.');
    }
}
