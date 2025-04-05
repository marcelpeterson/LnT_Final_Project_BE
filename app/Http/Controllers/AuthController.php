<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    function getLoginPage()
    {
        return view('Authenticate/login');
    }

    function getRegisterPage()
    {
        return view('Authenticate/register');
    }
    
    function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3|max:40',
            'email' => 'required|email|unique:users|regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/',
            'password' => 'required|string|min:6|max:12',
            'handphone' => 'required|string|min:10|max:15',
        ], [
            'name.required' => 'Name is required',
            'name.string' => 'Name must be a string',
            'name.min' => 'Name must be at least 3 characters',
            'name.max' => 'Name must not exceed 40 characters',
            'email.required' => 'Email is required',
            'email.email' => 'Email must be a valid email address',
            'email.unique' => 'Email already exists',
            'email.regex' => 'Email must be a Gmail address',
            'password.required' => 'Password is required',
            'password.string' => 'Password must be a string',
            'password.min' => 'Password must be at least 6 characters',
            'password.max' => 'Password must not exceed 12 characters',
            'handphone.required' => 'Handphone number is required',
            'handphone.string' => 'Handphone number must be a string',
            'handphone.min' => 'Handphone number must be at least 10 characters',
            'handphone.max' => 'Handphone number must not exceed 15 characters',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'handphone' => $request->handphone,
            'role' => 'user',
        ]);

        return redirect(route('login'))->with('success', 'Registration successful, please login');
    }

    function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'Email is required',
            'email.email' => 'Email must be a valid email address',
            'password.required' => 'Password is required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect(route('home'))->with('success', 'Login successful');
        }
        
        return redirect()->back()->with('error', 'Invalid credentials');
    }

    function logout()
    {
        Auth::logout();
        return redirect(route('login'))->with('success', 'Logout successful');
    }
}
