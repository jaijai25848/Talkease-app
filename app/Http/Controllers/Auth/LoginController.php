<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // Log the user login activity (only if model exists)
            if (class_exists(\App\Models\ActivityLog::class)) {
                ActivityLog::create([
                    'user_id'     => Auth::id(),
                    'action'      => 'login',
                    'description' => 'User Login',
                    'status'      => 'Success',
                ]);
            }

            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        if (Auth::check() && class_exists(\App\Models\ActivityLog::class)) {
            ActivityLog::create([
                'user_id'     => Auth::id(),
                'action'      => 'logout',
                'description' => 'User Logout',
                'status'      => 'Success',
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
