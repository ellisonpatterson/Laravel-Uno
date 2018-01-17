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

// Landing page
Route::get('/', 'Controller@index')->name('index');

// Authentication Routes
Route::group(['namespace' => 'Auth', 'as' => 'auth.'], function () {
    // Authentication Routes
    Route::get('login', 'LoginController@showLoginForm')->name('login');
    Route::post('login', 'LoginController@login')->name('login.post');
    Route::get('logout', 'LoginController@logout')->name('logout');
    Route::post('logout', 'LoginController@logout')->name('logout');

    // Registration Routes
    Route::get('register', 'RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'RegisterController@register')->name('register.post');
});

// Chat Routes
Route::group(['namespace' => 'Chat', 'as' => 'chat.'], function () {
    Route::get('chat/messages/{game_id?}', 'ChatController@fetchMessages')->name('fetch');
    Route::post('chat/messages/{game_id?}', 'ChatController@sendMessage')->name('send');
});

// Game Routes
Route::group(['namespace' => 'Game', 'as' => 'game.'], function () {
    Route::post('games', 'GameController@createGame')->name('create');
    Route::get('games', 'GameController@fetchGames')->name('fetch');

    Route::post('games/{game_id}/status', 'GameController@status')->name('status');
    Route::post('games/{game_id}/turn', 'GameController@turn')->name('turn');
    Route::post('games/{game_id}/draw', 'GameController@draw')->name('draw');
    Route::get('games/{game_id}', 'GameController@view')->name('view');
});