<?php

namespace App\Admin\Controllers;

use App\Models\Consultation;
use App\Models\Doctor;
use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ConsultationController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Consultation';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Consultation());

        $grid->column('id', __('Id'))->filter();
        $grid->column('user_id', __('Patient'))->display(function ($id) {
            if($id){
                return "<a href='".route('admin.patients.patients.edit', $id)."'>Patient</a>";
            }else{
                return "";
            }
        })->filter();
        $grid->column('doctor_id', __('Doctor'))->display(function ($id) {
            if($id){
                return "<a href='".route('admin.doctors.doctors.edit', $id)."'>Doctor</a>";
            }else{
                return "";
            }
        })->filter();
        $grid->column('created_at', __('Created at'))->filter();
        $grid->column('updated_at', __('Updated at'))->filter();


        $grid->actions(function ($actions) {
//            $actions->disableEdit();
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
        $show = new Show(Consultation::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('doctor_id', __('Doctor id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        $show->messages('Messages', function ($messages) {

            $messages->id('ID')->sortable();
            $messages->msg('Message');
            $messages->created_at();

        });

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Consultation());

        $form->select('user_id', __('Patient'))->options(User::all()->pluck('name','id'))->required();
        $form->select('doctor_id', __('Doctor'))->options(Doctor::all()->pluck('name','id'))->required();

        return $form;
    }
}
