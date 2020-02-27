<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->group(function(){
	Route::get('/user', function (Request $request) { //for test
	    return $request->user();
	});
	Route::resource('post','PostController')->only(['store','destroy']);
	Route::resource('comment','CommentController')->only(['store','update','destroy']);
	Route::resource('user','UserController')->only(['update','destroy']);
});
