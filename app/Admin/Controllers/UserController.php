<?php

namespace App\Admin\Controllers;

use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Layout\Content;


use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use HasResourceActions;
    use RegistersUsers;

    protected $title = 'Пользователи';


    public function index(Content $content)
    {
        return $content
            ->header('Пользователи')
            ->description('description')
            ->body($this->grid());
    }
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body($this->detail($id));
    }

    public function edit($id, Content $content)
    {
        return $content
            ->header('Edit')
            ->description('description')
            ->body($this->form(true)->edit($id));
    }

    public function create(Content $content)
    {
        return $content
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }
    public function store(Request $request)
    {
        $this->validator($request->all())->validate();
        event(new Registered($user = $this->addUser($request->all())));
        return redirect('/admin/users/' . $user->id . '');
    }
    protected function grid()
    {
        $grid = new Grid(new User);

        $grid->column('id', __('ID'))->sortable();
        $grid->column('name', __('Логин пользователя'))->sortable();
        $grid->column('email', __('Email'))->sortable();;
        $grid->column('fio', __('FIO'))->sortable();;

        $grid->column('role', 'Role')->display(function ($role) {
            $roles = config('user_roles.roles');
            return $roles[$role];
        })->sortable();
        $grid->column('created_at', __('Дата регистрации'));

        $grid->disableExport();
        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->like('name', 'Логин пользователя');
            $filter->like('email', 'email');
            $filter->like('fio', 'FIO');
            $roles = config('user_roles.roles');
            $filter->equal('role')->select($roles);
        });
        return $grid;
    }
    protected function detail($id)
    {
        $show = new Show(User::findOrFail($id));
        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('email', __('Email'));
        $show->field('fio', __('FIO'));
        $show->field('phone', __('Телефон'));
        return $show;
    }

    protected function form($edit = false)
    {

        $form = new Form(new User);
        $form->hidden('id');
        if ($edit) {
            $form->text('name', __('Логин пользователя'))->readonly()->required();
            $form->email('email', __('Email'))->readonly()->required();
            $form->text('password', __('Password'));
        } else {
            $form->text('name', __('Логин пользователя'))->required();
            $form->email('email', __('Email'))->required();
            $form->text('password', __('Password'))->required();
        }


        $roles = config('user_roles.roles');
        $form->select('role', 'Roles')->options($roles)->required();


        $form->text('fio', __('FIO'));
        $form->mobile('phone', __('Телефон'))
            ->options(['mask' => '+7 999 9999 9999']);

        $form->radio('status', 'Cтатус')->options(['0' => 'Заблокирован', '1' => 'Активен'])->default('1')->required();
        $form->radio('upload', 'Загрузка Excel')->options(['0' => 'Нет', '1' => 'Да'])->default('1')->required();
        $form->radio('ContactDownload', 'ContactDownload')->options(['0' => 'Нет', '1' => 'Да'])->default('1')->required();


        if ($edit) {
            $form->setAction('/admin/usersAdd');
        }

        $form->tools(function (Form\Tools $tools) {
            $tools->disableList();
            $tools->disableDelete();
            if (session('status')) {
                $tools->add('<div class="alert-success alert pull-right">Профиль обновлен</div>');
            }
        });

        $form->footer(function ($footer) {
            $footer->disableReset();
            $footer->disableViewCheck();
            $footer->disableEditingCheck();
            $footer->disableCreatingCheck();
        });

        return $form;
    }
    /*
     * кастомные методы
     */
    protected function addUser(array $data)
    {

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'fio' => $data['fio'],
            'phone' => $data['phone'],
            'role' => $data['role'],
            'status' => $data['status'],
            'upload' => $data['upload'],
            'ContactDownload' => $data['ContactDownload'],
            'password' => Hash::make($data['password']),
        ]);
    }
    protected function validator(array $data)
    {
        return Validator::make(
            $data,
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8'],
            ]
        );
    }
    public function readUser(Request $request)
    {
        $user = User::findOrFail($request->id);
        if ($request->password) {
            $validatedData = $request->validate([
                'password' => ['required', 'string', 'min:6'],
            ]);
            $user->password = Hash::make($request->password);
        }
        $user->fio = $request->fio;
        $user->phone = $request->phone;
        $user->role = $request->role;
        $user->status = $request->status;
        $user->upload = $request->upload;
        $user->ContactDownload = $request->ContactDownload;
        $user->save();

        return redirect('/admin/users/' . $user->id . '/edit')->with('status', 'Profile updated!');
    }
}
