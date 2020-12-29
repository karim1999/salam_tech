<?php

namespace App\Admin\Controllers;

use App\Models\Clinic;
use App\Models\ClinicProduct;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ClinicProductController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'ClinicProduct';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ClinicProduct());

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('image', __('Image'))->image();
        $grid->column('id_product', __('Id product'));
        $grid->column('unit_measure', __('Unit measure'));
        $grid->column('supplier_name', __('Supplier name'));
        $grid->column('id_supplier', __('Id supplier'));
        $grid->column('quantity', __('Quantity'));
        $grid->column('min_stock_quantity', __('Min stock quantity'));
        $grid->column('expire_date', __('Expire date'))->datetime();
        $grid->column('min_stock_expire_date', __('Min stock expire date'));
        $grid->column('clinic_id', __('Clinic'))->display(function ($id) {

            return "<a href='".route('admin.clinics.edit', $id)."'>Clinic</a>";

        });
        $grid->column('created_at', __('Created at'))->datetime("Y-m-d");
//        $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(ClinicProduct::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('image', __('Image'));
        $show->field('id_product', __('Id product'));
        $show->field('unit_measure', __('Unit measure'));
        $show->field('supplier_name', __('Supplier name'));
        $show->field('id_supplier', __('Id supplier'));
        $show->field('quantity', __('Quantity'));
        $show->field('min_stock_quantity', __('Min stock quantity'));
        $show->field('expire_date', __('Expire date'));
        $show->field('min_stock_expire_date', __('Min stock expire date'));
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
        $form = new Form(new ClinicProduct());

        $form->select('clinic_id', __('Clinic id'))->options(Clinic::all()->pluck('name','id'))->required();
        $form->text('name', __('Name'));
        $form->image('image', __('Image'));
        $form->text('id_product', __('Id product'));
        $form->text('unit_measure', __('Unit measure'));
        $form->text('supplier_name', __('Supplier name'));
        $form->text('id_supplier', __('Id supplier'));
        $form->number('quantity', __('Quantity'));
        $form->number('min_stock_quantity', __('Min stock quantity'));
        $form->date('expire_date', __('Expire date'))->default(date('Y-m-d'));
        $form->date('min_stock_expire_date', __('Min stock expire date'))->default(date('Y-m-d'));

        return $form;
    }
}
