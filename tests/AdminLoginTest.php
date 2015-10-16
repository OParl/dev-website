<?php

class AdminLoginTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testLogin()
    {
        $this->visit('/admin')->see('Login');

        $this->type('tester@oparl.org', 'email');
        $this->type('testerpassword', 'password');
    }
}
