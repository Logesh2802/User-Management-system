<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use GuzzleHttp\Client;

class AdminController extends Controller
{
    public function index(){
    $token = Session::get('token');

    if (!$token) {
        return redirect('/login')->withErrors(['message' => 'Session expired. Please login again.']);
    }
    try {
        $client = new Client();
        $response = $client->request('GET', 'http://127.0.0.1:8000/api/admin/users', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
            ],
        ]);

        $users = json_decode($response->getBody()->getContents(), true);
        return view('admin.users.index', compact('users'));

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

public function create(){
    $token = Session::get('token');
    if (!$token) {
        return redirect('/login')->
        withErrors(['message' =>
        'Session expired. Please login again.']);
        }
        return view('admin.users.create');  

}

public function edit($id){
    $token = Session::get('token');

    if (!$token) {
        return redirect('/login')->withErrors(['message' => 'Session expired. Please login again.']);
    }
    try {
        $client = new Client();
        $response = $client->request('GET', 'http://127.0.0.1:8000/api/admin/users/'.$id, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
            ],
        ]);

        $user = json_decode($response->getBody()->getContents(), true);

        return view('admin.users.edit', compact('user'));

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

 public function view($id)
{
    $token = Session::get('token');

    if (!$token) {
        return redirect('/login')->withErrors(['message' => 'Session expired. Please login again.']);
    }

    try {
        $client = new Client();
        $response = $client->request('GET', 'http://127.0.0.1:8000/api/admin/users/'.$id, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
            ],
        ]);

        $user = json_decode($response->getBody()->getContents(), true);

        return view('admin.users.view', compact('user'));

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

public function destroy($id){
    $token = Session::get('token');

    if (!$token) {
        return redirect('/login')->withErrors(['message' => 'Session expired. Please login again.']);
    }
    try {
        $client = new Client();
        $response = $client->request('DELETE', 'http://127.0.0.1:8000/api/admin/users/'.$id, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
            ],
        ]);

        // $user = json_decode($response->getBody()->getContents(), true);

        return redirect()->to('/admin/users');

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

}
