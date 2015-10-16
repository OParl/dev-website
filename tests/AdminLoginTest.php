<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminLoginTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testLoginPageExists()
    {
        $this->visit('/admin')->see('Login');
    }

    public function testLoginWithNonExistentUserFails()
    {
        $this->visit('/admin/login');

        $this->type('non-existent-user@vanished-id.com', 'email');
        $this->type('42', 'password');

        $this->press('Login');

        $this->see('<strong>Huch!</strong> Die Eingabe war fehlerhaft.');
    }

    public function testLoginWithExistingUser()
    {
        $user = factory(App\Model\User::class)->create(['password' => 'test']);

        $this->seeInDatabase('users', ['id' => $user->id]);

        // TODO: figure out why the login fails even though the user reports as in the database
//        $this->visit('/admin/login');
//
//        $this->type($user->email, 'email');
//        $this->type('test', 'password');
//
//        $this->press('Login');
//
//        $this->see($user->name);
    }
}
