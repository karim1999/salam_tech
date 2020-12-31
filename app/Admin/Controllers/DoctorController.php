<?php

namespace App\Admin\Controllers;

use App\Models\Area;
use App\Models\City;
use App\Models\Clinic;
use App\Models\ClinicBranche;
use App\Models\Doctor;
use App\Models\Specialist;
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

        $grid->column('id', __('Id'))->filter();
        $grid->column('image', __('Image'))->image();
        $grid->column('name', __('Name'))->filter();
        $grid->column('email', __('Email'))->filter();
        $grid->column('phone', __('Phone'))->filter();
        $grid->column('birth_date', __('Birth date'))->filter();
        $grid->column('gender', __('Gender'))->using(['1' => 'female', '2' => 'male'])->filter();

        $grid->column('views', __('Views'))->filter();

        $grid->column('clinic_id', __('Clinic'))->display(function ($id) {
            if($id){
                return "<a href='".route('admin.clinics.clinics.edit', $id)."'>Clinic</a>";
            }else{
                return "";
            }
        })->filter();

        $grid->column('clinic_branch_id', __('Branch'))->display(function ($id) {
            if($id){
				return "<a href='".route('admin.clinics.branches.edit', $id)."'>Branch</a>";
            }else{
                return "";
            }
        })->filter();

        $grid->column('specialist_id', __('Specialist'))->display(function ($id) {
            if($id){
				return "<a href='".route('admin.clinics.specialists.edit', $id)."'>Specialist</a>";
            }else{
                return "";
            }
        })->filter();

        $grid->column('profile_finish', __('Profile finish'))->bool()->filter();
        $grid->column('status', __('Status'))->bool()->filter();
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

        $form->tab('Basic info', function ($form) {

            $form->select('clinic_id', __('Clinic'))->options(Clinic::all()->pluck('name','id'))->required();
            $form->select('clinic_branch_id', __('Branch'))->options(ClinicBranche::all()->pluck('address','id'))->required();
            $form->image('image', __('Image'));
            $form->text('name', __('Name'))->required();
            $form->email('email', __('Email'))->required()
                ->creationRules(['required', "unique:doctors,email"])
                ->updateRules(['required', "unique:doctors,email,{{id}}"]);
            $form->mobile('phone', __('Phone'))->required()
                ->creationRules(['required', "unique:doctors,phone"])
                ->updateRules(['required', "unique:doctors,phone,{{id}}"]);

            $form->password('password', __('Password'))->creationRules('required|min:6|confirmed')
                ->updateRules('sometimes|nullable|min:6|confirmed');
            $form->password('password_confirmation', __('Password Conformation'))->creationRules('required|min:6')->updateRules('sometimes|nullable|min:6');
            $form->date('birth_date', __('Birth date'))->default(date('Y-m-d'));
            $form->radio('gender', 'Gender')->options(['1' => 'Male', '2'=> 'Female'])->default('1');

        })->tab('Address', function ($form) {

            $form->select('city_id', __('City'))->options(function ($id) {
                $city = City::find($id);

                if ($city) {
                    return [$city->id => $city->name_en];
                }
            })->ajax('/admin/api/cities')->load('area_id', '/admin/api/areas');

            $form->select('area_id', __('Area'))->options(Area::all()->pluck('name_en','id'));
            $form->text('address', __('Address'));
            $form->number('block_no', __('Block no'))->default(0);
            $form->number('floor_no', __('Floor no'))->default(0);
//            $form->decimal('latitude', __('Latitude'));
//            $form->decimal('longitude', __('Longitude'));

        })->tab('Work Information', function ($form) {

            $form->multipleSelect('work_days', __('Work days'))
                ->options(['Saturday' => 'Saturday', 'Sunday' => 'Sunday', 'Monday' => 'Monday',
                    'Tuesday'=> 'Tuesday', 'Wednesday' => 'Wednesday', 'Thursday' => 'Thursday', 'Friday' => 'Friday']);
            $form->time('work_time_from', __('Work time from'))->default(date('H:i:s'));
            $form->time('work_time_to', __('Work time to'))->default(date('H:i:s'));

            $form->list('services', __('Services'));

        })->tab('Pricing Information', function ($form) {

            $form->decimal('fees', __('Fees'))->default(0);
            $form->number('patient_hour', __('Patient hour'))->default(1);
            $form->switch('home_visit', __('Home visit'));
            $form->decimal('home_visit_fees', __('Home visit fees'))->default(0);
//            $form->decimal('rate', __('Rate'));

        })->tab('Extra Information', function ($form) {

            $form->list('sub_specialist', __('Sub specialist'));
            $form->text('seniority_level', __('Seniority level'));

            $form->select('specialist_id', __('Specialist id'))->options(Specialist::all()->pluck('name_en','id'));


        })->tab('Documents', function ($form) {

            $form->hasMany('documents', 'Documents', function (Form\NestedForm $form) {
                $form->file('link', __('File'));
            });

        })->tab('Certifications', function ($form) {

            $form->hasMany('certifications', 'Certifications', function (Form\NestedForm $form) {
                $form->text('title', __('Title'));
                $form->textarea('body', __('Body'));
            });

        })->tab('Vacations', function ($form) {

            $form->hasMany('vacations', 'Vacations', function (Form\NestedForm $form) {
                $form->date('date', __('Date'));
            });

        })->tab('Settings', function ($form) {

            $form->switch('profile_finish', __('Profile finish'))->default(1);
            $form->switch('status', __('Status'))->default(1);

        });

        $form->submitted(function (Form $form) {
            $form->ignore('password_confirmation');
        });


        return $form;
    }
}
