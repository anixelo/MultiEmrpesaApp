<?php

namespace MultiempresaApp\Servicios\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use MultiempresaApp\Servicios\Models\Servicio;

class ServicioController extends Controller
{
    public function index(Request $request)
    {
        $empresaId = auth()->user()->company_id;
        $buscar = $request->input('buscar');

        $servicios = Servicio::deEmpresa($empresaId)
            ->when($buscar, function ($query) use ($buscar) {
                $query->where('nombre', 'like', "%{$buscar}%");
            })
            ->orderBy('orden')
            ->orderBy('nombre')
            ->paginate(15)
            ->withQueryString();

        return view('servicios::servicios.index', compact('servicios', 'buscar'));
    }

    public function create()
    {
        return view('servicios::servicios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'      => 'required|max:255',
            'descripcion' => 'nullable',
            'precio'      => 'required|numeric|min:0',
            'iva_tipo'    => 'nullable|numeric|in:0,4,10,21',
            'activo'      => 'boolean',
            'orden'       => 'nullable|integer',
        ]);

        Servicio::create(array_merge($request->only('nombre', 'descripcion', 'precio', 'iva_tipo', 'orden'), [
            'empresa_id' => auth()->user()->company_id,
            'activo'     => $request->boolean('activo', true),
        ]));

        return redirect()->route('admin.servicios.index')
            ->with('success', 'Servicio creado correctamente.');
    }

    public function show($id)
    {
        $servicio = Servicio::findOrFail($id);

        if ($servicio->empresa_id !== auth()->user()->company_id) {
            abort(403);
        }

        return view('servicios::servicios.show', compact('servicio'));
    }

    public function edit($id)
    {
        $servicio = Servicio::findOrFail($id);

        if ($servicio->empresa_id !== auth()->user()->company_id) {
            abort(403);
        }

        return view('servicios::servicios.edit', compact('servicio'));
    }

    public function update(Request $request, $id)
    {
        $servicio = Servicio::findOrFail($id);

        if ($servicio->empresa_id !== auth()->user()->company_id) {
            abort(403);
        }

        $request->validate([
            'nombre'      => 'required|max:255',
            'descripcion' => 'nullable',
            'precio'      => 'required|numeric|min:0',
            'iva_tipo'    => 'nullable|numeric|in:0,4,10,21',
            'activo'      => 'boolean',
            'orden'       => 'nullable|integer',
        ]);

        $servicio->update(array_merge($request->only('nombre', 'descripcion', 'precio', 'iva_tipo', 'orden'), [
            'activo' => $request->boolean('activo'),
        ]));

        return redirect()->route('admin.servicios.index')
            ->with('success', 'Servicio actualizado correctamente.');
    }

    public function destroy($id)
    {
        $servicio = Servicio::findOrFail($id);

        if ($servicio->empresa_id !== auth()->user()->company_id) {
            abort(403);
        }

        $servicio->delete();

        return redirect()->route('admin.servicios.index')
            ->with('success', 'Servicio eliminado correctamente.');
    }
}
