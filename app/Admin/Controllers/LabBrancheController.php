<?php

namespace App\Admin\Controllers;

use App\Models\Area;
use App\Models\City;
use App\Models\Clinic;
use App\Models\Lab;
use App\Models\LabBranche;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class LabBrancheController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'LabBranche';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new LabBranche());

        $grid->column('id', __('Id'))->filter();
        $grid->column('phone', __('Phone'))->filter();
        $grid->column('floor', __('Floor'))->filter();
        $grid->column('block', __('Block'))->filter();
        $grid->column('address', __('Address'))->filter();
        $grid->column('work_time_from', __('Work time from'))->filter();
        $grid->column('work_time_to', __('Work time to'))->filter();
        $grid->column('lab_id', __('Lab'))->display(function ($id) {
            return "<a href='".route('admin.labs.labs.edit', $id)."'>Lab</a>";
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
        $show = new Show(LabBranche::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('floor', __('Floor'));
        $show->field('block', __('Block'));
        $show->field('address', __('Address'));
        $show->field('latitude', __('Latitude'));
        $show->field('longitude', __('Longitude'));
        $show->field('phone', __('Phone'));
        $show->field('work_days', __('Work days'));
        $show->field('work_time_from', __('Work time from'));
        $show->field('work_time_to', __('Work time to'));
        $show->field('lab_id', __('Lab id'));
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
        $form = new Form(new LabBranche());

        $form->select('lab_id', __('lab id'))->options(Lab::all()->pluck('name_en','id'))->required();
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

        return $form;
    }
}
