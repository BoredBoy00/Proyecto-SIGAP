<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\Facturacion;
use App\Models\Patient;
use App\Models\Examen;

class UserController extends Controller
{
    public function index(Request $request)
{
    $search = $request->input('search');

    $usuarios = User::where('rol', '!=', 'paciente')
        ->when($search, function ($query, $search) {
            return $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                ->orWhere('apellido', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%")
                ->orWhere('rol', 'LIKE', "%{$search}%");
            });
        })
        ->orderBy('id', 'desc')
        ->get();

    $facturasHoy = Facturacion::whereDate('created_at', Carbon::today())->count();
    $pacientesTotal = Patient::count();
    $examenesTotal = Examen::count();

    return view('users.index', compact('usuarios', 'facturasHoy', 'pacientesTotal', 'examenesTotal'));
}

    public function create()
    {
        return view('users.create');
    }

    public function edit($id)
    {
        return view('users.edit', ['user' => User::findOrFail($id)]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
        'nombres' => 'required|string|max:255',
        'apellidos' => 'required|string|max:255',
        'cedula' => 'required|string|max:9|unique:users,cedula,'.$id,
        'email' => 'required|email|unique:users,email,'.$id, 
        'rol' => 'required|string',
    ]);

    $user = User::findOrFail($id);
    $user->update([
        'name' => $request->nombres,
        'apellido' => $request->apellidos,
        'cedula' => $request->cedula,
        'email' => $request->email,
        'rol' => $request->rol,
    ]);

    return redirect()->route('users.index')->with('success', 'Usuario actualizado');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('users.index')->with('deleted', "El usuario <b>{$user->name} {$user->apellido}</b> ha sido eliminado exitosamente.");
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'cedula' => 'required|string|max:9|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'rol' => 'required|string',
        ]);

        User::create([
            'name' => $request->nombres,
            'apellido' => $request->apellidos,
            'cedula' => $request->cedula,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => $request->rol,
            'estado' => 'Activo',
        ]);

    

        return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente');
    }
}
