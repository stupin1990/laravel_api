<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ApiRequest;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;

use App\Services\StatisticService;


class ApiController extends Controller
{
    protected $per_page;

    public function __construct()
    {
        $this->per_page = config('settings.items_per_page');
    }

    /**
     * Get token by email / password
     * @param Illuminate\Http\Request $request
     * 
     * @return Illuminate\Http\JsonResponse
     */
    public function token(Request $request)
    {
        return response()->json($request->input('token'));
    }

    /**
     * Get all users
     * @param App\Http\Requests\ApiRequest $request
     * 
     * @return Illuminate\Http\JsonResponse
     */
    public function users(ApiRequest $request)
    {
        $users = User::with(['posts', 'comments'])->apiPaginate($this->per_page);;

        return response()->json($users);
    }

    /**
     * Get posts for current user or for all / given user
     * @param App\Http\Requests\ApiRequest $request
     * 
     * @return Illuminate\Http\JsonResponse
     */
    public function posts(ApiRequest $request)
    {
        $user_id = $request->isMethod('get') ? $request->user()->id : $request->input('user_id', 0);
        $posts = Post::getPostsForUser($user_id, ['comments'], $this->per_page);

        return response()->json($posts);
    }

    /**
     * Get all comments or comments of given user / post
     * @param App\Http\Requests\ApiRequest $request
     * 
     * @return Illuminate\Http\JsonResponse
     */
    public function comments(ApiRequest $request)
    {
        $params = [
            'user_id' => $request->input('user_id', false),
            'post_id' => $request->input('post_id', false)
        ];
        $comments = Comment::getCommentsByParams($params, ['user', 'post'], $this->per_page);

        return response()->json($comments);
    }

    /**
     * Display by months of the current year how many interruptions each user had more than 5 minutes between calls
     * @param int $break_time
     * @param Illuminate\Http\Request $request
     * @param StatisticService $statistic
     * 
     * @return Illuminate\Http\JsonResponse
     */
    public function calls(int $break_time = 5, Request $request, StatisticService $statistic)
    {
        $calls = $statistic->getCallBreakesByMonth($break_time, $this->per_page);

        return response()->json($calls);
    }
}
