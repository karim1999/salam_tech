<?php

namespace App\Admin\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Rate;
use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class RateController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Rate';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Rate());

        $grid->column('id', __('Id'))->filter();
        $grid->column('rate', __('Rate'))->filter();
        $grid->column('points', __('Points'))->filter();
        $grid->column('status', __('Status'))->filter();
        $grid->column('sender', __('Sender'))->using(['1' => 'Doctor', '2'=> 'User'])->filter();
        $grid->column('user_id', __('Patient'))->display(function ($id) {
            return "<a href='".route('admin.patients.patients.edit', $id)."'>Patient</a>";
        })->filter();
        $grid->column('doctor_id', __('Doctor'))->display(function ($id) {
            return "<a href='".route('admin.doctors.doctors.edit', $id)."'>Doctor</a>";
        })->filter();
//        $grid->column('appointment_id', __('Appointment'))->display(function ($id) {
//            return "<a href='".route('admin.doctors.appointments.edit', $id)."'>Appointment</a>";
//        })->filter();
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
        $show = new Show(Rate::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('rate', __('Rate'));
        $show->field('points', __('Points'));
        $show->field('status', __('Status'));
        $show->field('sender', __('Sender'));
        $show->field('user_id', __('User id'));
        $show->field('doctor_id', __('Doctor id'));
        $show->field('appointment_id', __('Appointment id'));
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
        $form = new Form(new Rate());

        $form->number('rate', __('Rate'))->default(0);
        $form->number('points', __('Points'))->default(0);
        $form->radio('status', 'Status')->options(['good' => 'good', 'bad'=> 'bad'])->default('good');
        $form->radio('sender', 'Sender')->options(['1' => 'Doctor', '2'=> 'User'])->default('1');
        $form->select('user_id', __('Patient'))->options(User::all()->pluck('name','id'))->required();
        $form->select('doctor_id', __('Doctor'))->options(Doctor::all()->pluck('name','id'))->required();

        return $form;
    }
}
