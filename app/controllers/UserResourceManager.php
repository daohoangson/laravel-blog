<?php

class UserResourceManager extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return Response::json(array('data' => $this->_prepareUsers(User::all())));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
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
            return Response::json(array('errors' => $validator->messages()));
        }

        $user = new User();
        $user->email = $input['email'];
        $user->password = Hash::make($input['password']);
        $user->save();

        return $this->_responseUser($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $user = User::find($id);
        if (empty($user)) {
            App::abort(404);
        }

        return $this->_responseUser($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        $user = User::find($id);
        if (empty($user)) {
            App::abort(404);
        }

        if (Auth::guest() OR !Auth::user()->canEditUser($user)) {
            App::abort(403);
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
            return Response::json(array('errors' => $validator->messages()));
        }

        if ($input['email'] != $user->email) {
            $userWithEmail = User::where('email', '=', $input['email'])->first();
            if (!empty($userWithEmail)) {
                return Response::json(array('errors' => array('email' => 'The email has already been taken.')));
            }

            $user->email = $input['email'];
        }

        if (!empty($input['password'])) {
            $user->password = Hash::make($input['password']);
        }

        $user->save();

        if (Auth::user()->isAdministrator()) {
            $inputRoles = Input::get('roles', array());

            if (Auth::user()->id == $user->id) {
                // make sure admin doesn't self revoke his/her admin right
                foreach (Auth::user()->roles as $role) {
                    if ($role->title === 'Administrator') {
                        $inputRoles[] = $role->id;
                    }
                }
            }

            $user->roles()->sync($inputRoles);
        }

        return $this->_responseUser($user);
    }

    protected function _responseUser(User $user)
    {
        return Response::json(array('user' => $this->_prepareUser($user)));
    }

    protected function _prepareUsers($users)
    {
        $usersArray = array();

        foreach ($users as $user) {
            $usersArray[] = $this->_prepareUser($user);
        }

        return $usersArray;
    }

    protected function _prepareUser(User $user)
    {
        $userArray = $user->toArray();

        if (Auth::guest()) {
            $userArray['canEditUser'] = false;
            $userArray['canEditRole'] = false;
        } else {
            $userArray['canEditUser'] = Auth::user()->canEditUser($user);
            $userArray['canEditRole'] = Auth::user()->isAdministrator();
        }

        unset($userArray['password']);

        $roles = Role::all();
        $userArray['roles'] = array();
        foreach ($roles as $role)
        {
            $userArray['roles'][$role->id] = $role->toArray();
            $userArray['roles'][$role->id]['isUserRole'] = $user->roles->contains($role->id);
        }

        return $userArray;
    }

}
