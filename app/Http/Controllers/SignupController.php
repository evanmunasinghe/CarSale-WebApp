<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SignupController extends Controller
{
    public function create(){
        return view('auth.signup');
    }

    public function store(Request $request){
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:45', 'unique:users,phone'],
        ]);

        User::create([
            'name' => trim($data['first_name'] . ' ' . $data['last_name']),
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => $data['password'],
        ]);

        return redirect()->route('login')->with('success', 'Account created successfully. Please log in.');
    }
}
