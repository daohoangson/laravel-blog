<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface
{

    use UserTrait, RemindableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array(
        'password',
        'remember_token'
    );

    public function roles()
    {
        return $this->belongsToMany('Role');
    }

    public function isAdministrator()
    {
        return $this->roles->contains(1);
    }

    public function isEditor()
    {
        return $this->roles->contains(2);
    }

    public function isReader()
    {
        return $this->roles->contains(3);
    }

    public function canEditUser(User $user)
    {
        if ($this->isAdministrator()) {
            return true;
        }

        if ($this->id == $user->id) {
            return true;
        }

        return false;
    }

}
