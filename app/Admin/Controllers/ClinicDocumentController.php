<?php

namespace App\Admin\Controllers;

use App\Models\ClinicDocument;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ClinicDocumentController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'ClinicDocument';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ClinicDocument());

        $grid->column('id', __('Id'));
        $grid->column('registration', __('Registration'));
        $grid->column('license', __('License'));
        $grid->column('tax_id', __('Tax id'));
        $grid->column('clinic_id', __('Clinic id'));
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
        $show = new Show(ClinicDocument::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('registration', __('Registration'));
        $show->field('license', __('License'));
        $show->field('tax_id', __('Tax id'));
        $show->field('clinic_id', __('Clinic id'));
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
        $form = new Form(new ClinicDocument());

        $form->text('registration', __('Registration'));
        $form->text('license', __('License'));
        $form->text('tax_id', __('Tax id'));
        $form->number('clinic_id', __('Clinic id'));

        return $form;
    }
}
