<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use GuzzleHttp\Client;

class DashboardController extends Controller {

    public function index()
{
    $token = Session::get('token');

    if (!$token) {
        return redirect('/login')->withErrors(['message' => 'Session expired. Please login again.']);
    }

    try {
        $client = new Client();
        $response = $client->request('GET', 'http://127.0.0.1:8000/api/user', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
            ],
        ]);

        $user = json_decode($response->getBody()->getContents(), true);

        return view('dashboard', compact('user'));

    } catch (RequestException $e) {
        // If token is invalid or request failed
        Session::forget('token');

        // Get API error message if available
        $errorMessage = 'Unauthorized. Please login again.';
        if ($e->hasResponse()) {
            $errorResponse = json_decode($e->getResponse()->getBody()->getContents(), true);
            if (isset($errorResponse['message'])) {
                $errorMessage = $errorResponse['message'];
            }
        }

        return redirect('/login')->withErrors(['message' => $errorMessage]);
    }
}

public function edit_profile(){
    $token = Session::get('token');

    if (!$token) {
        return redirect('/login')->withErrors(['message' => 'Session expired. Please login again.']);
    }
    try {
        $client = new Client();
        $response = $client->request('GET', 'http://127.0.0.1:8000/api/user', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
            ],
        ]);

        $user = json_decode($response->getBody()->getContents(), true);

        return view('user.edit_profile', compact('user'));

    } catch (RequestException $e) {
        // If token is invalid or request failed
        Session::forget('token');

        // Get API error message if available
        $errorMessage = 'Unauthorized. Please login again.';
        if ($e->hasResponse()) {
            $errorResponse = json_decode($e->getResponse()->getBody()->getContents(), true);
            if (isset($errorResponse['message'])) {
                $errorMessage = $errorResponse['message'];
            }
        }

        return redirect('/login')->withErrors(['message' => $errorMessage]);
    }

}

public function update_profile(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'password' => 'nullable|min:6|confirmed', // if using password confirmation
    ]);

    $user->name = $request->name;
    $user->email = $request->email;

    if (!empty($request->password)) {
        $user->password = Hash::make($request->password);
    }

    $user->save();

    return redirect()->back()->with('success', 'Profile updated successfully.');
}

}