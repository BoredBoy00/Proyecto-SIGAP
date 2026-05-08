<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Encargo;

class EncargoController extends Controller
{
    public function index()
    {
        $encargos = Encargo::orderBy('created_at', 'desc')->paginate(20);
        return view('encargos.index', compact('encargos'));
    }

    public function updateEstado(Request $request, $id)
    {
        $encargo = Encargo::findOrFail($id);
        $encargo->update(['estado' => $request->estado]);
        return redirect()->route('encargos.index')->with('success', 'Estado actualizado.');
    }
    public function destroy($id)
    {
        $rol = strtolower(auth()->user()->rol);
        if (!in_array($rol, ['administrador', 'admin', 'administrador principal'])) {
            return redirect()->route('encargos.index')->with('error', 'Sin permisos para eliminar encargos.');
        }

        $encargo = Encargo::findOrFail($id);
        $encargo->delete();

        return redirect()->route('encargos.index')->with('deleted', 'Encargo eliminado correctamente.');
    }
}
