<?php

class UserController extends BaseController
{
    public function showList()
    {
        $users = User::with('roles')->get();

        return View::make('user/list', array('users' => $users));
    }

    public function showProfile()
    {
        return View::make('user/view', array('user' => Auth::user()));
    }

    public function showView($user)
    {
        return View::make('user/view', array('user' => $user));
    }

    public function processSave()
    {
        $user = User::find(Input::get('user_id'));
        if (empty($user)) {
            return 'The specified user could not be found.';
        }

        $authUser = Auth::user();
        if (!$authUser->canEditUser($user)) {
            return 'Permission denied.';
        }

        $input = array(
            'email' => Input::get('email'),
            'password' => Input::get('password'),
        );

        $validator = Validator::make($input, array(
            'email' => array(
                'required',
                'email',
            ),
            'password' => array('min:8'),
        ));

        if ($validator->fails()) {
            return Redirect::route('user_view', $user->id)->withInput()->withErrors($validator);
        }

        if ($input['email'] != $user->email) {
            $userWithEmail = User::where('email', '=', $input['email'])->first();
            if (!empty($userWithEmail)) {
                return Redirect::route('user_view', $user->id)->withInput()->withErrors(array('email' => 'The email has already been taken.'));
            }

            $user->email = $input['email'];
        }

        if (!empty($input['password'])) {
            $user->password = Hash::make($input['password']);
        }

        $user->save();

        if ($authUser->isAdministrator()) {
            $inputRoles = Input::get('roles', array());

            if ($authUser->id == $user->id) {
                // make sure admin doesn't self revoke his/her admin right
                foreach ($authUser->roles as $role) {
                    if ($role->title === 'Administrator') {
                        $inputRoles[] = $role->id;
                    }
                }
            }

            $user->roles()->sync($inputRoles);
        }

        return Redirect::route('user_view', $user->id);
    }

}
