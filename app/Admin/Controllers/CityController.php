<?php

namespace App\Admin\Controllers;

use App\Models\City;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CityController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'City';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new City());

        $grid->column('id', __('Id'));
        $grid->column('name_ar', __('Name ar'))->filter();
        $grid->column('name_en', __('Name en'))->filter();
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(City::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name_ar', __('Name ar'));
        $show->field('name_en', __('Name en'));
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
        $form = new Form(new City());

        $form->tab('Basic info', function ($form) {

            $form->text('name_ar', __('Name ar'))->required();
            $form->text('name_en', __('Name en'))->required();

        })->tab('Areas', function ($form) {

            $form->hasMany('areas', 'Areas', function (Form\NestedForm $form) {
                $form->text('name_ar', __('Name ar'))->required();
                $form->text('name_en', __('Name en'))->required();
            });

        });


        return $form;
    }
}
