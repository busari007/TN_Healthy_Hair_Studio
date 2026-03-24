<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{


    public function signup(){
        return view('auth.signup');
    }

     public function signin(){
        return view('auth.signin');
    }
    /**
     * Handle user registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
public function register(Request $request)
{
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'min:8', 'confirmed'],
        'phone' => ['required', 'string', 'max:20', 'unique:users'],
        'address' => ['required', 'string', 'max:255'],
    ]);

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role' => 'client',
        'phone' => $validated['phone'],
        'address' => $validated['address'],
    ]);

    // Auto login after signup
    Auth::login($user);

    return redirect('/')
        ->with('success', 'Account created successfully!');
}

    /**
     * Handle user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (!Auth::attempt($credentials)) {
        return back()
            ->withErrors(['email' => 'Invalid credentials'])
            ->withInput();
    }

    $request->session()->regenerate();

    $user = Auth::user();

    // 🔥 Role-based redirect (like your React logic)
    if ($user->role === 'admin') {
        return redirect('/admin');
    }

    return redirect('/')->with('success', 'Welcome back!');
}

    /**
     * Handle user logout request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        // If using API tokens
        // $request->user()->currentAccessToken()->delete();

        Auth::logout();

        return redirect('/')
        ->with('success', 'Logged out successfully!');
    }

    /**
     * Get authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user()
        ], 200);
    }

    public function forgotPassword(){
        return view('auth.forgot-password');
    }
}