<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/get-token', [ApiController::class, 'token']);

Route::group(['middleware' => 'auth:sanctum'], function () {
     Route::get('/users', [ApiController::class, 'users']);
     Route::get('/posts', [ApiController::class, 'posts']);
     Route::post('/posts', [ApiController::class, 'posts']);
     Route::post('/comments', [ApiController::class, 'comments']);
});