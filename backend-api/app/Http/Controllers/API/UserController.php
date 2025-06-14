<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller {

    public function updateProfile(Request $request)
{
    $user = $request->user(); // Authenticated via token

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'password' => 'nullable|min:6',
    ]);

    $user->name = $request->name;
    $user->email = $request->email;
    $user->save();

    return response()->json(['message' => 'Profile updated successfully.']);
}
}
