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
            return redirect()->route('dashboard');
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
            'g-recaptcha-response' => ['required'],
        ], [
            'g-recaptcha-response.required' => 'Por favor, marca la casilla de seguridad reCAPTCHA.',
        ]);

        $response = \Illuminate\Support\Facades\Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('RECAPTCHA_SECRET_KEY'),
            'response' => $request->input('g-recaptcha-response'),
            'remoteip' => $request->ip()
        ]);

        if (! $response->json('success')) {
            return back()->withErrors([
                'g-recaptcha-response' => 'La validación de reCAPTCHA falló. Inténtalo de nuevo.',
            ])->onlyInput('email');
        }

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $user->update([
                'last_login_at' => now(),
            ]);

            // Desloguear otros dispositivos para mantener una sola sesión activa
            Auth::logoutOtherDevices($request->input('password'));

            $request->session()->regenerate();

            // Handle different roles
            // You can check Auth::user()->rol here to redirect appropriately.
            // For now, redirecting everyone to intended or default route.
            $redirect = route('dashboard');

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
