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

//        $grid->column('id', __('Id'));
//        $grid->column('type', __('Type'));
        $grid->column('key', __('Key'));
        $grid->column('value', __('Value'));
        $grid->disableCreateButton();
        $grid->disableFilter();
        $grid->disablePagination();
        $grid->disableExport();
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
//        $show->field('type', __('Type'));
        $show->field('key', __('Key'));
        $show->field('value', __('Value'));
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
//        $form->text('type', __('Type'));
        $form->text('key', __('Key'));
        $form->textarea('value', __('Value'));
        return $form;
    }
}
