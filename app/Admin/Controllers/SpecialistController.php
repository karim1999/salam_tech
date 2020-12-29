<?php

namespace App\Admin\Controllers;

use App\Models\Specialist;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class SpecialistController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Specialist';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Specialist());

        $grid->column('id', __('Id'));
        $grid->column('name_ar', __('Name ar'));
        $grid->column('name_en', __('Name en'));
        $grid->column('image', __('Image'));
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
        $show = new Show(Specialist::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name_ar', __('Name ar'));
        $show->field('name_en', __('Name en'));
        $show->field('image', __('Image'));
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
        $form = new Form(new Specialist());

        $form->text('name_ar', __('Name ar'));
        $form->text('name_en', __('Name en'));
        $form->image('image', __('Image'));

        return $form;
    }
}
