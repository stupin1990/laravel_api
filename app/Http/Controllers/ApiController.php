<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;

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
            'type' => 'Bearer',
            'token' => $token
        ]);
    }

    public function users(Request $request)
    {
        $users = User::with(['posts', 'comments'])->get();

        return response()->json($users);
    }

    public function posts(Request $request)
    {
        if ($request->isMethod('get')) {
            $posts = Post::with(['comments'])->where('user_id', $request->user()->id)->get();
        }
        else {
            $posts = Post::with(['comments'])->get();
        }

        return response()->json($posts);
    }

    public function comments(Request $request)
    {
        $comments = Comment::with(['user', 'post'])->get();

        return response()->json($comments);
    }
}
