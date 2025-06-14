<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class AdminController extends Controller {
    public function index() {
        return User::paginate(50);
    }
    public function show($id) {
        return User::findOrFail($id);
    }
    public function store(Request $request) {
        $request->validate([
            'name' => 'required', 
            'email' => 'required|email|unique:users',
            'password' => 'required', 
            'role' => 'required|in:admin,manager,user'
        ]);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role
        ]);

        return response()->json(['message' => 'User Created']);
    }

    public function destroy($id) {
        User::findOrFail($id)->delete();
        return response()->json(['message' => 'User deleted']);
    }

public function search(Request $request)
{
    $query = User::query();

    if ($request->filled('name')) {
        $query->where('name', 'like', '%' . $request->name . '%');
    }

    if ($request->filled('email')) {
        $query->where('email', 'like', '%' . $request->email . '%');
    }

    if ($request->filled('role')) {
        $query->where('role', 'like', '%' . $request->role . '%');
    }

    // return JSON response with users (no fail method used here)
    return response()->json([
        'data' => $query->get()
    ]);
}


   public function update(Request $request, $id)
{
    $user = User::findOrFail($id); // use findOrFail for better error handling

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => [
            'required',
            'email',
            Rule::unique('users')->ignore($user->id),
        ],
        'role' => 'required|string|in:user,admin,manager', // adjust roles as needed
    ]);

    $user->name = $request->name;
    $user->email = $request->email;
    $user->role = $request->role; // fixed: you were assigning role to email
    $user->save();

    return response()->json(['message' => 'Profile updated successfully.']);
}
}
