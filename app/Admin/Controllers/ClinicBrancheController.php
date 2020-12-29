<?php

namespace App\Admin\Controllers;

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
        $grid->column('phone', __('Phone'));
        $grid->column('floor', __('Floor'));
        $grid->column('block', __('Block'));
        $grid->column('address', __('Address'));
        $grid->column('latitude', __('Latitude'));
        $grid->column('longitude', __('Longitude'));
        $grid->column('work_days', __('Work days'));
        $grid->column('work_time_from', __('Work time from'));
        $grid->column('work_time_to', __('Work time to'));
        $grid->column('clinic_id', __('Clinic id'));
        $grid->column('area_id', __('Area id'));
        $grid->column('city_id', __('City id'));
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

        $form->mobile('phone', __('Phone'));
        $form->number('floor', __('Floor'));
        $form->number('block', __('Block'));
        $form->text('address', __('Address'));
        $form->decimal('latitude', __('Latitude'));
        $form->decimal('longitude', __('Longitude'));
        $form->textarea('work_days', __('Work days'));
        $form->time('work_time_from', __('Work time from'))->default(date('H:i:s'));
        $form->time('work_time_to', __('Work time to'))->default(date('H:i:s'));
        $form->number('clinic_id', __('Clinic id'));
        $form->number('area_id', __('Area id'));
        $form->number('city_id', __('City id'));

        return $form;
    }
}
