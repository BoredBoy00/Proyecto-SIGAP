<?php

namespace App\Http\Controllers;

use App\Models\Examen;
use App\Models\Activity;
use App\Services\BcvService;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index(BcvService $bcvService)
    {
        $examenes = Examen::all();
        $tasaBcv = $bcvService->getRate();

        return view('exams.index', compact('examenes', 'tasaBcv'));
    }

    public function store(Request $request)
    {
        $examen = Examen::create($this->validateExamen($request));

        Activity::create([
            'user_id' => auth()->id(),
            'type' => 'Examen',
            'description' => "Creó el examen: {$examen->nombre_examen}",
            'identifier' => $examen->codigo_examen,
        ]);

        return redirect()->route('exams.index')->with('success', 'Examen creado exitosamente.');
    }

    public function update(Request $request, $id)
    {
        $examen = Examen::findOrFail($id);
        $examen->update($this->validateExamen($request, $id));

        Activity::create([
            'user_id' => auth()->id(),
            'type' => 'Examen',
            'description' => "Actualizó el examen: {$examen->nombre_examen}",
            'identifier' => $examen->codigo_examen,
        ]);

        return redirect()->route('exams.index')->with('success', 'Examen actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $examen = Examen::findOrFail($id);
        
        Activity::create([
            'user_id' => auth()->id(),
            'type' => 'Examen',
            'description' => "Eliminó el examen: {$examen->nombre_examen}",
            'identifier' => $examen->codigo_examen,
        ]);

        $examen->delete();

        return redirect()->route('exams.index')->with('deleted', 'Examen eliminado correctamente.');
    }

    private function validateExamen(Request $request, $id = null)
    {
        $uniqueRule = 'unique:examenes,codigo_examen' . ($id ? ',' . $id . ',id_examen' : '');

        return $request->validate([
            'codigo_examen' => ['required', 'string', 'max:15', $uniqueRule],
            'nombre_examen' => ['required', 'string', 'max:50', 'regex:/^[a-zA-Z\sáéíóúñÁÉÍÓÚÑ]+$/'],
            'precio_actual' => ['required', 'numeric', 'min:0', 'max:1000'],
            'tiempo_entrega' => ['nullable', 'string', 'max:50'],
        ], [
            'nombre_examen.regex' => 'El nombre solo puede contener letras y espacios.',
            'precio_actual.max' => 'El precio máximo permitido es $1000.',
        ]);
    }
}
