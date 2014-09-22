<?php

Route::get('/', 'HomeController@showWelcome');

Route::get('/register', 'RegisterController@showRegister');
Route::post('/register', array(
    'before' => 'csrf',
    'uses' => 'RegisterController@processRegister',
));
Route::get('/auth/login', 'AuthController@showLogin');
Route::post('/auth/login', 'AuthController@processLogin');
Route::get('/auth/logout', 'AuthController@processLogout');

Route::get('/profile', array(
    'before' => 'auth',
    'uses' => 'UserController@showProfile',
));
Route::get('/users', 'UserController@showList');
Route::get('/users/{id}', array(
    'before' => 'auth',
    'uses' => 'UserController@showView',
));
Route::post('/users/save', array(
    'before' => 'auth',
    'uses' => 'UserController@processSave',
));
