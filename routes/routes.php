<?php
Route::get('/', 'App\Controllers\HomeController@welcome')->name('home.welcome');

Route::get('/home/index', 'App\Controllers\HomeController@index')->name('home.index');
Route::get('/home/show/{id}', 'App\Controllers\HomeController@show')->name('home.show');
Route::get('/home/delete/{id}', 'App\Controllers\HomeController@delete')->name('home.delete');
Route::post('/home/create', 'App\Controllers\HomeController@create')->name('home.create');
Route::post('/home/cmtcreate', 'App\Controllers\HomeController@cmtcreate')->name('home.cmtcreate');

Route::get('/comment/deleteComment/{id}', 'App\Controllers\CommentController@deleteComment')->name('comment.deleteComment');


//Inscription et connexion
Route::get('/users/store', 'App\Controllers\UserController@store')->name('users.store');
Route::get('/users/register', 'App\Controllers\UserController@register')->name('users.register');
Route::post('/users/signup', 'App\Controllers\UserController@signup')->name('users.signup');
Route::get('/users/logout', 'App\Controllers\UserController@logout')->name('users.logout');
Route::post('/users/login', 'App\Controllers\UserController@login')->name('users.login');

//Création de la route Add post
Route::get('/admin/store', 'App\Controllers\AdminController@store')->name('admin.store');
Route::post('/admin/create', 'App\Controllers\AdminController@create')->name('admin.create');

//Création des routes pour l'édit
Route::get('/admin/show/{id}', 'App\Controllers\AdminController@show')->name('admin.show');
Route::post('/admin/update/{id}', 'App\Controllers\AdminController@update')->name('admin.update');

//Dashboard Admin article
Route::get('/admin/index', 'App\Controllers\AdminController@index')->name('admin.index');
Route::get('/admin/delete/{id}', 'App\Controllers\AdminController@delete')->name('admin.delete');

//Dashboard Admin commentaire
Route::get('/admin/listComment', 'App\Controllers\AdminController@listComment')->name('admin.listComment');
Route::get('/admin/validComment/{id}', 'App\Controllers\AdminController@validComment')->name('admin.validComment');
Route::get('/admin/deleteComment/{id}', 'App\Controllers\AdminController@deleteComment')->name('admin.deleteComment');

//Formulaire de Contact
Route::post('/contact/send', 'App\Controllers\ContactController@send')->name('contact.send');
