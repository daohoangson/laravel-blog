<?php

Route::get('/', 'HomeController@showWelcome');

Route::get('/register', 'RegisterController@showRegister');
Route::post('/register', array(
    'before' => 'csrf',
    'RegisterController@processRegister',
));
Route::get('/auth/login', 'AuthController@showLogin');
Route::post('/auth/login', 'AuthController@processLogin');
Route::get('/auth/logout', 'AuthController@processLogout');
