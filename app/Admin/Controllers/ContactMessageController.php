<?php

namespace App\Admin\Controllers;

use App\Models\ContactMessage;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ContactMessageController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'ContactMessage';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ContactMessage());

        $grid->column('id', __('Id'))->filter();
        $grid->column('first_name', __('First name'))->filter();
        $grid->column('last_name', __('Last name'))->filter();
        $grid->column('email', __('Email'))->filter();
        $grid->column('msg', __('Msg'))->filter();
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
        $show = new Show(ContactMessage::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('first_name', __('First name'));
        $show->field('last_name', __('Last name'));
        $show->field('email', __('Email'));
        $show->field('msg', __('Msg'));
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
        $form = new Form(new ContactMessage());

        $form->text('first_name', __('First name'));
        $form->text('last_name', __('Last name'));
        $form->email('email', __('Email'));
        $form->textarea('msg', __('Msg'));

        return $form;
    }
}
