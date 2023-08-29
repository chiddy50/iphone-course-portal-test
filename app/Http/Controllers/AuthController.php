<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email'    => 'required|string',
            'password' => 'required'
        ]);

        $email = $validated['email'];
        $password = $validated['password'];

        $user = User::where("email", $email)->first();

        if(!$user || !Hash::check($password, $user->password)) {

        }

        $token = $user->createToken($user->firstname)->plainTextToken;

        return response()->json([
            'token' => $token
        ]);
    }
}
