<?php

namespace Encore\Admin\Controllers;

use Encore\Admin\Auth\Database\Permission;
use Encore\Admin\Auth\Database\Role;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Routing\Controller;

class RoleController extends Controller
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
            $content->header(trans('admin.roles'));
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
            $content->header(trans('admin.roles'));
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
            $content->header(trans('admin.roles'));
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
        return Admin::grid(Role::class, function (Grid $grid) {
            $grid->id('Access Level')->sortable();
            $grid->slug(trans('admin.slug'));
            $grid->name(trans('admin.name'));

            $grid->permissions(trans('admin.permission'))->pluck('name')->label();

            $grid->actions(function (Grid\Displayers\Actions $actions) {
                if ($actions->row->slug == 'administrator') {
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
        return Admin::form(Role::class, function (Form $form) {
            $form->text('id', 'Access Level')->rules('required');
            $form->text('slug', trans('admin.slug'))->rules('required');
            $form->text('name', trans('admin.name'))->rules('required');
            $form->listbox('permissions', trans('admin.permissions'))->options(Permission::all()->pluck('name', 'id'));
        });
    }
}
