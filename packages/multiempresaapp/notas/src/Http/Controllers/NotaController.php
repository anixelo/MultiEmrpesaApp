<?php

namespace MultiempresaApp\Notas\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MultiempresaApp\Clientes\Models\Cliente;
use MultiempresaApp\Notas\Models\Nota;
use MultiempresaApp\Presupuestos\Models\Presupuesto;

class NotaController extends Controller
{
    public function index(Request $request)
    {
        $empresaId = auth()->user()->company_id;
        $company   = auth()->user()->company;

        if ($company && ! $company->canUseNotas()) {
            return redirect()->route('admin.dashboard')->with('error', 'Tu plan actual no incluye gestión de notas.');
        }

        $query = Nota::with(['cliente', 'presupuesto'])
            ->deEmpresa($empresaId);

        if ($buscar = $request->input('buscar')) {
            $query->where(function ($q) use ($buscar) {
                $q->where('titulo', 'like', "%{$buscar}%")
                  ->orWhere('contenido', 'like', "%{$buscar}%")
                  ->orWhereHas('cliente', fn ($q2) => $q2->where('nombre', 'like', "%{$buscar}%"));
            });
        }

        $notas = $query->latest()->paginate(15)->withQueryString();

        return view('notas::notas.index', compact('notas'));
    }

    public function create(Request $request)
    {
        $empresaId = auth()->user()->company_id;
        $company   = auth()->user()->company;

        if ($company && ! $company->canUseNotas()) {
            return redirect()->route('admin.dashboard')->with('error', 'Tu plan actual no incluye gestión de notas.');
        }

        $clienteId = $request->query('cliente_id');

        if ($clienteId) {
            $cliente = Cliente::where('id', $clienteId)
                ->where('empresa_id', $empresaId)
                ->firstOrFail();

            return view('notas::notas.create', ['step' => 2, 'cliente' => $cliente]);
        }

        $clientes = Cliente::deEmpresa($empresaId)->orderBy('nombre')->get();

        return view('notas::notas.create', ['step' => 1, 'clientes' => $clientes]);
    }

    public function store(Request $request)
    {
        $empresaId = auth()->user()->company_id;
        $company   = auth()->user()->company;

        if ($company && ! $company->canUseNotas()) {
            return redirect()->route('admin.dashboard')->with('error', 'Tu plan actual no incluye gestión de notas.');
        }

        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'titulo'     => 'required|string|max:255',
            'contenido'  => 'nullable|string',
        ]);

        // Ensure cliente belongs to this company
        $cliente = Cliente::where('id', $validated['cliente_id'])
            ->where('empresa_id', $empresaId)
            ->firstOrFail();

        Nota::create([
            'empresa_id' => $empresaId,
            'cliente_id' => $cliente->id,
            'titulo'     => $validated['titulo'],
            'contenido'  => $validated['contenido'] ?? null,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.notas.index')
            ->with('success', 'Nota creada correctamente.');
    }

    public function show($id)
    {
        $nota = Nota::with(['cliente', 'presupuesto'])->findOrFail($id);

        if ($nota->empresa_id !== auth()->user()->company_id) {
            abort(403);
        }

        return view('notas::notas.show', compact('nota'));
    }

    public function edit($id)
    {
        $nota = Nota::with(['cliente'])->findOrFail($id);

        if ($nota->empresa_id !== auth()->user()->company_id) {
            abort(403);
        }

        if (auth()->user()->hasRole('trabajador') && $nota->created_by !== auth()->id()) {
            abort(403);
        }

        $clientes = Cliente::deEmpresa(auth()->user()->company_id)->orderBy('nombre')->get();

        return view('notas::notas.edit', compact('nota', 'clientes'));
    }

    public function update(Request $request, $id)
    {
        $nota = Nota::findOrFail($id);

        if ($nota->empresa_id !== auth()->user()->company_id) {
            abort(403);
        }

        if (auth()->user()->hasRole('trabajador') && $nota->created_by !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'titulo'     => 'required|string|max:255',
            'contenido'  => 'nullable|string',
        ]);

        // Ensure cliente belongs to this company
        $cliente = Cliente::where('id', $validated['cliente_id'])
            ->where('empresa_id', auth()->user()->company_id)
            ->firstOrFail();

        $nota->update([
            'cliente_id' => $cliente->id,
            'titulo'     => $validated['titulo'],
            'contenido'  => $validated['contenido'] ?? null,
        ]);

        return redirect()->route('admin.notas.index')
            ->with('success', 'Nota actualizada correctamente.');
    }

    public function destroy($id)
    {
        $nota = Nota::findOrFail($id);

        if ($nota->empresa_id !== auth()->user()->company_id) {
            abort(403);
        }

        if (auth()->user()->hasRole('trabajador') && $nota->created_by !== auth()->id()) {
            abort(403);
        }

        $nota->delete();

        return redirect()->route('admin.notas.index')
            ->with('success', 'Nota eliminada correctamente.');
    }

    public function crearPresupuesto($id)
    {
        $nota = Nota::with('cliente')->findOrFail($id);

        if ($nota->empresa_id !== auth()->user()->company_id) {
            abort(403);
        }

        return redirect()->route('admin.presupuestos.create', ['nota_id' => $nota->id]);
    }
}
