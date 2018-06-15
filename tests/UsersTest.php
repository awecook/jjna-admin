<?php

use App\Models\EmployeeAuth as Administrator;

class UsersTest extends TestCase
{
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = Administrator::first();

        $this->be($this->user, 'admin');
    }

    public function testUsersIndexPage()
    {
        $this->visit('admin/auth/users')
            ->see('Administrator');
    }

    public function testCreateUser()
    {
        $user = [
            'Waiter_Login'              => 'Test',
            'name'                  => 'Name',
            'Waiter_Password'              => '123456',
            'password_confirmation' => '123456',
        ];

        // create user
        $this->visit('admin/auth/users/create')
            ->see('Create')
            ->submitForm('Submit', $user)
            ->seePageIs('admin/auth/users')
            ->seeInDatabase(config('admin.database.users_table'), ['Waiter_Login' => 'Test']);

        // assign role to user
        $this->visit('admin/auth/users/2/edit')
            ->see('Edit')
            ->submitForm('Save', ['roles' => [1]])
            ->seePageIs('admin/auth/users')
            ->seeInDatabase(config('admin.database.role_users_table'), ['Waiter_ID' => 2, 'role_id' => 1]);

        $this->visit('admin/auth/logout')
            ->dontSeeIsAuthenticated('admin')
            ->seePageIs('admin/auth/login')
            ->submitForm('Login', ['Waiter_Login' => $user['Waiter_Login'], 'Waiter_Password' => $user['Waiter_Password']])
            ->see('dashboard')
            ->seeIsAuthenticated('admin')
            ->seePageIs('admin');

        $this->assertTrue($this->app['auth']->guard('admin')->getUser()->isAdministrator());

        $this->see('<span>Users</span>')
            ->see('<span>Roles</span>')
            ->see('<span>Permission</span>')
            ->see('<span>Operation log</span>')
            ->see('<span>Menu</span>');
    }

    public function testUpdateUser()
    {
        $this->visit('admin/auth/users/'.$this->user->id.'/edit')
            ->see('Create')
            ->submitForm('Save', ['name' => 'test', 'roles' => [1]])
            ->seePageIs('admin/auth/users')
            ->seeInDatabase(config('admin.database.users_table'), ['name' => 'test']);
    }

    public function testResetPassword()
    {
        $password = 'odjwyufkglte';

        $data = [
            'Waiter_Password'              => $password,
            'password_confirmation' => $password,
            'roles'                 => [1],
        ];

        $this->visit('admin/auth/users/'.$this->user->id.'/edit')
            ->see('Create')
            ->submitForm('Save', $data)
            ->seePageIs('admin/auth/users')
            ->visit('admin/auth/logout')
            ->dontSeeIsAuthenticated('admin')
            ->seePageIs('admin/auth/login')
            ->submitForm('Login', ['Waiter_Login' => $this->user->username, 'Waiter_Password' => $password])
            ->see('dashboard')
            ->seeIsAuthenticated('admin')
            ->seePageIs('admin');
    }
}
