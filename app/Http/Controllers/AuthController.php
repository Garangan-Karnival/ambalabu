<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validation
        $request->validate([
            'username' => 'required|min:3|max:50|unique:user,username',
            'email' => 'required|email|unique:user,email',
            'password' => 'required|min:6'
        ]);

        // Create user
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        // Auto login after register
        Auth::login($user);

        return redirect('/profile')->with('success', 'Welcome!');
    }

    public function login(Request $request)
    {
        // Validation
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Attempt 
        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect('/profile')->with('success', 'Logged in!');
        }

        return back()->withErrors(['email' => 'Wrong email or password']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/')->with('success', 'Logged out!');
    }
}
