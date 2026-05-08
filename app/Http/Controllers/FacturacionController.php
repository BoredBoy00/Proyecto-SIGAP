<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facturacion;
use App\Models\Orden;
use App\Models\Patient;
use App\Models\Examen;
use App\Models\Encargo;
use App\Services\BcvService;

class FacturacionController extends Controller
{
    public function index(Request $request, BcvService $bcvService)
    {
        $query = Facturacion::with(['orden.paciente', 'orden.detalles.examen']);
        $tasaBcv = $bcvService->getRate();

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

        return view('facturacion.index', compact('facturas', 'sort', 'direction', 'tasaBcv'));
    }

    public function create(BcvService $bcvService)
    {
        // Load patients with their associated 'empresa' to get the discount
        $pacientes = Patient::with('empresa')->get();
        $examenes = Examen::all();
        $tasaBcv = $bcvService->getRate();
        return view('facturacion.create', compact('pacientes', 'examenes', 'tasaBcv'));
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
            'id_orden'        => $orden->id_orden,
            'fecha_emision'   => now(),
            'subtotal'        => $validated['subtotal'],
            'monto_descuento' => $validated['monto_descuento'],
            'monto_total'     => $validated['monto_total'],
            'tipo_pago'       => $validated['tipo_pago'],
            'estatus'         => $validated['estatus']
        ]);

        // Crear encargo para el bioanalista con los exámenes de la orden
        $paciente = Patient::find($validated['id_paciente']);
        $nombresExamenes = Examen::whereIn('id_examen', $validated['examenes'])
            ->pluck('nombre_examen')->implode(', ');

        Encargo::create([
            'id_orden'        => $orden->id_orden,
            'nombre_paciente' => ($paciente->nombre ?? '') . ' ' . ($paciente->apellido ?? ''),
            'examenes'        => $nombresExamenes,
            'estado'          => 'Pendiente',
            'creado_por'      => auth()->user()->name . ' ' . auth()->user()->apellido,
        ]);

        return redirect()->route('facturacion.index')->with('success', 'Factura registrada exitosamente.');
    }

    public function edit($id)
    {
        if (!in_array(strtolower(auth()->user()->rol), ['admin', 'administrador', 'administrador principal', 'recepcionista'])) {
            return redirect()->route('facturacion.index')->with('error', 'No tienes permisos para editar facturas.');
        }

        $factura = Facturacion::with('orden.paciente')->findOrFail($id);
        return view('facturacion.edit', compact('factura'));
    }

    public function update(Request $request, $id)
    {
        if (!in_array(strtolower(auth()->user()->rol), ['admin', 'administrador', 'administrador principal', 'recepcionista'])) {
            return redirect()->route('facturacion.index')->with('error', 'No tienes permisos para editar facturas.');
        }

        $factura = Facturacion::findOrFail($id);

        $validated = $request->validate([
            'tipo_pago' => 'nullable|string|max:255',
            'estatus' => 'required|string|max:255'
        ]);

        $factura->update($validated);

        return redirect()->route('facturacion.index')->with('success', 'Factura actualizada exitosamente.');
    }

    public function destroy($id)
    {
        if (!in_array(strtolower(auth()->user()->rol), ['admin', 'administrador', 'administrador principal', 'recepcionista'])) {
            return redirect()->route('facturacion.index')->with('error', 'No tienes permisos para eliminar facturas.');
        }

        $factura = Facturacion::findOrFail($id);
        
        if ($factura->orden) {
            // Eliminar la orden en cascada eliminará la factura y los detalles automáticamente en la BD
            $factura->orden->delete();
        } else {
            // Si por alguna razón no hay orden, eliminar solo la factura
            $factura->delete();
        }

        return redirect()->route('facturacion.index')->with('success', 'Factura y orden asociada eliminadas exitosamente.');
    }
}
