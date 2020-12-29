<?php

namespace App\Admin\Controllers;

use App\Models\Clinic;
use App\Models\ClinicEmployee;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ClinicEmployeeController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'ClinicEmployee';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ClinicEmployee());

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('image', __('Image'))->image();
        $grid->column('id_employee', __('Id employee'));
        $grid->column('position', __('Position'));
        $grid->column('net_salary', __('Net salary'));
        $grid->column('gross_salary', __('Gross salary'));
        $grid->column('docs_checklist', __('Docs checklist'));
//        $grid->column('gender', __('Gender'));
        $grid->column('clinic_id', __('Clinic'))->display(function ($id) {
            return "<a href='".route('admin.clinics.edit', $id)."'>Clinic</a>";
        });
        $grid->column('created_at', __('Created at'));

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
        $show = new Show(ClinicEmployee::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('image', __('Image'));
        $show->field('id_employee', __('Id employee'));
        $show->field('position', __('Position'));
        $show->field('net_salary', __('Net salary'));
        $show->field('gross_salary', __('Gross salary'));
        $show->field('docs_checklist', __('Docs checklist'));
        $show->field('gender', __('Gender'));
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
        $form = new Form(new ClinicEmployee());

        $form->select('clinic_id', __('Clinic id'))->options(Clinic::all()->pluck('name','id'))->required();
        $form->text('name', __('Name'));
        $form->image('image', __('Image'));
        $form->text('id_employee', __('Id employee'));
        $form->text('position', __('Position'));
        $form->decimal('net_salary', __('Net salary'));
        $form->decimal('gross_salary', __('Gross salary'));
        $form->list('docs_checklist', __('Docs checklist'));
        $form->switch('gender', __('Gender'))->default(1);

        return $form;
    }
}
