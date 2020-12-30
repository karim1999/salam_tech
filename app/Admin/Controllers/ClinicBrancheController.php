<?php

namespace App\Admin\Controllers;

use App\Models\Area;
use App\Models\City;
use App\Models\Clinic;
use App\Models\ClinicBranche;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ClinicBrancheController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'ClinicBranche';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ClinicBranche());

        $grid->column('id', __('Id'));
        $grid->column('phone', __('Phone'))->filter();
        $grid->column('floor', __('Floor'))->filter();
        $grid->column('block', __('Block'))->filter();
        $grid->column('address', __('Address'))->filter();
//        $grid->column('latitude', __('Latitude'))->filter();
//        $grid->column('longitude', __('Longitude'))->filter();
//        $grid->column('work_days', __('Work days'))->filter();
        $grid->column('work_time_from', __('Work time from'))->filter();
        $grid->column('work_time_to', __('Work time to'))->filter();
        $grid->column('clinic_id', __('Clinic'))->display(function ($id) {
            return "<a href='".route('admin.clinics.clinics.edit', $id)."'>Clinic</a>";
        })->filter();
//        $grid->column('area_id', __('Area id'));
//        $grid->column('city_id', __('City id'));
        $grid->column('created_at', __('Created at'))->filter();
//        $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(ClinicBranche::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('phone', __('Phone'));
        $show->field('floor', __('Floor'));
        $show->field('block', __('Block'));
        $show->field('address', __('Address'));
        $show->field('latitude', __('Latitude'));
        $show->field('longitude', __('Longitude'));
        $show->field('work_days', __('Work days'));
        $show->field('work_time_from', __('Work time from'));
        $show->field('work_time_to', __('Work time to'));
        $show->field('clinic_id', __('Clinic id'));
        $show->field('area_id', __('Area id'));
        $show->field('city_id', __('City id'));
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
        $form = new Form(new ClinicBranche());

        $form->tab('Basic info', function ($form) {

            $form->select('clinic_id', __('Clinic id'))->options(Clinic::all()->pluck('name','id'))->required();
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

        })->tab('Images', function ($form) {

            $form->hasMany('images', 'Images', function (Form\NestedForm $form) {
                $form->image('image', __('Image'));
            });

        });

        return $form;
    }
}
