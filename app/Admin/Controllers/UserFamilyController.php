<?php

namespace App\Admin\Controllers;

use App\Models\User;
use App\Models\UserFamily;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class UserFamilyController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'UserFamily';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new UserFamily());

        $grid->column('id', __('Id'))->filter();
        $grid->column('image', __('Image'))->image();
        $grid->column('user_id', __('Patient'))->display(function ($id) {
            return "<a href='".route('admin.patients.patients.edit', $id)."'>Patient</a>";
        })->filter();
        $grid->column('name', __('Name'))->filter();
        $grid->column('title', __('Title'))->filter();
        $grid->column('relation', __('Relation'))->filter();


        $grid->actions(function ($actions) {
            $actions->disableView();
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(UserFamily::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('title', __('Title'));
        $show->field('relation', __('Relation'));
        $show->field('image', __('Image'));
        $show->field('user_id', __('User id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));


        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new UserFamily());

        $form->select('user_id', __('Patient'))->options(User::all()->pluck('name','id'))->required();
        $form->image('image', __('Image'));
        $form->text('name', __('Name'))->required();
        $form->text('relation', __('Relation'));
        $form->text('title', __('Title'));

        return $form;
    }
}
