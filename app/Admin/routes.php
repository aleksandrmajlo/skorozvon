<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');

    // пользователи
    $router->resource('users', UserController::class);

    $router->get('usersAdd', 'UserController@readUser');
    $router->post('usersAdd', 'UserController@readUser');
    $router->put('usersAdd', 'UserController@readUser');

    // банки
    $router->resource('banks', BankController::class);

    // настроййки
    $router->resource('settings', SettingController::class);


});
