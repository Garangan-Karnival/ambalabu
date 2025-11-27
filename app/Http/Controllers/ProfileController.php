<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $id = Session::get('id_user');

        if (!$id) {
            return redirect('/login');
        }

        $user = DB::table('user')->where('id_user', $id)->first();

        return view('profile', [
            'user' => $user,
        ]);
    }

    public function update(Request $request)
    {
        $id = Session::get('id_user');

        DB::table('user')->where('id_user', $id)->update([
            'username' => $request->username,
        ]);

        return redirect()->route('profile.show')
            ->with('update_success', true);
    }

    public function logout()
    {
        Session::flush();
        return redirect('/login');
    }
}
