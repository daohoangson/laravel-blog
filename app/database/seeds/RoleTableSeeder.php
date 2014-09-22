<?php

class RoleTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('roles')->delete();

        Role::create(array('title' => 'Administrator'));
		Role::create(array('title' => 'Editor'));
		Role::create(array('title' => 'Reader'));
    }

}
