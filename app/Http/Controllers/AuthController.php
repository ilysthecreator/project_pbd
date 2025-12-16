<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Pastikan import Model User
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        // Cek manual: Jika sudah login, lempar ke dashboard
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('welcome'); // Pastikan view 'welcome' berisi form login
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required', // Sesuai kolom DB
            'password' => 'required',
        ]);

        // Karena data di SQL Anda passwordnya PLAIN TEXT ('admin123'), 
        // Auth::attempt bawaan Laravel akan GAGAL (karena dia mengharapkan Hash).
        
        // --- OPSI LOGIN MANUAL (Untuk Password Plain Text) ---
        $user = User::where('username', $request->username)->first();

        if ($user && $user->password === $request->password) { // Cek password plain text
            Auth::login($user); // Loginkan user secara manual
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        // --- JIKA PASSWORD DI DB SUDAH DI-HASH (Disarankan), GUNAKAN INI: ---
        /*
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }
        */

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}