<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    // tampilkan login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // login post
    public function login(Request $request)
    {
        $request->validate([
            'username'=>'required',
            'password'=>'required',
        ]);

        // contoh hardcode user
        $username = $request->input('username');
        $password = $request->input('password');

        if ($username === 'aldmic' && $password === '123abc123') {
            $request->session()->put('user', $username);
            return redirect()->route('movies.index');
        }

        return back()->withErrors(['login'=>'Username atau password salah']);
    }

    // logout
    public function logout(Request $request)
    {
        $request->session()->forget('user');
        return redirect()->route('login');
    }
}