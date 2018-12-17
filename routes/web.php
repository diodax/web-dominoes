<?php

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
app()->singleton('GameCenterService', function(){
    return new \App\Services\GameCenterService;
});


Route::get('/', function () {
    return view('landing');
});

// Registration Routes...
Route::get('/register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('/register', 'Auth\RegisterController@register');

// Authentication Routes...
Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

// Chat Room route
Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', 'Chat\ChatController@show')->name('home');
    Route::get('/chat/{id}', 'Chat\ChatController@show')->name('chat');
});

// Game route
Route::group(['middleware' => ['auth']], function () {
    Route::get('/game/create', 'GameController@create')->name('game.create');
    Route::get('/game/{id}', 'GameController@show')->name('game');
    Route::post('/game/{id}/submit', 'GameController@submitTurn')->name('game.submit');
    Route::post('/game/{id}/check', 'GameController@checkTurn')->name('game.check');
});
