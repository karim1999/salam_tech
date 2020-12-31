<?php

namespace App\Admin\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\User;
use App\Models\UserAddress;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class AppointmentController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Appointment';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Appointment());

        $grid->column('id', __('Id'));
        $grid->column('patient_name', __('Patient name'))->filter();
        $grid->column('patient_phone', __('Patient phone'))->filter();
        $grid->column('date', __('Date'))->filter();
        $grid->column('time', __('Time'))->filter();
        $grid->column('fees', __('Fees'))->filter();
        $grid->column('type', __('Type'))->using(['1' => 'Appointment', '2' => 'Visit', '3' => 'From clinic'])->filter();
        $grid->column('user_rated', __('User rated'))->bool()->filter();
        $grid->column('doctor_rated', __('Doctor rated'))->bool()->filter();
        $grid->column('user_canceled', __('User canceled'))->bool()->filter();
        $grid->column('doctor_canceled', __('Doctor canceled'))->bool()->filter();
        $grid->column('user_id', __('Patient'))->display(function ($id) {
            if($id){
                return "<a href='".route('admin.patients.patients.edit', $id)."'>Patient</a>";
            }else{
                return "";
            }
        })->filter();
        $grid->column('doctor_id', __('Doctor'))->display(function ($id) {
            if($id){
                return "<a href='".route('admin.doctors.doctors.edit', $id)."'>Doctor</a>";
            }else{
                return "";
            }
        })->filter();
        $grid->column('created_at', __('Created at'))->filter();

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
        $show = new Show(Appointment::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('patient_name', __('Patient name'));
        $show->field('patient_phone', __('Patient phone'));
        $show->field('date', __('Date'));
        $show->field('time', __('Time'));
        $show->field('fees', __('Fees'));
        $show->field('visit_reason', __('Visit reason'));
        $show->field('type', __('Type'));
        $show->field('user_rated', __('User rated'));
        $show->field('doctor_rated', __('Doctor rated'));
        $show->field('user_canceled', __('User canceled'));
        $show->field('doctor_canceled', __('Doctor canceled'));
        $show->field('user_id', __('User id'));
        $show->field('user_family_id', __('User family id'));
        $show->field('user_address_id', __('User address id'));
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
        $form = new Form(new Appointment());

        $form->text('patient_name', __('Patient name'));
        $form->text('patient_phone', __('Patient phone'));
        $form->date('date', __('Date'))->default(date('Y-m-d'));
        $form->time('time', __('Time'))->default(date('H:i:s'));
        $form->number('fees', __('Fees'))->default(0);
        $form->textarea('visit_reason', __('Visit reason'));
        $form->radio('type', 'Type')->options(['1' => 'Appointment', '2' => 'Visit', '3' => 'From clinic'])->default('1');
//        $form->switch('user_rated', __('User rated'));
//        $form->switch('doctor_rated', __('Doctor rated'));
//        $form->switch('user_canceled', __('User canceled'));
//        $form->switch('doctor_canceled', __('Doctor canceled'));
        $form->select('user_id', __('Patient'))->options(User::all()->pluck('name','id'))->required();
        $form->select('user_address_id', __('Address'))->options(UserAddress::all()->pluck('address','id'))->required();
        $form->select('doctor_id', __('Doctor'))->options(Doctor::all()->pluck('name','id'))->required();

        return $form;
    }
}
