<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin() {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Check user by email first
        $user = \App\Models\User::where('email', $credentials['email'])->first();

        if ($user && !$user->is_approved) {
            return redirect()->back()->with('error', 'Your account is still pending approval.');
        }

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Ensure the user is approved
            if (!$user->is_approved) {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Your account is still pending approval.');
            }

            $user->is_active = true;
            $user->save(); // <== THIS MUST EXIST

            return redirect()->route('dashboard');
        }


        return redirect()->back()->with('error', 'Invalid credentials.');
    }


    public function showRegister() {
        return view('auth.register');
    }

    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_active' => false,
            'is_approved' => false, // Mark as pending approval
        ]);


        return redirect()->route('login')->with('success', 'Account created successfully. Please login.');
    }

   // LOGOUT
   public function logout(Request $request)
    {
        $user = Auth::user();
        $user->is_active = false;
        $user->save(); // Add this if missing

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}


