<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ResulExamen;
use App\Models\Encargo;
use App\Models\Activity;

class ResulExamenController extends Controller
{
    private function canEdit()
    {
        return in_array(strtolower(auth()->user()->rol), ['bioanalista', 'administrador', 'admin', 'administrador principal']);
    }

    public function index(Request $request)
    {
        $query = ResulExamen::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nombre_paciente', 'LIKE', "%{$search}%")
                  ->orWhere('nombre_examen', 'LIKE', "%{$search}%")
                  ->orWhere('id', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'az':
                    $query->orderBy('nombre_paciente', 'asc');
                    break;
                case 'za':
                    $query->orderBy('nombre_paciente', 'desc');
                    break;
                case 'newest':
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $resultados = $query->paginate(15)->withQueryString();

        if ($request->filled('search') && $resultados->isEmpty()) {
            session()->now('error', "No se encontró ningún resultado coincidente con '{$request->search}'");
        }

        // Recepcionista: solo lectura (no puede crear ni eliminar)
        $readonly = in_array(strtolower(auth()->user()->rol), ['recepcionista', 'recepcion']);
        return view('resulexamen.index', compact('resultados', 'readonly'));
    }

    public function create()
    {
        if (!$this->canEdit()) {
            return redirect()->route('resulexamen.index')->with('error', 'Sin permisos para crear resultados.');
        }
        // Solo encargos pendientes o en proceso (no completados)
        $encargos = Encargo::whereIn('estado', ['Pendiente', 'En proceso'])->orderBy('created_at', 'desc')->get();
        return view('resulexamen.create', compact('encargos'));
    }

    public function store(Request $request)
    {
        if (!$this->canEdit()) {
            return redirect()->route('resulexamen.index')->with('error', 'Sin permisos.');
        }

        $validated = $request->validate([
            'id_encargo'  => 'required|exists:encargos,id',
            'observaciones' => 'nullable|string',
            'pdf'           => 'required|file|mimes:pdf|max:10240',
        ]);

        $encargo = Encargo::findOrFail($validated['id_encargo']);

        $pdfPath = $request->file('pdf')->store('resultados', 'public');

        $resultado = ResulExamen::create([
            'id_orden'        => $encargo->id_orden,
            'nombre_paciente' => $encargo->nombre_paciente,
            'nombre_examen'   => $encargo->examenes,
            'observaciones'   => $validated['observaciones'] ?? null,
            'imagen_path'     => $pdfPath,
            'bioanalista'     => auth()->user()->name . ' ' . auth()->user()->apellido,
            'fecha_resultado' => now(),
        ]);

        Activity::create([
            'user_id' => auth()->id(),
            'type' => 'Resultado',
            'description' => "Registró resultados para el paciente {$encargo->nombre_paciente}",
            'identifier' => $resultado->id,
        ]);

        // Marcar el encargo como Completado automáticamente
        $encargo->update(['estado' => 'Completado']);

        return redirect()->route('resulexamen.index')->with('success', 'Resultado registrado y encargo marcado como completado.');
    }

    public function edit($id)
    {
        if (!$this->canEdit()) {
            return redirect()->route('resulexamen.index')->with('error', 'Sin permisos para editar resultados.');
        }
        $resultado = ResulExamen::findOrFail($id);
        return view('resulexamen.edit', compact('resultado'));
    }

    public function update(Request $request, $id)
    {
        if (!$this->canEdit()) {
            return redirect()->route('resulexamen.index')->with('error', 'Sin permisos para editar resultados.');
        }

        $resultado = ResulExamen::findOrFail($id);

        $validated = $request->validate([
            'observaciones' => 'nullable|string',
            'pdf'           => 'nullable|file|mimes:pdf|max:10240',
        ]);

        if ($request->hasFile('pdf')) {
            if ($resultado->imagen_path) {
                \Storage::disk('public')->delete($resultado->imagen_path);
            }
            $resultado->imagen_path = $request->file('pdf')->store('resultados', 'public');
        }

        $resultado->observaciones = $validated['observaciones'] ?? $resultado->observaciones;
        $resultado->save();

        return redirect()->route('resulexamen.index')->with('success', 'Resultado actualizado correctamente.');
    }

    public function destroy($id)
    {
        $rol = strtolower(auth()->user()->rol);
        if (!in_array($rol, ['administrador', 'admin', 'administrador principal'])) {
            return redirect()->route('resulexamen.index')->with('error', 'Sin permisos para eliminar resultados.');
        }

        $resultado = ResulExamen::findOrFail($id);

        // Revertir el encargo asociado a "En proceso" al borrar el resultado
        if ($resultado->id_orden) {
            $encargo = Encargo::where('id_orden', $resultado->id_orden)->first();
            if ($encargo) {
                $encargo->update(['estado' => 'En proceso']);
            }
        }

        if ($resultado->imagen_path) {
            \Storage::disk('public')->delete($resultado->imagen_path);
        }

        Activity::create([
            'user_id' => auth()->id(),
            'type' => 'Resultado',
            'description' => "Eliminó los resultados del paciente {$resultado->nombre_paciente}",
            'identifier' => $resultado->id,
        ]);

        $resultado->delete();

        return redirect()->route('resulexamen.index')->with('deleted', 'Resultado y encargo asociado eliminados.');
    }
}
