<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    function __invoke(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => ['These credentials do not match our records.']], 404);
        }
        
        if ($user->role_id == 1) {
            $token = $user->createToken('my-app-token', ['products:modify'])->plainTextToken;
        } else {
            $token = $user->createToken('my-app-token')->plainTextToken;
        }
        
        $response = ['token' => $token];
        return response()->json($response, 201);
    }
}
