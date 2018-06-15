<?php

use App\Models\EmployeeAuth as Administrator;
use Illuminate\Support\Facades\File;

class UserSettingTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->be(Administrator::first(), 'admin');
    }

    public function testVisitSettingPage()
    {
        $this->visit('admin/auth/setting')
            ->see('User setting')
            ->see('Waiter_Login')
            ->see('Name')
            ->see('Avatar')
            ->see('Waiter_Password')
            ->see('Password confirmation');

        $this->seeElement('input[value=Administrator]')
            ->seeInElement('.box-body', 'administrator');
    }

    public function testUpdateName()
    {
        $data = [
            'name' => 'tester',
        ];

        $this->visit('admin/auth/setting')
            ->submitForm('Save', $data)
            ->seePageIs('admin/auth/setting');

        $this->seeInDatabase('admin_users', ['name' => $data['name']]);
    }

    public function testUpdateAvatar()
    {
        File::cleanDirectory(public_path('uploads/images'));

        $this->visit('admin/auth/setting')
            ->attach(__DIR__.'/assets/test.jpg', 'avatar')
            ->press('Save')
            ->seePageIs('admin/auth/setting');

        $avatar = Administrator::first()->avatar;

        $this->assertEquals('http://localhost:8000/uploads/images/test.jpg', $avatar);
    }

    public function testUpdatePasswordConfirmation()
    {
        $data = [
            'Waiter_Password'              => '123456',
            'password_confirmation' => '123',
        ];

        $this->visit('admin/auth/setting')
            ->submitForm('Save', $data)
            ->seePageIs('admin/auth/setting')
            ->see('The Password confirmation does not match.');
    }

    public function testUpdatePassword()
    {
        $data = [
            'Waiter_Password'              => '123456',
            'password_confirmation' => '123456',
        ];

        $this->visit('admin/auth/setting')
            ->submitForm('Save', $data)
            ->seePageIs('admin/auth/setting');

        $this->assertTrue(app('hash')->check($data['Waiter_Password'], Administrator::first()->makeVisible('Waiter_Password')->password));

        $this->visit('admin/auth/logout')
            ->seePageIs('admin/auth/login')
            ->dontSeeIsAuthenticated('admin');

        $credentials = ['Waiter_Login' => 'admin', 'Waiter_Password' => '123456'];

        $this->visit('admin/auth/login')
            ->see('login')
            ->submitForm('Login', $credentials)
            ->see('dashboard')
            ->seeCredentials($credentials, 'admin')
            ->seeIsAuthenticated('admin')
            ->seePageIs('admin');
    }
}
