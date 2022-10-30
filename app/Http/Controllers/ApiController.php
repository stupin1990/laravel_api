<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ApiController extends Controller
{
    public function token(Request $request)
    {
        $email = $request->input('email', false);
        $password = $request->input('password', false);

        if (!$email || !$password) {
            return response()->json([
                'error' => 'Invalid credentials'
            ], 401);
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json([
                'error' => 'User not found'
            ], 401);
        }

        if (!Hash::check($password, $user->password)) {
            return response()->json([
                'error' => 'Password is invalid'
            ], 401);
        }

        $user->tokens()->delete();  

        $token = $user->createToken($email);
        $token = explode('|', $token->plainTextToken)[1];

        return response()->json([
            'token' => $token
        ]);
    }

    public function users(Request $request)
    {
        return $request->user();
    }
}
