<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('api_token')->plainTextToken;

        return response()
        ->json(['user' => $user, 'token' => $token], 200)
        ->cookie(
            'auth-token',
            $token,
            60 * 24 * 7,
            '/',
            env('SESSION_DOMAIN'),
            env('APP_ENV') === 'production',
            true,
            false,
            'strict'
        );
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()
        ->json(['message' => 'Logged out successfully'], 200)
        ->cookie('auth-token', '', -1);
    }
}