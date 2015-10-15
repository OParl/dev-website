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
        $this->visit('/admin/dashboard');
        $this->assertRedirectedToRoute('admin.login');
    }
}
