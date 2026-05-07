<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;

class ConvController extends Controller
{

    public function index(Request $request)
    {
        $query = Empresa::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nombre_empresa', 'LIKE', "%{$search}%")
                  ->orWhere('id_empresa', 'LIKE', "%{$search}%")
                  ->orWhere('rif', 'LIKE', "%{$search}%");
        }

        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'az':
                    $query->orderBy('nombre_empresa', 'asc');
                    break;
                case 'za':
                    $query->orderBy('nombre_empresa', 'desc');
                    break;
                case 'newest':
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            // Orden por defecto
            $query->orderBy('created_at', 'desc');
        }

        $empresas = $query->paginate(10)->withQueryString();
        return view('convenios.index', compact('empresas'));
    }

    public function create()
    {
        return view('convenios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_empresa' => 'required|string|max:255',
            'rif' => 'required|string|max:20|unique:empresas,rif',
            'direccion' => 'required|string|max:255',
            'porcentaje_descuento' => 'required|numeric|min:1|max:20',
        ]);

        Empresa::create([
            'nombre_empresa' => $request->nombre_empresa,
            'rif' => $request->rif,
            'direccion' => $request->direccion,
            'porcentaje_descuento' => $request->porcentaje_descuento,
        ]);

        return redirect()->route('convens.index')->with('success', 'Convenio creado exitosamente.');
    }

    public function edit($id)
    {
        $empresa = Empresa::findOrFail($id);
        return view('convenios.edit', compact('empresa'));
    }

    public function update(Request $request, $id)
    {
        $empresa = Empresa::findOrFail($id);

        $request->validate([
            'nombre_empresa' => 'required|string|max:255',
            'rif' => 'required|string|max:20|unique:empresas,rif,' . $id . ',id_empresa',
            'direccion' => 'required|string|max:255',
            'porcentaje_descuento' => 'required|numeric|min:1|max:20',
        ]);

        $empresa->update([
            'nombre_empresa' => $request->nombre_empresa,
            'rif' => $request->rif,
            'direccion' => $request->direccion,
            'porcentaje_descuento' => $request->porcentaje_descuento,
        ]);

        return redirect()->route('convens.index')->with('success', 'Convenio actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $empresa = Empresa::findOrFail($id);
        $empresa->delete();
        return redirect()->route('convens.index')->with('success', 'Convenio eliminado exitosamente.');
    }
}
