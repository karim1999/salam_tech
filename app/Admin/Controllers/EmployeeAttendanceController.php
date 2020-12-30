<?php

namespace App\Admin\Controllers;

use App\Models\ClinicEmployee;
use App\Models\EmployeeAttendance;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class EmployeeAttendanceController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'EmployeeAttendance';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new EmployeeAttendance());

        $grid->column('id', __('Id'))->filter();
        $grid->column('date', __('Date'))->filter();
        $grid->column('status', __('Status'))->using(['1' => 'Show Up', '2'=> 'Late', '3'=> 'Did not show up'])->default('1')->filter();
        $grid->column('delay_time', __('Delay time'))->filter();
        $grid->column('deduction', __('Deduction'))->filter();
        $grid->column('paid_leave', __('Paid leave'))->bool()->filter();
        $grid->column('employee_id', __('Employee'))->display(function ($id) {
            return "<a href='".route('admin.clinics.employees.employees.edit', $id)."'>Employee</a>";
        })->filter();
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
        $show = new Show(EmployeeAttendance::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('date', __('Date'));
        $show->field('status', __('Status'));
        $show->field('delay_time', __('Delay time'));
        $show->field('deduction', __('Deduction'));
        $show->field('paid_leave', __('Paid leave'));
        $show->field('employee_id', __('Employee id'));
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
        $form = new Form(new EmployeeAttendance());

        $form->select('employee_id', __('Employee'))->options(ClinicEmployee::all()->pluck('name','id'))->required();

        $form->date('date', __('Date'))->default(date('Y-m-d'));
        $form->radio('status', 'Status')->options(['1' => 'Show Up', '2'=> 'Late', '3'=> 'Did not show up'])->default('1');
        $form->time('delay_time', __('Delay Time'))->default(date('H:i:s'));
        $form->switch('paid_leave', 'Paid Leave')->default('0');
        $form->decimal('deduction', __('Deduction'))->default(0);

        return $form;
    }
}
