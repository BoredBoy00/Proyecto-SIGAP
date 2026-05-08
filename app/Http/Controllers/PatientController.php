<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Activity;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function create()
    {
        $empresas = \App\Models\Empresa::all();
        return view('patients.create', compact('empresas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'cedula' => ['nullable', 'string', 'regex:/^V-[0-9]{1,8}$/', 'max:10', 'unique:pacientes'],
            'telefono' => 'nullable|string',
            'fecha_nacimiento' => 'nullable|date',
<<<<<<< Updated upstream
            'email' => 'nullable|email:rfc,dns|unique:pacientes',
=======
            'email' => 'nullable|email|unique:pacientes',
            'id_empresa' => 'nullable|exists:empresas,id_empresa',
>>>>>>> Stashed changes
        ]);

        $patient = Patient::create([
            'nombre' => $request->nombres,
            'apellido' => $request->apellidos,
            'cedula' => $request->cedula,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'id_empresa' => $request->id_empresa,
        ]);

        Activity::create([
            'user_id' => auth()->id(),
            'type' => 'Paciente',
            'description' => "Registró al paciente {$patient->nombre} {$patient->apellido}",
            'identifier' => $patient->id,
        ]);

        return redirect()->route('patients.create')->with('success', 'Paciente registrado exitosamente');
    }

    public function edit($id)
    {
        $patient = Patient::findOrFail($id);
        $empresas = \App\Models\Empresa::all();
        return view('patients.edit', compact('patient', 'empresas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'cedula' => ['nullable', 'string', 'regex:/^V-[0-9]{1,8}$/', 'max:10', 'unique:pacientes,cedula,' . $id],
            'telefono' => 'nullable|string',
            'fecha_nacimiento' => 'nullable|date',
            'email' => 'nullable|email:rfc,dns|unique:pacientes,email,' . $id,
            'id_empresa' => 'nullable|exists:empresas,id_empresa',
        ]);

        $patient = Patient::findOrFail($id);
        $patient->update([
            'nombre' => $request->nombres,
            'apellido' => $request->apellidos,
            'cedula' => $request->cedula,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'id_empresa' => $request->id_empresa,
        ]);

        Activity::create([
            'user_id' => auth()->id(),
            'type' => 'Paciente',
            'description' => "Actualizó los datos del paciente {$patient->nombre} {$patient->apellido}",
            'identifier' => $patient->id,
        ]);

        return redirect()->route('patients.look')->with('success', 'Paciente actualizado exitosamente');
    }

    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);

        Activity::create([
            'user_id' => auth()->id(),
            'type' => 'Paciente',
            'description' => "Eliminó al paciente {$patient->nombre} {$patient->apellido}",
            'identifier' => $patient->cedula,
        ]);

        $patient->delete();

        return redirect()->route('patients.look')->with('deleted', 'Paciente eliminado exitosamente');
    }

    public function index(Request $request)
    {
        $search = trim($request->input('search'));
        $patients = Patient::when($search, function ($query, $search) {
            return $query->where(function($q) use ($search) {
                $q->where('nombre', 'LIKE', "%{$search}%")
                  ->orWhere('apellido', 'LIKE', "%{$search}%")
                  ->orWhere('cedula', 'LIKE', "%{$search}%")
                  ->orWhere('telefono', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        })->get();

        return view('patients.look', compact('patients', 'search'));
    }

    public function look(Request $request)
    {
        return $this->index($request);
    }
}
