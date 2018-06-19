<?php

namespace Encore\Admin\Auth\Database;

use Encore\Admin\Traits\AdminBuilder;
use App\Models\Employee;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Administrator.
 *
 * @property string Waiter_ID
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
 *
 * @property Role[] $roles
 *
 */
class Administrator extends Employee implements AuthenticatableContract
{
    use Authenticatable, AdminBuilder, HasPermissions;
}
