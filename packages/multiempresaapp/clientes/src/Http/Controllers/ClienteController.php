<?php

namespace MultiempresaApp\Clientes\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use MultiempresaApp\Clientes\Models\Cliente;
use MultiempresaApp\Presupuestos\Models\Presupuesto;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $empresaId = auth()->user()->company_id;
        $buscar = $request->input('buscar');

        $clientes = Cliente::deEmpresa($empresaId)
            ->when($buscar, function ($query) use ($buscar) {
                $query->where(function ($q) use ($buscar) {
                    $q->where('nombre', 'like', "%{$buscar}%")
                      ->orWhere('email', 'like', "%{$buscar}%")
                      ->orWhere('telefono', 'like', "%{$buscar}%");
                });
            })
            ->orderBy('nombre')
            ->paginate(15)
            ->withQueryString();

        return view('clientes::clientes.index', compact('clientes', 'buscar'));
    }

    public function create()
    {
        return view('clientes::clientes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'   => 'required|max:255',
            'email'    => 'nullable|email|max:255',
            'telefono' => 'nullable|max:50',
            'notas'    => 'nullable',
        ]);

        Cliente::create(array_merge($request->only('nombre', 'email', 'telefono', 'notas'), [
            'empresa_id' => auth()->user()->company_id,
            'created_by' => auth()->id(),
        ]));

        return redirect()->route('admin.clientes.index')
            ->with('success', 'Cliente creado correctamente.');
    }

    public function show($id)
    {
        $cliente = Cliente::findOrFail($id);

        if ($cliente->empresa_id !== auth()->user()->company_id) {
            abort(403);
        }

        $presupuestos = Presupuesto::with(['negocio'])
            ->where('empresa_id', auth()->user()->company_id)
            ->where('cliente_id', $cliente->id)
            ->latest()
            ->get();

        return view('clientes::clientes.show', compact('cliente', 'presupuestos'));
    }

    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);

        if ($cliente->empresa_id !== auth()->user()->company_id) {
            abort(403);
        }

        return view('clientes::clientes.edit', compact('cliente'));
    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        if ($cliente->empresa_id !== auth()->user()->company_id) {
            abort(403);
        }

        $request->validate([
            'nombre'   => 'required|max:255',
            'email'    => 'nullable|email|max:255',
            'telefono' => 'nullable|max:50',
            'notas'    => 'nullable',
        ]);

        $cliente->update($request->only('nombre', 'email', 'telefono', 'notas'));

        return redirect()->route('admin.clientes.index')
            ->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);

        if ($cliente->empresa_id !== auth()->user()->company_id) {
            abort(403);
        }

        if (auth()->user()->hasRole('trabajador') && $cliente->created_by !== auth()->id()) {
            return redirect()->route('admin.clientes.index')
                ->with('error', 'No puedes eliminar clientes que no hayas creado tú.');
        }

        $cliente->delete();

        return redirect()->route('admin.clientes.index')
            ->with('success', 'Cliente eliminado correctamente.');
    }

    public function quickStore(Request $request)
    {
        $request->validate([
            'nombre'   => 'required',
            'email'    => 'nullable|email',
            'telefono' => 'nullable',
        ]);

        $cliente = Cliente::create([
            'empresa_id' => auth()->user()->company_id,
            'nombre'     => $request->nombre,
            'email'      => $request->email,
            'telefono'   => $request->telefono,
            'created_by' => auth()->id(),
        ]);

        return response()->json([
            'id'     => $cliente->id,
            'nombre' => $cliente->nombre,
        ]);
    }
}