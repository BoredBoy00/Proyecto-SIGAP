<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'cedula' => 'required|string|unique:pacientes',
            'telefono' => 'nullable|string',
            'fecha_nacimiento' => 'nullable|date',
            'email' => 'nullable|email|unique:pacientes',
        ]);

        Patient::create([
            'nombre' => $request->nombres,
            'apellido' => $request->apellidos,
            'cedula' => $request->cedula,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'telefono' => $request->telefono,
            'email' => $request->email,
        ]);

        return redirect()->route('patients.create')->with('success', 'Paciente registrado exitosamente');
    }

    public function edit($id)
    {
        return view('patients.edit', ['patient' => Patient::findOrFail($id)]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'cedula' => 'required|string|unique:pacientes,cedula,' . $id,
            'telefono' => 'nullable|string',
            'fecha_nacimiento' => 'nullable|date',
            'email' => 'nullable|email|unique:pacientes,email,' . $id,
        ]);

        $patient = Patient::findOrFail($id);
        $patient->update([
            'nombre' => $request->nombres,
            'apellido' => $request->apellidos,
            'cedula' => $request->cedula,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'telefono' => $request->telefono,
            'email' => $request->email,
        ]);

        return redirect()->route('patients.look')->with('success', 'Paciente actualizado exitosamente');
    }

    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();

        return redirect()->route('patients.look')->with('deleted', 'Paciente eliminado exitosamente');
    }

    public function index(Request $request)
    {
        $search = trim($request->input('search'));

        $patients = Patient::when($search, function ($query, $search) {
            return $query->where('nombre', 'LIKE', "%{$search}%")
                ->orWhere('apellido', 'LIKE', "%{$search}%")
                ->orWhere('cedula', 'LIKE', "%{$search}%")
                ->orWhere('telefono', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%");
        })->get();

        return view('patients.index', compact('patients', 'search'));
    }

    public function look(Request $request)
    {
        $search = trim($request->input('search'));

        $patients = Patient::when($search, function ($query, $search) {
            return $query->where('nombre', 'LIKE', "%{$search}%")
                ->orWhere('apellido', 'LIKE', "%{$search}%")
                ->orWhere('cedula', 'LIKE', "%{$search}%")
                ->orWhere('telefono', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%");
        })->get();

        return view('patients.look', compact('patients', 'search'));
    }
}
