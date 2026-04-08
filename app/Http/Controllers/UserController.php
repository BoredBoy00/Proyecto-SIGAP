<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index()
    {
        // Get users that are not 'paciente'
        $usuarios = User::where('rol', '!=', 'paciente')->orderBy('id', 'desc')->get();

        return view('users.index', compact('usuarios'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'rol' => 'required|string',
        ]);

        User::create([
            'name' => $request->nombres,
            'apellido' => $request->apellidos,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => $request->rol,
            'estado' => 'Activo',
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente');
    }
}
