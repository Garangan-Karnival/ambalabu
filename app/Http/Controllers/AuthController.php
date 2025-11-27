<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
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
        Session::put('id_user', $user->id_user);
        return redirect()->intended(route('profile.show'))->with('success', 'Selamat datang!');

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

        // SIMPAN ID USER KE SESSION
        Session::put('id_user', Auth::user()->id_user);

        // Redirect ke halaman yang diinginkan (default ke /profile jika tidak ada tujuan lain)
        return redirect()->intended(route('profile.show'))->with('success', 'Berhasil masuk!');
    }

    // 3. Gagal (Redirect back)
    return back()->withErrors(['email' => 'Email atau Password salah.'])->onlyInput('email');
}

    public function logout()
    {
        Auth::logout();
        return redirect('/')->with('success', 'Logged out!');
    }

    public function changePassword(Request $request)
{
    $request->validate([
        'old_password' => 'required',
        'new_password' => 'required|min:6|confirmed'
    ]);

    $user = Auth::user();

    if (!Hash::check($request->old_password, $user->password)) {
        return back()->withErrors(['old_password' => 'Password lama salah']);
    }

    $user->password = Hash::make($request->new_password);
    $user->save();

    return redirect()->route('profile')->with('success', 'Password berhasil diganti!');
}

}
