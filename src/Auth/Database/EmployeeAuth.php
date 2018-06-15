<?php

namespace App\Models;

use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Auth\Database\HasPermissions;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
//use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
//use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class EmployeeAuth
 * @package App\Models
 * @version June 14, 2018, 4:11 pm UTC
 *
 * @property string Store_ID
 * @property string Waiter_Login
 * @property string Waiter_Password
 * @property string Government_ID
 * @property string Country
 * @property string State_Province
 * @property string County
 * @property string City
 * @property string Street_Address_Line_1
 * @property string Street_Address_Line_2
 * @property string Zip_Code
 * @property string Phone_Area_Code
 * @property string Phone
 * @property string Email
 * @property boolean Access_Level
 * @property string remember_token
 * @property string api_token
 */
class EmployeeAuth extends Employee implements AuthenticatableContract, CanResetPasswordContract, JWTSubject
{
    use Authenticatable, AdminBuilder, HasPermissions, CanResetPassword;

    public function getAuthIdentifierName()
    {
        return 'Waiter_Login';
    }

    public function getAuthIdentifier()
    {
        return $this->Waiter_Login;
    }

    public function getAuthPassword()
    {
        return $this->Waiter_Password;
    }

    public function getJWTIdentifier()
    {
        return "Waiter_Login";
    }

    public function getJWTCustomClaims()
    {
        return [];
    }


}
