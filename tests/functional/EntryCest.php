<?php
use \FunctionalTester;

class EntryCest
{
    const ADMIN_EMAIL = 'admin@domain.com';
    const EDITOR_EMAIL = 'editor@domain.com';
    const PASSWORD = '12345678';

    public function _before(FunctionalTester $I)
    {
        $roles = Role::all();
        $adminRoleId = 0;
        $editorRoldeId = 0;
        $readerRoleId = 0;
        foreach ($roles as $role) {
            switch ($role->title) {
                case 'Administrator':
                    $adminRoleId = $role->id;
                    break;
                case 'Editor':
                    $editorRoldeId = $role->id;
                    break;
                case 'Reader':
                    $readerRoleId = $role->id;
                    break;
            }
        }

        $admin = new User();
        $admin->email = self::ADMIN_EMAIL;
        $admin->password = Hash::make(self::PASSWORD);
        $admin->save();
        $admin->roles()->attach($adminRoleId);

        $editor = new User();
        $editor->email = self::EDITOR_EMAIL;
        $editor->password = Hash::make(self::PASSWORD);
        $editor->save();
        $editor->roles()->attach($editorRoldeId);
    }

    public function adminCreateEntrySelfEdit(FunctionalTester $I)
    {
        $I->amLoggedAs(array(
            'email' => SELF::ADMIN_EMAIL,
            'password' => SELF::PASSWORD,
        ));

        $entry = $this->_createEntry($I);
        $entry = $this->_editAndSoftDeleteEntry($I, $entry);

        $I->seeCurrentUrlEquals('');
        $I->see('Restore / Hard Delete');

        $entry = $this->_restoreEntry($I, $entry);
        $entry = $this->_editAndSoftDeleteEntry($I, $entry);

        $entry = $this->_hardDeleteEntry($I, $entry);
    }

    public function adminCreateEntryEditorEdit(FunctionalTester $I)
    {
        $I->amLoggedAs(array(
            'email' => SELF::ADMIN_EMAIL,
            'password' => SELF::PASSWORD,
        ));

        $entry = $this->_createEntry($I);

        $I->logout();

        $I->amLoggedAs(array(
            'email' => SELF::EDITOR_EMAIL,
            'password' => SELF::PASSWORD,
        ));

        $I->assertTrue($this->_seeExceptionThrown('Symfony\Component\HttpKernel\Exception\HttpException', function() use ($I, $entry)
        {
            $I->amOnRoute('entry_edit', $entry->id);
        }));
    }

    public function editorCreateEntrySelfEditAdminHardDelete(FunctionalTester $I)
    {
        $I->amLoggedAs(array(
            'email' => SELF::EDITOR_EMAIL,
            'password' => SELF::PASSWORD,
        ));

        $entry = $this->_createEntry($I);
        $entry = $this->_editAndSoftDeleteEntry($I, $entry);

        $I->logout();

        $I->amLoggedAs(array(
            'email' => SELF::ADMIN_EMAIL,
            'password' => SELF::PASSWORD,
        ));

        $this->_hardDeleteEntry($I, $entry);
    }

    public function editorCreateEntryAdminEdit(FunctionalTester $I)
    {
        $I->amLoggedAs(array(
            'email' => SELF::EDITOR_EMAIL,
            'password' => SELF::PASSWORD,
        ));

        $entry = $this->_createEntry($I);

        $I->logout();

        $I->amLoggedAs(array(
            'email' => SELF::ADMIN_EMAIL,
            'password' => SELF::PASSWORD,
        ));

        $entry = $this->_editAndSoftDeleteEntry($I, $entry);
    }

    protected function _createEntry(FunctionalTester $I)
    {
        $I->amOnPage('/');
        $I->click('Create New Entry');

        $I->seeCurrentRouteIs('entry_create');
        $title = 'Title ' . md5(rand());
        $body = 'Body ' . md5(rand());
        $I->fillField('title', $title);
        $I->fillField('body', $body);
        $I->click('.btn-default');

        $entry = Entry::where('title', '=', $title)->first();
        $I->assertTrue(!empty($entry));

        $I->seeCurrentUrlMatches('#^/entries/\d+$#');
        $I->seeInTitle($title);
        $I->see('Edit');

        return $entry;
    }

    protected function _editAndSoftDeleteEntry(FunctionalTester $I, Entry $entry)
    {
        $I->amOnRoute('entry_edit', $entry->id);
        $I->seeCurrentUrlMatches('#^/entries/\d+/edit$#');
        $I->seeInField('title', $entry->title);
        $I->seeInField('body', $entry->body);
        $I->checkOption('input[name=delete]');
        $I->click('.btn-default');

        $entry = Entry::onlyTrashed()->find($entry->id);
        $I->assertTrue(!empty($entry));

        $I->seeCurrentUrlEquals('');

        return $entry;
    }

    protected function _hardDeleteEntry(FunctionalTester $I, Entry $entry)
    {
        $I->amOnRoute('entry_delete', $entry->id);
        $I->seeCurrentUrlMatches('#^/entries/\d+/delete$#');
        $I->selectOption('input[name=action]', 'hard_delete');
        $I->click('.btn-default');

        $entry = Entry::find($entry->id);
        $I->assertFalse(!empty($entry));

        $I->seeCurrentUrlEquals('');
    }

    protected function _restoreEntry(FunctionalTester $I, Entry $entry)
    {
        $I->amOnRoute('entry_delete', $entry->id);
        $I->seeCurrentUrlMatches('#^/entries/\d+/delete$#');
        $I->selectOption('input[name=action]', 'restore');
        $I->click('.btn-default');

        $entry = Entry::find($entry->id);
        $I->assertTrue(!empty($entry));

        $I->seeCurrentUrlMatches('#^/entries/\d+$#');

        return $entry;
    }

    protected function _seeExceptionThrown($exception, $function)
    {
        try {
            $function();

            return false;
        }
        catch (Exception $e) {
            if (get_class($e) == $exception) {
                return true;
            }

            return false;
        }
    }

}
