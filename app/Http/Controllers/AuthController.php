<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

use Exception; // Tambahkan ini di bagian atas file
class AuthController extends Controller
{
    // PERBAIKAN PADA AuthController.php::register
// AuthController.php::register


public function register(Request $request)
{
    // ... validation code ...

    try {
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        Auth::login($user);
        return redirect()->intended(route('profile'))->with('success', 'Selamat datang!');

    } catch (Exception $e) {
        // Ini akan menampilkan error teknis database
        return response()->json([
            'status' => 'Gagal Insert Ke DB!',
            'error' => $e->getMessage()
        ], 500); 
    }
}

    // PERBAIKAN PADA AuthController.php::login
public function login(Request $request)
{
    // 1. Validation
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    // 2. Attempt
    // Menggunakan $request->only('email', 'password') sudah benar.
    if (Auth::attempt($request->only('email', 'password'))) {
        
        // PENTING: Regenerate session ID untuk keamanan
        $request->session()->regenerate(); 

        // Redirect ke halaman yang diinginkan (default ke /profile jika tidak ada tujuan lain)
        return redirect()->intended(route('profile'))->with('success', 'Berhasil masuk!');
    }

    // 3. Gagal (Redirect back)
    return back()->withErrors(['email' => 'Email atau Password salah.'])->onlyInput('email');
}

    public function logout()
    {
        Auth::logout();
        return redirect('/')->with('success', 'Logged out!');
    }
}
