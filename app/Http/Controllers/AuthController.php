<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Show the application's login form.
     */
    public function showLoginForm()
    {
        // If the user is already authenticated, redirect them
        if (Auth::check()) {
            return Auth::user()->rol === 'Administrador' 
                ? redirect()->route('users.index') 
                : redirect()->route('patients.create');
        }
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Handle different roles
            // You can check Auth::user()->rol here to redirect appropriately.
            // For now, redirecting everyone to intended or default route.
            $redirect = Auth::user()->rol === 'Administrador' 
                ? route('users.index') 
                : route('patients.create');

            return redirect()->intended($redirect);
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
