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
        $grid->column('image', __('Image'))->image();
        $grid->column('name', __('Name'));
        $grid->column('id_employee', __('Id employee'));
        $grid->column('position', __('Position'));
        $grid->column('net_salary', __('Net salary'));
        $grid->column('gross_salary', __('Gross salary'));
//        $grid->column('docs_checklist', __('Docs checklist'));
//        $grid->column('gender', __('Gender'));
        $grid->column('clinic_id', __('Clinic'))->display(function ($id) {
            return "<a href='".route('admin.clinics.clinics.edit', $id)."'>Clinic</a>";
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

        $form->tab('Basic info', function ($form) {

            $form->select('clinic_id', __('Clinic id'))->options(Clinic::all()->pluck('name','id'))->required();
            $form->text('name', __('Name'));
            $form->image('image', __('Image'));
            $form->file('id_employee', __('Employee ID'));
            $form->text('position', __('Position'));
            $form->decimal('net_salary', __('Net salary'));
            $form->decimal('gross_salary', __('Gross salary'));
            $form->list('docs_checklist', __('Docs checklist'));
            $form->radio('gender', 'Gender')->options(['1' => 'Male', '2'=> 'Female'])->default('1');

        })->tab('Documents', function ($form) {

            $form->hasMany('documents', 'Documents', function (Form\NestedForm $form) {
                $form->file('document', __('Document'));
            });

        })->tab('Attendance', function ($form) {
            $form->hasMany('attendance', 'Attendances', function (Form\NestedForm $form) {
                $form->date('date', __('Date'))->default(date('Y-m-d'));
                $form->radio('status', 'Status')->options(['1' => 'Show Up', '2'=> 'Late', '3'=> 'Did not show up'])->default('1');
                $form->time('delay_time', __('Delay Time'))->default(date('H:i:s'));
                $form->switch('paid_leave', 'Paid Leave')->default('0');
                $form->decimal('deduction', __('Deduction'))->default(0);
            });

        });


        return $form;
    }
}
