<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::any('/', function () {
    return 'hi api!';
});
Route::any(['prefix' => 'admin'],function (){
    //管理员登录
    Route::any('passport/login', 'Api\Admin\Passport\PassportController@login');
    //管理员注销
    Route::any('passport/logout', 'Api\Admin\Passport\PassportController@loginout');
    //密码更新
    Route::any('passport/update', 'Api\Admin\Passport\PassportController@updatePassport');
    //编辑学员
    Route::any('user/updateStu', 'Api\Admin\User\UserController@updateStu');
    //编辑教员
    Route::any('user/updateTea', 'Api\Admin\User\UserController@updateTea');
    //删除学员
    Route::any('user/deleteStu', 'Api\Admin\User\UserController@deleteStu');
    //删除教员
    Route::any('user/deleteTea', 'Api\Admin\User\UserController@deleteTea');
    //网站基本信息配置
    Route::any('base/list', 'Api\Admin\Base\UserController@getList');

});
Route::any(['prefix' => 'user'],function (){
    //用户注册
    Route::any('/insert', 'Api\User\User\UserController@insertUser');
    //用户列表
    Route::any('/list', 'Api\User\User\UserController@getList');
    //用户修改
    Route::any('/update', 'Api\User\User\UserController@updateUser');
    //用户删除
    Route::any('/delete', 'Api\User\User\UserController@deleteUser');
    //教员详情
    Routte::any('/stu/info', 'Api\User\InfoController@stuInfo');
    //学员详情
    Routte::any('/tea/info', 'Api\User\InfoController@teaInfo');

});
Route::any(['prefix' => 'base'],function (){
    //网站基本信息获取
    Route::any('info/get', 'Api\Base\InfoController@getInfo');
    //网站基本信息更新
    Route::any('info/update', 'Api\Base\InfoController@updateInfo');
    //学员列表
    Route::any('user/stuList', 'Api\Base\User\InfoController@getStuList');
    //教员列表
    Route::any('user/teaList', 'Api\Base\User\InfoController@getTeaList');
});