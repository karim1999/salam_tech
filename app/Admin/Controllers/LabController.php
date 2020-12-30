<?php

namespace App\Admin\Controllers;

use App\Models\Area;
use App\Models\City;
use App\Models\Lab;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class LabController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Lab';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Lab());

        $grid->column('id', __('Id'))->filter();
        $grid->column('image', __('Image'))->image();
        $grid->column('name_ar', __('Name ar'))->filter();
        $grid->column('name_en', __('Name en'))->filter();
        $grid->column('delivery', __('Delivery'))->bool()->filter();
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
        $show = new Show(Lab::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name_ar', __('Name ar'));
        $show->field('name_en', __('Name en'));
        $show->field('image', __('Image'));
        $show->field('delivery', __('Delivery'));
        $show->field('status', __('Status'));
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
        $form = new Form(new Lab());

        $form->tab('Basic info', function ($form) {
            $form->text('name_ar', __('Name ar'))->required();
            $form->text('name_en', __('Name en'))->required();
            $form->image('image', __('Image'));
            $form->switch('delivery', __('Delivery'))->default(1);
            $form->switch('status', __('Status'))->default(1);

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
        });

        return $form;
    }
}
