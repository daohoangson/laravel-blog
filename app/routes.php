<?php

Route::model('user', 'User');

Route::get('', array(
    'as' => 'index',
    'uses' => 'HomeController@showWelcome'
));

Route::get('register', array(
    'as' => 'register',
    'uses' => 'RegisterController@showRegister'
));
Route::post('register', array(
    'as' => 'register',
    'before' => 'csrf',
    'uses' => 'RegisterController@processRegister',
));
Route::get('auth/login', array(
    'as' => 'login',
    'uses' => 'AuthController@showLogin'
));
Route::post('auth/login', array(
    'as' => 'login',
    'uses' => 'AuthController@processLogin'
));
Route::post('auth/logout', array(
    'as' => 'logout',
    'uses' => 'AuthController@processLogout'
));

Route::get('profile', array(
    'as' => 'profile',
    'before' => 'auth',
    'uses' => 'UserController@showProfile',
));

Route::get('users', array(
    'as' => 'user_list',
    'uses' => 'UserController@showList'
));
Route::get('users/{user}', array(
    'as' => 'user_view',
    'before' => 'auth',
    'uses' => 'UserController@showView',
));
Route::post('users/save', array(
    'as' => 'user_save',
    'before' => 'auth',
    'uses' => 'UserController@processSave',
));
