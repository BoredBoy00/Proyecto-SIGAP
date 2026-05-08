<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facturacion;
use App\Models\Orden;
use App\Models\Patient;
use App\Models\Examen;

class FacturacionController extends Controller
{
    public function index(Request $request)
    {
        $query = Facturacion::with('orden');

        // Búsqueda (opcional, si hay filtros)
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('id_facturacion', 'like', "%{$search}%")
                  ->orWhere('id_orden', 'like', "%{$search}%")
                  ->orWhere('tipo_pago', 'like', "%{$search}%")
                  ->orWhere('estatus', 'like', "%{$search}%");
        }

        // Ordenamiento
        $sort = $request->get('sort', 'fecha_emision');
        $direction = $request->get('direction', 'desc');

        $facturas = $query->orderBy($sort, $direction)->paginate(10);

        return view('facturacion.index', compact('facturas', 'sort', 'direction'));
    }

    public function create()
    {
        // Load patients with their associated 'empresa' to get the discount
        $pacientes = Patient::with('empresa')->get();
        $examenes = Examen::all();
        return view('facturacion.create', compact('pacientes', 'examenes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_paciente' => 'required|exists:pacientes,id',
            'examenes' => 'required|array',
            'examenes.*' => 'exists:examenes,id_examen',
            'subtotal' => 'required|numeric|min:0',
            'monto_descuento' => 'required|numeric|min:0',
            'monto_total' => 'required|numeric|min:0',
            'tipo_pago' => 'nullable|string|max:255',
            'estatus' => 'required|string|max:255'
        ]);

        // Crear la Orden
        // Nota: asumo que la tabla ordenes usa 'id_usuario' pero en realidad no requiere obligatoriamente uno si es nullable, o podemos guardar el id del paciente ahi si la base de datos lo permite.
        // Pero ordenes.id_usuario referencia a users.id, así que no podemos poner el id_paciente ahí a menos que cambiemos la bd. 
        // Por ahora lo dejaremos nulo, o le asignamos el auth()->id() (quien la creó).
        $orden = Orden::create([
            'fecha' => now(),
            'total_monto' => $validated['monto_total'],
            'id_usuario' => auth()->id(),
            'id_paciente' => $validated['id_paciente']
        ]);

        // Crear los Detalles (exámenes)
        foreach ($validated['examenes'] as $id_examen) {
            \App\Models\Detalle::create([
                'id_orden' => $orden->id_orden,
                'id_examen' => $id_examen
            ]);
        }

        // Crear la Facturación
        Facturacion::create([
            'id_orden' => $orden->id_orden,
            'fecha_emision' => now(),
            'subtotal' => $validated['subtotal'],
            'monto_descuento' => $validated['monto_descuento'],
            'monto_total' => $validated['monto_total'],
            'tipo_pago' => $validated['tipo_pago'],
            'estatus' => $validated['estatus']
        ]);

        // Además, si facturacion se asocia al paciente de alguna manera, actualmente 'facturacion' y 'ordenes' no tienen 'id_paciente'. 
        // Sería ideal agregarlo a 'ordenes' o 'facturacion'. Pero por ahora se guardará la factura.

        return redirect()->route('facturacion.index')->with('success', 'Factura registrada exitosamente.');
    }
}
