<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $userRol = strtolower(Auth::user()->rol);
            if ($userRol !== 'admin' && $userRol !== 'administrador') {
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
            'nombres' => 'required|string|regex:/^[\pL\s\-]+$/u|max:255',
            'apellidos' => 'required|string|regex:/^[\pL\s\-]+$/u|max:255',
            'cedula' => 'required|string|regex:/^[0-9]{1,9}$/|unique:users,cedula,'.$id,
            'telefono' => 'nullable|string|regex:/^[0-9]{1,11}$/',
            'email' => 'required|email|unique:users,email,'.$id, 
            'rol' => 'required|string',
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->nombres,
            'apellido' => $request->apellidos,
            'cedula' => $request->cedula,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'rol' => strtolower($request->rol),
        ]);

    return redirect()->route('users.index')->with('success', 'Usuario actualizado');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect(to: '/usuarios');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombres' => 'required|string|regex:/^[\pL\s\-]+$/u|max:255',
            'apellidos' => 'required|string|regex:/^[\pL\s\-]+$/u|max:255',
            'cedula' => 'required|string|regex:/^[0-9]{1,9}$/|unique:users,cedula',
            'telefono' => 'nullable|string|regex:/^[0-9]{1,11}$/',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'rol' => 'required|string',
        ]);

        User::create([
            'name' => $request->nombres,
            'apellido' => $request->apellidos,
            'cedula' => $request->cedula,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => strtolower($request->rol),
            'estado' => 'Activo',
        ]);

    

        return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente');
    }
}
