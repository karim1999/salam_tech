<?php

namespace App\Admin\Controllers;

use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class UserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'User';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User());

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('code', __('Code'));
        $grid->column('email', __('Email'));
        $grid->column('phone', __('Phone'));
        $grid->column('password', __('Password'));
        $grid->column('image', __('Image'));
        $grid->column('identification_card', __('Identification card'));
        $grid->column('insurance_card', __('Insurance card'));
        $grid->column('birth_date', __('Birth date'));
        $grid->column('gender', __('Gender'));
        $grid->column('floor_no', __('Floor no'));
        $grid->column('block_no', __('Block no'));
        $grid->column('address', __('Address'));
        $grid->column('latitude', __('Latitude'));
        $grid->column('longitude', __('Longitude'));
        $grid->column('rate', __('Rate'));
        $grid->column('points', __('Points'));
        $grid->column('profile_finish', __('Profile finish'));
        $grid->column('status', __('Status'));
        $grid->column('city_id', __('City id'));
        $grid->column('area_id', __('Area id'));
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
        $show = new Show(User::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('code', __('Code'));
        $show->field('email', __('Email'));
        $show->field('phone', __('Phone'));
        $show->field('password', __('Password'));
        $show->field('image', __('Image'));
        $show->field('identification_card', __('Identification card'));
        $show->field('insurance_card', __('Insurance card'));
        $show->field('birth_date', __('Birth date'));
        $show->field('gender', __('Gender'));
        $show->field('floor_no', __('Floor no'));
        $show->field('block_no', __('Block no'));
        $show->field('address', __('Address'));
        $show->field('latitude', __('Latitude'));
        $show->field('longitude', __('Longitude'));
        $show->field('rate', __('Rate'));
        $show->field('points', __('Points'));
        $show->field('profile_finish', __('Profile finish'));
        $show->field('status', __('Status'));
        $show->field('city_id', __('City id'));
        $show->field('area_id', __('Area id'));
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
        $form = new Form(new User());

        $form->text('name', __('Name'));
        $form->text('code', __('Code'));
        $form->email('email', __('Email'));
        $form->mobile('phone', __('Phone'));
        $form->password('password', __('Password'));
        $form->image('image', __('Image'));
        $form->text('identification_card', __('Identification card'));
        $form->text('insurance_card', __('Insurance card'));
        $form->date('birth_date', __('Birth date'))->default(date('Y-m-d'));
        $form->switch('gender', __('Gender'));
        $form->number('floor_no', __('Floor no'));
        $form->number('block_no', __('Block no'));
        $form->text('address', __('Address'));
        $form->decimal('latitude', __('Latitude'));
        $form->decimal('longitude', __('Longitude'));
        $form->decimal('rate', __('Rate'));
        $form->number('points', __('Points'));
        $form->switch('profile_finish', __('Profile finish'));
        $form->switch('status', __('Status'))->default(1);
        $form->number('city_id', __('City id'));
        $form->number('area_id', __('Area id'));

        return $form;
    }
}
