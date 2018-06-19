<?php

namespace Encore\Admin\Controllers;

use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Auth\Database\Permission;
use Encore\Admin\Auth\Database\Role;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Routing\Controller;

class UserController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header(trans('admin.administrator'));
            $content->description(trans('admin.list'));
            $content->body($this->grid()->render());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     *
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header(trans('admin.administrator'));
            $content->description(trans('admin.edit'));
            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {
            $content->header(trans('admin.administrator'));
            $content->description(trans('admin.create'));
            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Administrator::grid(function (Grid $grid) {
            $grid->Waiter_ID('Waiter ID')->sortable();
            $grid->Waiter_Login('Waiter Login');
            $grid->Email('Email');
            $grid->roles('Roles')->pluck('name')->label();

            $grid->actions(function (Grid\Displayers\Actions $actions) {
                if ($actions->getKey() == 1) {
                    $actions->disableDelete();
                }
            });

            $grid->tools(function (Grid\Tools $tools) {
                $tools->batch(function (Grid\Tools\BatchActions $actions) {
                    $actions->disableDelete();
                });
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        return Administrator::form(function (Form $form) {

            $form->text('Waiter_ID', 'Waiter ID')->rules('required');
            $form->text('Waiter_Login', 'Waiter Login')->rules('required');
            /*$form->text('name', trans('admin.name'))->rules('required');
            $form->image('avatar', trans('admin.avatar'));*/
            $form->password('Waiter_Password', 'Waiter Password')->rules('required|confirmed')->default(function ($form) {
                return $form->model()->Waiter_Password;
            });
            $form->password('Waiter_Password_confirmation', trans('admin.password_confirmation'))->rules('required')
                ->default(function ($form) {
                    return $form->model()->Waiter_Password;
                });

            $form->ignore(['Waiter_Password_confirmation']);

            $form->multipleSelect('roles', 'Access Level')->options(Role::all()->pluck('name', 'id'));
            $form->multipleSelect('permissions', trans('admin.permissions'))->options(Permission::all()->pluck('name', 'id'));

            $form->select('Store_ID')->options(\App\Models\Append\Store::all()->pluck('Store_ID'));
            $form->text('Government_ID');
            $form->text('Country');
            $form->text('State_Province');
            $form->text('County');
            $form->text('City');
            $form->text('Street_Address_Line_1');
            $form->text('Street_Address_Line_2');
            $form->text('Zip_Code');
            $form->text('Phone_Area_Code');
            $form->text('Phone');
            $form->email('Email')->rules('required');

            $form->saving(function (Form $form) {
                if ($form->Waiter_Password && $form->model()->Waiter_Password != $form->Waiter_Password) {
                    $form->Waiter_Password = bcrypt($form->Waiter_Password);
                }
                if ($form->roles && $form->model()->Access_Level != $form->roles[0]) {
                    $form->Access_Level = $form->roles[0];
                }
            });
        });
    }
}
