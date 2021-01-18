<?php

namespace App\Admin\Controllers;

use App\Models\Doctor;
use App\Models\Emr;
use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class EmrController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Emr';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Emr());

        $grid->column('id', __('Id'))->filter();
        $grid->column('report', __('Report'))->filter();
        $grid->column('user_id', __('Patient'))->display(function ($id) {
            return "<a href='".route('admin.patients.patients.edit', $id)."'>Patient</a>";
        })->filter();
        $grid->column('doctor_id', __('Doctor'))->display(function ($id) {
            return "<a href='".route('admin.doctors.doctors.edit', $id)."'>Doctor</a>";
        })->filter();
        $grid->column('created_at', __('Created at'))->filter();
        $grid->column('updated_at', __('Updated at'))->filter();

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
        $show = new Show(Emr::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('report', __('Report'));
        $show->field('user_id', __('User id'));
        $show->field('doctor_id', __('Doctor id'));
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
        $form = new Form(new Emr());
        $form->tab('Basic info', function ($form) {

            $form->select('user_id', __('Patient'))->options(User::all()->pluck('name','id'))->required();
            $form->select('doctor_id', __('Doctor'))->options(Doctor::all()->pluck('name','id'))->required();
            $form->textarea('report', __('Report'));

        })->tab('Documents', function ($form) {

            $form->hasMany('documents', 'Documents', function (Form\NestedForm $form) {
                $form->text('title', __('Title'))->required();
                $form->file('link', __('File'))->required();
            });

        })->tab('Medecines', function ($form) {

            $form->hasMany('medecines', 'Medecines', function (Form\NestedForm $form) {
                $form->text('title', __('Title'))->required();
                $form->number('duration', __('Duration'))->default(1);
                $form->textarea('body', __('Body'));
            });

        });


        return $form;
    }
}
