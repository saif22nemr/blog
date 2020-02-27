<?php

use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware(['auth'])->group(function(){
	Route::resource('/post', 'PostController')->only(['index','create','update','edit']);

	Route::get('my_post','PostController@myPosts')->name('myPost');
	Route::get('user','UserController@index')->name('user.index');
	Route::get('user/{user}/post','PostController@userPosts')->name('user.post');
	Route::get('profile','UserController@profile')->name('profile');
	Route::get('profile/edit','UserController@edit')->name('profile.edit');
	Route::patch('profile/update','UserController@updateProfile')->name('profile.update');
});


//Route::resource('post','PostController');
