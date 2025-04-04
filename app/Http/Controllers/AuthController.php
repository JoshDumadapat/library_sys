<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // Add this line
use App\Models\User;

class AuthController extends Controller
{
    // Show registration form
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Handle registration logic
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        // Create new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Auto-login after registration
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Registration successful!');
    }

    // Show the login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle login logic
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->route('dashboard')->with('success', 'Login successful!');
        }

        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/login')->with('success', 'Logged out successfully.');
    }
}
