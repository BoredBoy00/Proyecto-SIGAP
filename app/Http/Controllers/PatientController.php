<?php

namespace App\Http\Controllers;

use App\Models\User;
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
            // Cedula is just stored in name/apellido for now or we could add it to Users table later.
            // But we didn't add Cedula to User! We mapped it from the ER where it doesn't exist. Let's just use email or ignore for now.
            'telefono' => 'nullable|string',
            'fecha_nacimiento' => 'nullable|date',
            'email' => 'nullable|email|unique:users',
        ]);

        // We use a dummy email if none provided so it doesn't fail unique user requirements if we made it nullable
        // Wait, email in users table is required by Laravel default.
        $email = $request->email ?? 'paciente_' . time() . '@sigap.local';

        User::create([
            'name' => $request->nombres,
            'apellido' => $request->apellidos,
            'email' => $email,
            'telefono' => $request->telefono,
            'fecha_de_nacimiento' => $request->fecha_nacimiento,
            'password' => bcrypt('password'), // default password
            'rol' => 'paciente',
            'estado' => 'Activo',
        ]);

        return redirect()->route('patients.create')->with('success', 'Paciente registrado exitosamente');
    }
}
