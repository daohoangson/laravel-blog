<?php

class RegisterController extends BaseController
{

    public function showRegister()
    {
        return View::make('register');
    }

    public function processRegister()
    {
        $input = array(
            'email' => Input::get('email'),
            'password' => Input::get('password'),
        );

        $validator = Validator::make($input, array(
            'email' => array(
                'required',
                'email',
                'unique:users'
            ),
            'password' => array(
                'required',
                'min:8'
            ),
        ));

        if ($validator->fails()) {
            return Redirect::to('/register')->withInput()->withErrors($validator);
        }

        $user = new User();
        $user->email = $input['email'];
        $user->password = Hash::make($input['password']);
        $user->save();

        Auth::attempt($input);
        return Redirect::to('/');
    }

}