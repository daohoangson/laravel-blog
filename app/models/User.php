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

    public function entries()
    {
        return $this->hasMany('Entry');
    }

    public function isAdministrator()
    {
        return $this->_hasRoleTitle('Administrator');
    }

    public function isEditor()
    {
        return $this->_hasRoleTitle('Editor');
    }

    public function isReader()
    {
        return $this->_hasRoleTitle('Reader');
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

    public function canCreateEntry()
    {
        return ($this->isEditor() OR $this->isAdministrator());
    }

    public function canEditEntry(Entry $entry)
    {
    	if (!empty($entry->deleted_at))
		{
			return false;
		}

        if ($this->isAdministrator()) {
            return true;
        }

        if ($entry->user_id == $this->id) {
            return true;
        }

        return false;
    }

    protected function _hasRoleTitle($title)
    {
        $found = false;
        $this->roles->each(function($role) use ($title, &$found)
        {
            if ($role->title == $title) {
                $found = true;
            }
        });

        return $found;
    }

}
