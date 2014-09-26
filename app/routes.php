<?php

Route::model('user', 'User');
Route::model('entry', 'Entry');

Route::get('entries', array(
    'as' => 'index',
    'uses' => 'EntryController@showList'
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
Route::get('auth/logout', array(
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
    'before' => array(
        'auth',
        'csrf'
    ),
    'uses' => 'UserController@processSave',
));

Route::get('entries/create', array(
    'as' => 'entry_create',
    'before' => 'auth',
    'uses' => 'EntryController@showCreate'
));
Route::get('entries/{entry}/edit', array(
    'as' => 'entry_edit',
    'before' => 'auth',
    'uses' => 'EntryController@showEdit'
));
Route::post('entries/save', array(
    'as' => 'entry_save',
    'before' => array(
        'auth',
        'csrf'
    ),
    'uses' => 'EntryController@processSave',
));
Route::get('entries/{id}/delete', array(
    'as' => 'entry_delete',
    'before' => 'auth',
    'uses' => 'EntryController@showDelete'
));
Route::post('entries/{id}/delete', array(
    'as' => 'entry_delete',
    'before' => array(
        'auth',
        'csrf'
    ),
    'uses' => 'EntryController@processDelete',
));
Route::get('entries/{entry}', array(
    'as' => 'entry_view',
    'uses' => 'EntryController@showView'
));

Route::get('', array(
    'as' => 'angular',
    'uses' => 'AngularController@show'
));
Route::group(array('prefix' => 'resources'), function()
{
    Route::resource('entries', 'EntryResourceManager', array('only' => array(
            'index',
            'store',
            'update',
            'destroy'
        )));
});
