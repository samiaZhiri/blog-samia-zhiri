<?php
Route::get('/', 'App\controllers\HomeController@welcome')->name('home.welcome');

Route::get('/home/index', 'App\controllers\HomeController@index')->name('home.index');
Route::get('/home/show/{id}', 'App\controllers\HomeController@show')->name('home.show');
Route::get('/home/delete/{id}', 'App\controllers\HomeController@delete')->name('home.delete');
Route::post('/home/create', 'App\controllers\HomeController@create')->name('home.create');
Route::post('/home/cmtcreate', 'App\controllers\HomeController@cmtcreate')->name('home.cmtcreate');

Route::get('/comment/delete/{id}/{postId}', 'App\controllers\CommentController@delete')->name('comment.delete');

Route::get('/users/store', 'App\controllers\UserController@store')->name('users.store');
Route::post('/users/login', 'App\controllers\UserController@login')->name('users.login');
