<?php
use \FunctionalTester;

class UserCest
{
    const ADMIN_EMAIL = 'admin@domain.com';
    const USER_EMAIL = 'user@domain.com';
    const PASSWORD = '12345678';

    public function _before(FunctionalTester $I)
    {
        $roles = Role::all();
        $roleIds = array();
        $readerRoleId = 0;
        foreach ($roles as $role) {
            $roleIds[] = $role->id;
            if ($role->title == 'Reader') {
                $readerRoleId = $role->id;
            }
        }

        $admin = new User();
        $admin->email = self::ADMIN_EMAIL;
        $admin->password = Hash::make(self::PASSWORD);
        $admin->save();
        $admin->roles()->attach($roleIds);

        $user = new User();
        $user->email = self::USER_EMAIL;
        $user->password = Hash::make(self::PASSWORD);
        $user->save();
        $user->roles()->attach($readerRoleId);
    }

    public function showList(FunctionalTester $I)
    {
        $I->amOnPage('/users');
        $I->see(self::ADMIN_EMAIL);
        $I->see(self::USER_EMAIL);
    }

    public function adminEditSelf(FunctionalTester $I)
    {
        $I->amLoggedAs(array(
            'email' => SELF::ADMIN_EMAIL,
            'password' => SELF::PASSWORD,
        ));

        $I->amOnPage('/users');
        $I->click(self::ADMIN_EMAIL);

        $this->_adminEditSelfShouldBe($I);
    }

    public function adminEditProfile(FunctionalTester $I)
    {
        $I->amLoggedAs(array(
            'email' => SELF::ADMIN_EMAIL,
            'password' => SELF::PASSWORD,
        ));

        $I->amOnPage('/profile');

        $this->_adminEditSelfShouldBe($I);
    }

    protected function _adminEditSelfShouldBe(FunctionalTester $I)
    {
        $newEmail = time() . self::ADMIN_EMAIL;
        $newPassword = time() . self::PASSWORD;

        $I->seeInField('email', self::ADMIN_EMAIL);
        $I->fillField('email', $newEmail);
        $I->fillField('password', $newPassword);

        $roles = Role::all();
        foreach ($roles as $role) {
            $selector = 'input[value=' . $role->id . ']';

            $I->seeCheckboxIsChecked($selector);
            $I->uncheckOption($selector);
        }

        // submit the changes
        $I->click('.btn-default');

        // check for our changes
        $I->dontSeeElement('.has-error');
        $I->seeInField('email', $newEmail);

        foreach ($roles as $role) {
            $selector = 'input[value=' . $role->id . ']';

            if ($role->title == 'Administrator') {
                // unchecked but the system won't detach admin role for self edit
                $I->seeCheckboxIsChecked($selector);
            } else {
                $I->dontSeeCheckboxIsChecked($selector);
            }
        }

        // check for password
        $I->logout();
        $I->dontSeeAuthentication();
        $I->amLoggedAs(array(
            'email' => $newEmail,
            'password' => $newPassword,
        ));
        $I->seeAuthentication();
    }

    public function adminEditUser(FunctionalTester $I)
    {
        $I->amLoggedAs(array(
            'email' => SELF::ADMIN_EMAIL,
            'password' => SELF::PASSWORD,
        ));

        $I->amOnPage('/users');
        $I->click(self::USER_EMAIL);

        $I->seeInField('email', self::USER_EMAIL);

        $roles = Role::all();
        foreach ($roles as $role) {
            $selector = 'input[value=' . $role->id . ']';

            if ($role->title == 'Reader') {
                $I->seeCheckboxIsChecked($selector);
            } else {
                $I->dontSeeCheckboxIsChecked($selector);
                $I->checkOption($selector);
            }
        }

        // submit the changes
        $I->click('.btn-default');

        // check for our changes
        $I->dontSeeElement('.has-error');
        foreach ($roles as $role) {
            $selector = 'input[value=' . $role->id . ']';

            $I->seeCheckboxIsChecked($selector);
        }
    }

    public function userEditSelf(FunctionalTester $I)
    {
        $newEmail = time() . self::USER_EMAIL;
        $newPassword = time() . self::PASSWORD;

        $I->amLoggedAs(array(
            'email' => SELF::USER_EMAIL,
            'password' => SELF::PASSWORD,
        ));

        $I->amOnPage('/users');
        $I->click(self::USER_EMAIL);

        $I->seeInField('email', self::USER_EMAIL);
        $I->fillField('email', $newEmail);
        $I->fillField('password', $newPassword);

        $roles = Role::all();
        foreach ($roles as $role) {
            $selector = 'input[value=' . $role->id . ']';

            $I->dontSeeCheckboxIsChecked($selector);
        }

        // submit the changes
        $I->click('.btn-default');

        // check for our changes
        $I->dontSeeElement('.has-error');
        $I->seeInField('email', $newEmail);

        // check for password
        $I->logout();
        $I->dontSeeAuthentication();
        $I->amLoggedAs(array(
            'email' => $newEmail,
            'password' => $newPassword,
        ));
        $I->seeAuthentication();
    }

    public function userEditSelfWithoutEmail(FunctionalTester $I)
    {
        $I->amLoggedAs(array(
            'email' => SELF::USER_EMAIL,
            'password' => SELF::PASSWORD,
        ));

        $I->amOnPage('/profile');

        $I->seeInField('email', self::USER_EMAIL);
        $I->fillField('email', '');

        // submit the changes
        $I->click('.btn-default');

        // check for error
        $I->seeSessionErrorMessage(array('email' => 'The email field is required.'));
    }

    public function userEditSelfWithInvalidEmail(FunctionalTester $I)
    {
        $I->amLoggedAs(array(
            'email' => SELF::USER_EMAIL,
            'password' => SELF::PASSWORD,
        ));

        $I->amOnPage('/profile');

        $I->seeInField('email', self::USER_EMAIL);
        $I->fillField('email', 'qwerty');

        // submit the changes
        $I->click('.btn-default');

        // check for error
        $I->seeSessionErrorMessage(array('email' => 'The email must be a valid email address.'));
    }

    public function userEditSelfEmailNotUnique(FunctionalTester $I)
    {
        $I->amLoggedAs(array(
            'email' => SELF::USER_EMAIL,
            'password' => SELF::PASSWORD,
        ));

        $I->amOnPage('/profile');

        $I->seeInField('email', self::USER_EMAIL);
        $I->fillField('email', self::ADMIN_EMAIL);

        // submit the changes
        $I->click('.btn-default');

        // check for error
        $I->seeSessionErrorMessage(array('email' => 'The email has already been taken.'));
    }

    public function userEditSelfWithShortPassword(FunctionalTester $I)
    {
        $I->amLoggedAs(array(
            'email' => SELF::USER_EMAIL,
            'password' => SELF::PASSWORD,
        ));

        $I->amOnPage('/profile');

        for ($i = 1; $i < 8; $i++) {
            $I->seeInField('email', self::USER_EMAIL);
            $I->fillField('password', str_repeat('1', $i));

            // submit the changes
            $I->click('.btn-default');

            // check for error
            $I->seeSessionErrorMessage(array('password' => 'The password must be at least 8 characters.'));
        }
    }

}
