<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function create(){
        return view('auth.login');
    }

    public function store(Request $request){
        $credentials = $request->validate([
            'email'=> ['required','email'],
            'password'=> ['required'],
            ]);
        if(Auth::attempt($credentials)){
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email'=> 'Credentials do not match'
            ]);
    }

    public function destroy(Request $request){
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
