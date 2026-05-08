<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->rol !== 'admin' && Auth::user()->rol !== 'Administrador') {
                abort(403, 'Acceso denegado. Solo administradores pueden acceder a esta sección.');
            }
            return $next($request);
        });
    }
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

    return view('users.index', compact('usuarios'));
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
        'email' => 'required|email|unique:users,email,'.$id, 
        'rol' => 'required|string',
    ]);

    $user = User::findOrFail($id);
    $user->update([
        'name' => $request->nombres,
        'apellido' => $request->apellidos,
        'email' => $request->email,
        'rol' => $request->rol,
    ]);

    return redirect()->route('users.index')->with('success', 'Usuario actualizado');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        Activity::create([
            'user_id' => auth()->id(),
            'type' => 'Usuario',
            'description' => "Eliminó al usuario: {$user->name} {$user->apellido}",
            'identifier' => $user->id,
        ]);

        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuario eliminado exitosamente');
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

        $user = User::create([
            'name' => $request->nombres,
            'apellido' => $request->apellidos,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => $request->rol,
            'estado' => 'Activo',
        ]);

        Activity::create([
            'user_id' => auth()->id(),
            'type' => 'Usuario',
            'description' => "Creó al usuario: {$user->name} {$user->apellido}",
            'identifier' => $user->id,
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente');
    }
}
