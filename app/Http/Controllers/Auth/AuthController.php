<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('pages.auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('password', 'avatar-name');
        $callbackPath = $request->input('callback');

        $user = User::where('username', $credentials['avatar-name'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return redirect()->back()->withErrors([
                'avatar-name' => 'your credentials is incorrect',
                'password' => 'your credentials is incorrect',
            ]);
        }

        Auth::login($user);

        $request->session()->regenerate();

        if ($callbackPath) {
            $url = url($callbackPath);

            return redirect($url);
        }

        return redirect()->route('home');
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('home');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('pages.auth.register');
    }
}
