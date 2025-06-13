<?php

namespace StDevs\Kgp\Api\Controllers;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        $loginData = request()->only('email', 'password');

        try {
            $user = \RainLab\User\Models\User::where('email', $loginData['email'])->first();

            if (!$user || !Hash::check($loginData['password'], $user->password)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }

            if (!$user->is_activated) {
                return response()->json(['error' => 'Account not activated'], 401);
            }

            $token = Str::random(60);
            $user->api_token = hash('sha256', $token);
            $user->save();

            return response()->json([
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Login failed'], 500);
        }
    }

    public function logout()
    {
        try {
            $token = request()->only('api_token');

            if (!$token) {
                return response()->json(['error' => 'Token required'], 401);
            }

            $user = \RainLab\User\Models\User::where('api_token', hash('sha256', $token['api_token']))->first();

            if (!$user) {
                return response()->json(['error' => 'Invalid token'], 401);
            }

            $user->api_token = null;
            $user->save();

            return response()->json(['message' => 'Logged out successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Logout failed'], 500);
        }
    }
}
