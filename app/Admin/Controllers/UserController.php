<?php

namespace App\Admin\Controllers;

use App\Models\Area;
use App\Models\City;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class UserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'User';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User());

        $grid->column('id', __('Id'))->filter();
        $grid->column('image', __('Image'))->image();
        $grid->column('name', __('Name'))->filter();
        $grid->column('email', __('Email'))->filter();
        $grid->column('phone', __('Phone'))->filter();
        $grid->column('birth_date', __('Birth date'))->filter();
        $grid->column('gender', __('Gender'))->using(['1' => 'female', '2' => 'male'])->filter();

        $grid->column('profile_finish', __('Profile finish'))->bool()->filter();
        $grid->column('status', __('Status'))->bool()->filter();
        $grid->column('created_at', __('Created at'))->filter();

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
        $show = new Show(User::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('code', __('Code'));
        $show->field('email', __('Email'));
        $show->field('phone', __('Phone'));
        $show->field('password', __('Password'));
        $show->field('image', __('Image'));
        $show->field('identification_card', __('Identification card'));
        $show->field('insurance_card', __('Insurance card'));
        $show->field('birth_date', __('Birth date'));
        $show->field('gender', __('Gender'));
        $show->field('floor_no', __('Floor no'));
        $show->field('block_no', __('Block no'));
        $show->field('address', __('Address'));
        $show->field('latitude', __('Latitude'));
        $show->field('longitude', __('Longitude'));
        $show->field('rate', __('Rate'));
        $show->field('points', __('Points'));
        $show->field('profile_finish', __('Profile finish'));
        $show->field('status', __('Status'));
        $show->field('city_id', __('City id'));
        $show->field('area_id', __('Area id'));
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
        $form = new Form(new User());

        $form->tab('Basic info', function ($form) {

            $form->image('image', __('Image'));
            $form->text('code', __('Code'));
            $form->text('name', __('Name'))->required();
            $form->file('identification_card', __('Identification card'));
            $form->file('insurance_card', __('Insurance card'));
            $form->email('email', __('Email'))->required()
                ->creationRules(['required', "unique:users,email"])
                ->updateRules(['required', "unique:users,email,{{id}}"]);
            $form->mobile('phone', __('Phone'))->required()
                ->creationRules(['required', "unique:users,phone"])
                ->updateRules(['required', "unique:users,phone,{{id}}"]);

            $form->password('password', __('Password'))->creationRules('required|min:6|confirmed')
                ->updateRules('sometimes|nullable|min:6|confirmed');
            $form->password('password_confirmation', __('Password Conformation'))->creationRules('required|min:6')->updateRules('sometimes|nullable|min:6');
            $form->date('birth_date', __('Birth date'))->default(date('Y-m-d'));
            $form->radio('gender', 'Gender')->options(['1' => 'Male', '2'=> 'Female'])->default('1');
//            $form->decimal('rate', __('Rate'))->default(0);

        })->tab('Address', function ($form) {

            $form->hasMany('addresses', 'Addresses', function (Form\NestedForm $form) {
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
            });

        })->tab('Health', function ($form) {

            $form->number('health.height', __('Height'))->default(0);
            $form->number('health.weight', __('Weight'))->default(0);
            $form->text('health.blood_pressure', __('Blood pressure'));
            $form->text('health.sugar_level', __('Sugar level'));
            $form->text('health.blood_type', __('Blood type'));
            $form->decimal('health.muscle_mass', __('Muscle mass'))->default(0);
            $form->text('health.metabolism', __('Metabolism'));
            $form->tags('health.genetic_history', __('Genetic history'));
            $form->tags('health.illness_history', __('Illness history'));
            $form->tags('health.allergies', __('Allergies'));
            $form->tags('health.prescription', __('Prescription'));
            $form->tags('health.operations', __('Operations'));

        })->tab('Families', function ($form) {

            $form->hasMany('families', 'Families', function (Form\NestedForm $form) {
                $form->image('image', __('Image'));
                $form->text('name', __('Name'))->required();
                $form->text('relation', __('Relation'));
                $form->text('title', __('Title'));
            });

        })->tab('Favorites', function ($form) {

            $form->multipleSelect('favorites','Favorites')->options(Doctor::all()->pluck('name','id'));

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
