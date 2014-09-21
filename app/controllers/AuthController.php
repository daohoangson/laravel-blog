<?php

class AuthController extends BaseController
{

    public function showLogin()
    {
        if (Auth::check()) {
            // Auth reported that user is logged it, redirect to index page
            return Redirect::to('/');
        }

        // show the login form
        return View::make('auth.login');
    }

    public function processLogin()
    {
        $input = array(
            'email' => Input::get('email'),
            'password' => Input::get('password'),
        );

        $validator = Validator::make($input, array(
            'email' => array('required'),
            'password' => array('required'),
        ));

        if ($validator->fails()) {
            return Redirect::to('/auth/login')->withInput()->withErrors($validator);
        }

        $remember = Input::get('remember');
        $remember = !empty($remember);
        if (Auth::attempt($input, $remember)) {
            return Redirect::intended();
        }
        else {
            return Redirect::to('/auth/login')->withInput()->withErrors(array('password' => 'Invalid email or password.'));
        }
    }

    public function processLogout()
    {
        Auth::logout();

        return Redirect::intended();
    }

}
