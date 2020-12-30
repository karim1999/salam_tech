<?php

namespace App\Admin\Controllers;

use App\Models\Area;
use App\Models\City;
use App\Models\Clinic;
use App\Models\Specialist;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ClinicController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Clinic';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Clinic());

        $grid->column('id', __('Id'))->filter();
        $grid->column('image', __('Image'))->image();
        $grid->column('name', __('Name'))->filter();
        $grid->column('email', __('Email'))->filter();
        $grid->column('phone', __('Phone'))->filter();
        $grid->column('address', __('Address'))->filter();
        $grid->column('website_url', __('Website url'))->link()->filter();
        $grid->column('profile_finish', __('Profile finish'))->bool()->filter();
        $grid->column('status', __('Status'))->bool()->filter();
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
        $show = new Show(Clinic::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('email', __('Email'));
        $show->field('phone', __('Phone'));
        $show->field('password', __('Password'));
        $show->field('image', __('Image'));
        $show->field('branches_no', __('Branches no'));
        $show->field('floor_no', __('Floor no'));
        $show->field('block_no', __('Block no'));
        $show->field('address', __('Address'));
        $show->field('latitude', __('Latitude'));
        $show->field('longitude', __('Longitude'));
        $show->field('work_days', __('Work days'));
        $show->field('work_time_from', __('Work time from'));
        $show->field('work_time_to', __('Work time to'));
        $show->field('services', __('Services'));
        $show->field('amenities', __('Amenities'));
        $show->field('website_url', __('Website url'));
        $show->field('type', __('Type'));
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

        $form = new Form(new Clinic());

        $form->tab('Basic info', function ($form) {

            $form->image('image', __('Image'));
            $form->text('name', __('Name'))->required();
            $form->email('email', __('Email'))->required()
                ->creationRules(['required', "unique:clinics,email"])
                ->updateRules(['required', "unique:clinics,email,{{id}}"]);
            $form->mobile('phone', __('Phone'))->required()
                ->creationRules(['required', "unique:clinics,phone"])
                ->updateRules(['required', "unique:clinics,phone,{{id}}"]);

            $form->password('password', __('Password'))->creationRules('required|min:6|confirmed')
                ->updateRules('sometimes|nullable|min:6|confirmed');
            $form->password('password_confirmation', __('Password Conformation'))->creationRules('required|min:6')->updateRules('sometimes|nullable|min:6');
            $form->url('website_url', __('Website url'));

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
            $form->list('amenities', __('Amenities'));


        })->tab('Images', function ($form) {

            $form->hasMany('images', 'Images', function (Form\NestedForm $form) {
                $form->image('image', __('Image'));
            });

        })->tab('Documents', function ($form) {

            $form->hasMany('documents', 'Documents', function (Form\NestedForm $form) {
                $form->file('tax_id', __('Tax ID'));
                $form->file('registration', __('Registration'));
                $form->file('license', __('License'));
            });

        })->tab('Branches', function ($form) {

            $form->hasMany('branches', 'Branches', function (Form\NestedForm $form) {
                $form->mobile('phone', __('Phone'))->required();
                $form->select('city_id', __('City'))->options(function ($id) {
                    $city = City::find($id);

                    if ($city) {
                        return [$city->id => $city->name_en];
                    }
                })->ajax('/admin/api/cities')->load('area_id', '/admin/api/areas');;

                $form->select('area_id', __('Area'))->options(Area::all()->pluck('name_en','id'));

                $form->multipleSelect('work_days', __('Work days'))
                    ->options(['Saturday' => 'Saturday', 'Sunday' => 'Sunday', 'Monday' => 'Monday',
                        'Tuesday'=> 'Tuesday', 'Wednesday' => 'Wednesday', 'Thursday' => 'Thursday', 'Friday' => 'Friday']);
                $form->time('work_time_from', __('Work time from'))->default(date('H:i:s'));
                $form->time('work_time_to', __('Work time to'))->default(date('H:i:s'));

                $form->text('address', __('Address'));
                $form->number('block', __('Block no'))->default(0);
                $form->number('floor', __('Floor no'))->default(0);
            });

        })->tab('Specialists', function ($form) {

            $form->multipleSelect('specialists','Specialists')->options(Specialist::all()->pluck('name_en','id'));

        })->tab('Employees', function ($form) {

            $form->hasMany('employees', 'employees', function (Form\NestedForm $form) {
                $form->text('name', __('Name'));
                $form->image('image', __('Image'));
                $form->file('id_employee', __('Employee ID'));
                $form->text('position', __('Position'));
                $form->decimal('net_salary', __('Net salary'))->default(0);
                $form->decimal('gross_salary', __('Gross salary'))->default(0);
//                $form->list('docs_checklist', __('Docs checklist'));
                $form->radio('gender', 'Gender')->options(['1' => 'Male', '2'=> 'Female'])->default('1');
            });

        })->tab('Products', function ($form) {

            $form->hasMany('products', 'Products', function (Form\NestedForm $form) {
                $form->text('name', __('Name'));
                $form->image('image', __('Image'));
                $form->text('id_product', __('Product ID'));
                $form->text('unit_measure', __('Unit measure'));
                $form->text('supplier_name', __('Supplier name'));
                $form->text('id_supplier', __('Id supplier'));
                $form->number('quantity', __('Quantity'));
                $form->number('min_stock_quantity', __('Min stock quantity'));
                $form->date('expire_date', __('Expire date'))->default(date('Y-m-d'));
                $form->date('min_stock_expire_date', __('Min stock expire date'))->default(date('Y-m-d'));
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
