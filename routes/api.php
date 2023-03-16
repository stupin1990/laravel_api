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

Route::get('/get-token', [ApiController::class, 'token'])->name('token')->middleware('token');

Route::middleware(['auth:sanctum', 'sql_log'])->group(function () {
     Route::get('/users', [ApiController::class, 'users'])->name('users');
     Route::get('/posts', [ApiController::class, 'posts'])->name('posts.get');
     Route::post('/posts', [ApiController::class, 'posts'])->name('posts.post');
     Route::post('/comments', [ApiController::class, 'comments'])->name('comments');
     Route::get('/calls/{break_time?}', [ApiController::class, 'calls'])->where('break_time', '[0-9]+')->name('calls');
});