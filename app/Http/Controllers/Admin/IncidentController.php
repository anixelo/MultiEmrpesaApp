<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Incident;
use Illuminate\Http\Request;

class IncidentController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Incident::where('empresa_id', $user->company_id)->with('user');

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $incidents = $query->latest()->paginate(15);
        return view('admin.incidents.index', compact('incidents'));
    }

    public function show(Incident $incident, Request $request)
    {
        $user = $request->user();
        if ($incident->empresa_id !== $user->company_id) {
            abort(403);
        }
        $incident->load(['user', 'comments.user']);
        return view('incidents.show', compact('incident'));
    }
}
