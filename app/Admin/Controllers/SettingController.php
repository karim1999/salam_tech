<?php

namespace App\Admin\Controllers;

use App\Models\Setting;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class SettingController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Setting';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Setting());

        $grid->column('id', __('Id'));
        $grid->column('pharmacy_distance', __('Pharmacy distance'));
        $grid->column('lab_distance', __('Lab distance'));
        $grid->column('clinic_distance', __('Clinic distance'));
        $grid->column('doctor_distance', __('Doctor distance'));
        $grid->column('map_distance', __('Map distance'));
        $grid->column('rate_points', __('Rate points'));
        $grid->column('user_terms_ar', __('User terms ar'));
        $grid->column('user_terms_en', __('User terms en'));
        $grid->column('doctor_terms_ar', __('Doctor terms ar'));
        $grid->column('doctor_terms_en', __('Doctor terms en'));
        $grid->column('clinic_terms_ar', __('Clinic terms ar'));
        $grid->column('clinic_terms_en', __('Clinic terms en'));
        $grid->column('user_policy_ar', __('User policy ar'));
        $grid->column('user_policy_en', __('User policy en'));
        $grid->column('doctor_policy_ar', __('Doctor policy ar'));
        $grid->column('doctor_policy_en', __('Doctor policy en'));
        $grid->column('clinic_policy_ar', __('Clinic policy ar'));
        $grid->column('clinic_policy_en', __('Clinic policy en'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        $grid->disableCreateButton();
        $grid->disableBatchActions();

        $grid->actions(function ($actions) {
            $actions->disableDelete();
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
        $show = new Show(Setting::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('pharmacy_distance', __('Pharmacy distance'));
        $show->field('lab_distance', __('Lab distance'));
        $show->field('clinic_distance', __('Clinic distance'));
        $show->field('doctor_distance', __('Doctor distance'));
        $show->field('map_distance', __('Map distance'));
        $show->field('rate_points', __('Rate points'));
        $show->field('user_terms_ar', __('User terms ar'));
        $show->field('user_terms_en', __('User terms en'));
        $show->field('doctor_terms_ar', __('Doctor terms ar'));
        $show->field('doctor_terms_en', __('Doctor terms en'));
        $show->field('clinic_terms_ar', __('Clinic terms ar'));
        $show->field('clinic_terms_en', __('Clinic terms en'));
        $show->field('user_policy_ar', __('User policy ar'));
        $show->field('user_policy_en', __('User policy en'));
        $show->field('doctor_policy_ar', __('Doctor policy ar'));
        $show->field('doctor_policy_en', __('Doctor policy en'));
        $show->field('clinic_policy_ar', __('Clinic policy ar'));
        $show->field('clinic_policy_en', __('Clinic policy en'));
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
        $form = new Form(new Setting());

        $form->number('pharmacy_distance', __('Pharmacy distance'))->default(0);
        $form->number('lab_distance', __('Lab distance'))->default(0);
        $form->number('clinic_distance', __('Clinic distance'))->default(0);
        $form->number('doctor_distance', __('Doctor distance'))->default(0);
        $form->number('map_distance', __('Map distance'))->default(0);
        $form->number('rate_points', __('Rate points'))->default(0);
        $form->textarea('user_terms_ar', __('User terms ar'));
        $form->textarea('user_terms_en', __('User terms en'));
        $form->textarea('doctor_terms_ar', __('Doctor terms ar'));
        $form->textarea('doctor_terms_en', __('Doctor terms en'));
        $form->textarea('clinic_terms_ar', __('Clinic terms ar'));
        $form->textarea('clinic_terms_en', __('Clinic terms en'));
        $form->textarea('user_policy_ar', __('User policy ar'));
        $form->textarea('user_policy_en', __('User policy en'));
        $form->textarea('doctor_policy_ar', __('Doctor policy ar'));
        $form->textarea('doctor_policy_en', __('Doctor policy en'));
        $form->textarea('clinic_policy_ar', __('Clinic policy ar'));
        $form->textarea('clinic_policy_en', __('Clinic policy en'));

        return $form;
    }
}
