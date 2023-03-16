<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\OAuth2TokenException;
use App\Exceptions\ApiRequestException;
use Illuminate\Support\Facades\Validator;

class OAuth2ApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return App\Http\Middleware\Response
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $this->getValidatedUser($request->all());

        $user->tokens()->delete();  

        $token = $user->createToken($user->email);
        $token = explode('|', $token->plainTextToken)[1];

        $request->merge(['token' => [
            'type' => 'Bearer',
            'token' => $token
        ]]);

        return $next($request);
    }

    /**
     * Validate request data and get validated User instanse
     * @param array $request
     * 
     * @return User
     */
    public function getValidatedUser(array $request) : User
    {
        $validator = Validator::make($request, [
            'email' => 'required|email|max:255',
            'password' => 'required|string|max:255'
        ]);
        if ($validator->fails()) {
            throw new ApiRequestException($validator->messages());
        }

        extract($request);

        if (!$email || !$password) {
            throw new OAuth2TokenException('Enter user and password!');
        }

        $user = User::select(['id', 'email', 'password'])->where('email', $email)->first();
        if (!$user) {
            throw new OAuth2TokenException('User not found');
        }

        if (!Hash::check($password, $user->password)) {
            throw new OAuth2TokenException('Password is invalid');
        }

        return $user;
    }
}
