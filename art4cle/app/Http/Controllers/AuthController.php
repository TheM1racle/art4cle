<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function showRegister()
    {
        return view('auth.register');
    }


    public function register(Request $request)
    {
        $data = $request->validate([
            'login' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['role_id'] = 3;

        $user = User::create($data);
        Auth::login($user);

        return redirect('/');
    }

    public function showLogin()
    {
        return view('auth.login');
    }


    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('/');
        }

        return back()->withErrors([
            'login' => 'Неверный логин или пароль.',
        ])->onlyInput('login');
    }


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
