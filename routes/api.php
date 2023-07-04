<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware'=> 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('login', 'App\Http\Controllers\AuthController@login'); //login
    Route::post('logout', 'App\Http\Controllers\AuthController@logout'); //logout
    Route::post('refresh', 'App\Http\Controllers\AuthController@refresh'); //refresh token
    Route::post('me', 'App\Http\Controllers\AuthController@me'); //get user info
    Route::post('register', 'App\Http\Controllers\AuthController@register'); //register

    Route::group(['middleware'=> 'api', 'prefix' => 'post'], function ($router) {
        Route::get('list', 'App\Http\Controllers\PostController@index'); //get all post
        Route::get('publicPost', 'App\Http\Controllers\PostController@getPublicPosts'); //get all public posts
        Route::get('privatePost', 'App\Http\Controllers\PostController@getPrivatePosts'); //get all private posts
        Route::get('show/{id}', 'App\Http\Controllers\PostController@show'); //get post by id
        Route::post('create', 'App\Http\Controllers\PostController@store'); //create new post
        Route::post('update/{id}', 'App\Http\Controllers\PostController@update'); //update post
        Route::post('delete/{id}', 'App\Http\Controllers\PostController@destroy'); //delete post
    });
});

