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

Route::get('/', function () {
    return redirect('/home');
});


Route::get('/chats', 'ChatController@index');
Route::get('/messages', 'ChatController@getMessages');
Route::post('/messages', 'ChatController@sendMessage');

Route::get('/vote', 'VoteController@index');

Route::get('/vote/{id}', 'VoteController@getVote');
Route::post('/vote', 'VoteController@postVote');
Route::post('/unvote', 'VoteController@unvote');
Route::get('/my/votes', 'VoteController@myVotes');
Route::get('/my/vote/create', 'VoteController@createVote');
Route::post('/my/vote/store', 'VoteController@storeVote');
Route::get('/vote/results/{id}', 'VoteController@results');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
