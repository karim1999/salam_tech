<?php

namespace App\Admin\Controllers;

use App\Models\Doctor;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class DoctorController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Doctor';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Doctor());

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('email', __('Email'));
        $grid->column('phone', __('Phone'));
        $grid->column('password', __('Password'));
        $grid->column('image', __('Image'));
        $grid->column('birth_date', __('Birth date'));
        $grid->column('gender', __('Gender'));
        $grid->column('sub_specialist', __('Sub specialist'));
        $grid->column('seniority_level', __('Seniority level'));
        $grid->column('floor_no', __('Floor no'));
        $grid->column('block_no', __('Block no'));
        $grid->column('address', __('Address'));
        $grid->column('latitude', __('Latitude'));
        $grid->column('longitude', __('Longitude'));
        $grid->column('work_days', __('Work days'));
        $grid->column('work_time_from', __('Work time from'));
        $grid->column('work_time_to', __('Work time to'));
        $grid->column('fees', __('Fees'));
        $grid->column('patient_hour', __('Patient hour'));
        $grid->column('home_visit', __('Home visit'));
        $grid->column('home_visit_fees', __('Home visit fees'));
        $grid->column('services', __('Services'));
        $grid->column('rate', __('Rate'));
        $grid->column('views', __('Views'));
        $grid->column('profile_finish', __('Profile finish'));
        $grid->column('status', __('Status'));
        $grid->column('specialist_id', __('Specialist id'));
        $grid->column('city_id', __('City id'));
        $grid->column('area_id', __('Area id'));
        $grid->column('clinic_id', __('Clinic id'));
        $grid->column('clinic_branch_id', __('Clinic branch id'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(Doctor::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('email', __('Email'));
        $show->field('phone', __('Phone'));
        $show->field('password', __('Password'));
        $show->field('image', __('Image'));
        $show->field('birth_date', __('Birth date'));
        $show->field('gender', __('Gender'));
        $show->field('sub_specialist', __('Sub specialist'));
        $show->field('seniority_level', __('Seniority level'));
        $show->field('floor_no', __('Floor no'));
        $show->field('block_no', __('Block no'));
        $show->field('address', __('Address'));
        $show->field('latitude', __('Latitude'));
        $show->field('longitude', __('Longitude'));
        $show->field('work_days', __('Work days'));
        $show->field('work_time_from', __('Work time from'));
        $show->field('work_time_to', __('Work time to'));
        $show->field('fees', __('Fees'));
        $show->field('patient_hour', __('Patient hour'));
        $show->field('home_visit', __('Home visit'));
        $show->field('home_visit_fees', __('Home visit fees'));
        $show->field('services', __('Services'));
        $show->field('rate', __('Rate'));
        $show->field('views', __('Views'));
        $show->field('profile_finish', __('Profile finish'));
        $show->field('status', __('Status'));
        $show->field('specialist_id', __('Specialist id'));
        $show->field('city_id', __('City id'));
        $show->field('area_id', __('Area id'));
        $show->field('clinic_id', __('Clinic id'));
        $show->field('clinic_branch_id', __('Clinic branch id'));
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
        $form = new Form(new Doctor());

        $form->text('name', __('Name'));
        $form->email('email', __('Email'));
        $form->mobile('phone', __('Phone'));
        $form->password('password', __('Password'));
        $form->image('image', __('Image'));
        $form->date('birth_date', __('Birth date'))->default(date('Y-m-d'));
        $form->switch('gender', __('Gender'));
        $form->text('sub_specialist', __('Sub specialist'));
        $form->text('seniority_level', __('Seniority level'));
        $form->number('floor_no', __('Floor no'));
        $form->number('block_no', __('Block no'));
        $form->text('address', __('Address'));
        $form->decimal('latitude', __('Latitude'));
        $form->decimal('longitude', __('Longitude'));
        $form->textarea('work_days', __('Work days'));
        $form->time('work_time_from', __('Work time from'))->default(date('H:i:s'));
        $form->time('work_time_to', __('Work time to'))->default(date('H:i:s'));
        $form->decimal('fees', __('Fees'));
        $form->number('patient_hour', __('Patient hour'))->default(1);
        $form->switch('home_visit', __('Home visit'));
        $form->decimal('home_visit_fees', __('Home visit fees'));
        $form->textarea('services', __('Services'));
        $form->decimal('rate', __('Rate'));
        $form->number('views', __('Views'));
        $form->switch('profile_finish', __('Profile finish'));
        $form->switch('status', __('Status'))->default(1);
        $form->number('specialist_id', __('Specialist id'));
        $form->number('city_id', __('City id'));
        $form->number('area_id', __('Area id'));
        $form->number('clinic_id', __('Clinic id'));
        $form->number('clinic_branch_id', __('Clinic branch id'));

        return $form;
    }
}
