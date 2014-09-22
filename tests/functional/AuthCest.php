<?php
use \FunctionalTester;

class AuthCest
{
    const EMAIL = 'email@domain.com';
    const PASSWORD = 'password';

    public function _before(FunctionalTester $I)
    {
        $user = new User();
        $user->email = self::EMAIL;
        $user->password = Hash::make(self::PASSWORD);
        $user->save();
    }

    public function loginUsingUserRecord(FunctionalTester $I)
    {
        $I->amLoggedAs(User::firstOrNew(array()));

        $this->_shouldBeOkie($I);
    }

    public function loginUsingCredentials(FunctionalTester $I)
    {
        $I->amLoggedAs(array(
            'email' => SELF::EMAIL,
            'password' => SELF::PASSWORD,
        ));

        $this->_shouldBeOkie($I);
    }

    public function loginFailed(FunctionalTester $I)
    {
        $I->amLoggedAs(array(
            'email' => SELF::EMAIL,
            'password' => SELF::PASSWORD . time(),
        ));

        $I->amOnPage('/');
        $I->dontSeeAuthentication();
    }

    public function loginUsingForm(FunctionalTester $I)
    {
        $I->amOnPage('/auth/login');
        $I->fillField('email', self::EMAIL);
        $I->fillField('password', self::PASSWORD);
        $I->click('.btn-default');

        $this->_shouldBeOkie($I);
    }

    public function loginUsingFormWithError(FunctionalTester $I)
    {
        $I->amOnPage('/auth/login');
        $I->fillField('email', self::EMAIL);
        $I->fillField('password', self::PASSWORD . time());
        $I->click('.btn-default');

        $I->seeSessionErrorMessage(array('password' => 'Invalid email or password.'));
    }

    public function loginUsingFormWithoutEmail(FunctionalTester $I)
    {
        $I->amOnPage('/auth/login');
        $I->fillField('password', self::PASSWORD . time());
        $I->click('.btn-default');

        $I->seeSessionErrorMessage(array('email' => 'The email field is required.'));
    }

    public function loginUsingFormWithoutPassword(FunctionalTester $I)
    {
        $I->amOnPage('/auth/login');
        $I->fillField('email', self::EMAIL);
        $I->click('.btn-default');

        $I->seeSessionErrorMessage(array('password' => 'The password field is required.'));
    }

    protected function _shouldBeOkie(FunctionalTester $I)
    {
        $I->amOnPage('/');
        $I->seeAuthentication();
        $I->logout();
        $I->dontSeeAuthentication();
    }

}
