<?php

namespace App\Admin\Controllers;

use App\Models\UserHealth;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class UserHealthController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'UserHealth';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new UserHealth());

        $grid->column('id', __('Id'))->filter();
        $grid->column('height', __('Height'))->filter();
        $grid->column('weight', __('Weight'))->filter();
        $grid->column('blood_pressure', __('Blood pressure'))->filter();
        $grid->column('sugar_level', __('Sugar level'))->filter();
        $grid->column('blood_type', __('Blood type'))->filter();
        $grid->column('muscle_mass', __('Muscle mass'))->filter();
        $grid->column('metabolism', __('Metabolism'))->filter();
        $grid->column('genetic_history', __('Genetic history'))->filter();
        $grid->column('illness_history', __('Illness history'))->filter();
        $grid->column('allergies', __('Allergies'))->filter();
        $grid->column('prescription', __('Prescription'))->filter();
        $grid->column('operations', __('Operations'))->filter();
        $grid->column('user_id', __('User id'))->filter();
        $grid->column('created_at', __('Created at'))->filter();
        $grid->column('updated_at', __('Updated at'))->filter();

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
        $show = new Show(UserHealth::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('height', __('Height'));
        $show->field('weight', __('Weight'));
        $show->field('blood_pressure', __('Blood pressure'));
        $show->field('sugar_level', __('Sugar level'));
        $show->field('blood_type', __('Blood type'));
        $show->field('muscle_mass', __('Muscle mass'));
        $show->field('metabolism', __('Metabolism'));
        $show->field('genetic_history', __('Genetic history'));
        $show->field('illness_history', __('Illness history'));
        $show->field('allergies', __('Allergies'));
        $show->field('prescription', __('Prescription'));
        $show->field('operations', __('Operations'));
        $show->field('user_id', __('User id'));
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
        $form = new Form(new UserHealth());

        $form->number('height', __('Height'));
        $form->number('weight', __('Weight'));
        $form->text('blood_pressure', __('Blood pressure'));
        $form->text('sugar_level', __('Sugar level'));
        $form->text('blood_type', __('Blood type'));
        $form->decimal('muscle_mass', __('Muscle mass'))->default(0);
        $form->text('metabolism', __('Metabolism'));
        $form->textarea('genetic_history', __('Genetic history'));
        $form->textarea('illness_history', __('Illness history'));
        $form->textarea('allergies', __('Allergies'));
        $form->textarea('prescription', __('Prescription'));
        $form->textarea('operations', __('Operations'));
        $form->number('user_id', __('User id'));

        return $form;
    }
}
