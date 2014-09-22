<?php
use \FunctionalTester;

class RegisterCest
{
    const EMAIL = 'email@domain.com';
    const PASSWORD = 'password';
    const NEW_EMAIL = 'email2@domain.com';

    public function _before(FunctionalTester $I)
    {
        $user = new User();
        $user->email = self::EMAIL;
        $user->password = Hash::make(self::PASSWORD);
        $user->save();
    }

    public function registerSuccessfully(FunctionalTester $I)
    {
        $I->amOnPage('/register');
        $I->fillField('email', self::NEW_EMAIL);
        $I->fillField('password', self::PASSWORD);
        $I->click('.btn-default');

        $I->seeAuthentication();
        $I->logout();
        $I->dontSeeAuthentication();
    }

    public function registerWithoutEmail(FunctionalTester $I)
    {
        $I->amOnPage('/register');
        $I->fillField('password', self::PASSWORD);
        $I->click('.btn-default');

        $I->seeSessionErrorMessage(array('email' => 'The email field is required.'));
    }

    public function registerWithInvalidEmail(FunctionalTester $I)
    {
        $I->amOnPage('/register');
        $I->fillField('email', 'qwerty');
        $I->fillField('password', self::PASSWORD);
        $I->click('.btn-default');

        $I->seeSessionErrorMessage(array('email' => 'The email must be a valid email address.'));
    }

    public function registerEmailNotUnique(FunctionalTester $I)
    {
        $I->amOnPage('/register');
        $I->fillField('email', self::EMAIL);
        $I->fillField('password', self::PASSWORD);
        $I->click('.btn-default');

        $I->seeSessionErrorMessage(array('email' => 'The email has already been taken.'));
    }

    public function registerWithoutPassword(FunctionalTester $I)
    {
        $I->amOnPage('/register');
        $I->fillField('email', self::NEW_EMAIL);
        $I->click('.btn-default');

        $I->seeSessionErrorMessage(array('password' => 'The password field is required.'));
    }

    public function registerWithShortPassword(FunctionalTester $I)
    {
        for ($i = 1; $i < 8; $i++) {
        	$I->wantTo(sprintf('register with %d character password', $i));
            $I->amOnPage('/register');
            $I->fillField('email', self::NEW_EMAIL);
            $I->fillField('password', str_repeat('1', $i));
            $I->click('.btn-default');

            $I->seeSessionErrorMessage(array('password' => 'The password must be at least 8 characters.'));
        }
    }

}
