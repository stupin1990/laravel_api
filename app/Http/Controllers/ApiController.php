<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Call;
use App\Http\Requests\ApiRequest;

class ApiController extends Controller
{
    /**
     * Get token by email / password
     */
    public function token(ApiRequest $request)
    {
        $email = $request->input('email', false);
        $password = $request->input('password', false);

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

    /**
     * Get all users
     */
    public function users(ApiRequest $request)
    {
        $users = User::with(['posts', 'comments'])->get();

        return response()->json($users);
    }

    /**
     * Get posts for current user or for all / given user
     */
    public function posts(ApiRequest $request)
    {
        $user_id = $request->isMethod('get') ? $request->user()->id : $request->input('user_id', false);
        $posts = Post::with(['comments'])
            ->when($user_id, function ($query) use ($user_id) {
                return $query->where('user_id', $user_id);
            })
            ->get();

        return response()->json($posts);
    }

    /**
     * Get all comments or comments of given user / post
     */
    public function comments(ApiRequest $request)
    {
        $params = [
            'user_id' => $request->input('user_id', false),
            'post_id' => $request->input('post_id', false)
        ];

        $comments = Comment::with(['user', 'post']);
        foreach ($params as $param => $value) {
            $comments->when($value, function ($query) use ($param, $value) {
                return $query->where($param, $value);
            });
        }
        $comments = $comments->get();

        return response()->json($comments);
    }

    /**
     * Display by months of the current year how many interruptions each user had more than 5 minutes between calls
     */
    public function calls($break_time = 5, ApiRequest $request)
    {
        $calls = Call::getCallsBreaksByMonth($break_time);

        return response()->json($calls);
    }
}
