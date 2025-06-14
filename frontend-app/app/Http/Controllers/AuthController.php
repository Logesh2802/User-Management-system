<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller {
    public function showLogin() {
        session(['captcha_num1' => $n1 = rand(1, 10)]);
        session(['captcha_num2' => $n2 = rand(1, 10)]);
        session(['captcha_result' => $n1 + $n2]);
        return view('auth.login');
    }

public function login(Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'captcha_answer' => 'required'
    ]);

    $apiUrl = 'http://127.0.0.1:8000/api/login';

    $response = Http::timeout(10)->post($apiUrl, [
        'email' => $request->email,
        'password' => $request->password,
        'captcha_answer' => $request->captcha_answer,
    ]);

    // Debug if it fails
    if ($response->failed()) {
        return back()->withErrors(['message' => 'Login failed: ' . $response->body()]);
    }

    // Store token
    $token = $response->json('token');
    Session::put('token', $token);

    return redirect('/dashboard');
}

    public function showRegister() {
        session(['captcha_num1' => $n1 = rand(1, 10)]);
        session(['captcha_num2' => $n2 = rand(1, 10)]);
        session(['captcha_result' => $n1 + $n2]);
        return view('auth.register');
    }

public function register(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'password' => 'required|string|min:6',
        'captcha_answer' => 'required',
    ]);
   
    $response = Http::asForm()->post('http://127.0.0.1:8000/api/register', [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'captcha_answer' => $request->captcha_answer,
        ]);

    if ($response->failed()) {
        return back()->withErrors(['message' => $response->json()['message'] ?? 'Registration failed']);
    }

    Session::put('token', $response['token']);
    return redirect('/dashboard');
}

    public function logout() {
        Session::flush();
        return redirect('/login');
    }

    public function store_token(Request $request) {
       session(['token' => $request->token]); 
        return response()->json(['status' => 'ok']);

}
}