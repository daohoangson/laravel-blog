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
            return Redirect::route('register')->withInput()->withErrors($validator);
        }

        $user = new User();
        $user->email = $input['email'];
        $user->password = Hash::make($input['password']);
        $user->save();

        if (User::all()->count() == 1) {
            // this is the first user
            // attach all roles
            $roles = Role::all();
			$roleIds = array();
			foreach ($roles as $role)
			{
				$roleIds[] = $role->id;
			}
			
            $user->roles()->attach($roleIds);
        } else {
        	// attach reader role
        	$readerRole = Role::where('title', '=', 'Reader')->first();
            $user->roles()->attach($readerRole->id);
        }

        Auth::attempt($input);
        return Redirect::route('index');
    }

}
