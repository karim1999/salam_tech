<?php

namespace App\Admin\Controllers;

use App\Models\ClinicSpecialist;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ClinicSpecialistController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'ClinicSpecialist';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ClinicSpecialist());

        $grid->column('id', __('Id'));
        $grid->column('clinic_id', __('Clinic id'));
        $grid->column('specialist_id', __('Specialist id'));
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
        $show = new Show(ClinicSpecialist::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('clinic_id', __('Clinic id'));
        $show->field('specialist_id', __('Specialist id'));
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
        $form = new Form(new ClinicSpecialist());

        $form->number('clinic_id', __('Clinic id'));
        $form->number('specialist_id', __('Specialist id'));

        return $form;
    }
}
