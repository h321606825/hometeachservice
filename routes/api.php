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
//添加管理员
Route::any('admin/passport/add','Api\Admin\Passport\PassportController@add');
//管理员登录
Route::any('admin/passport/login', 'Api\Admin\Passport\PassportController@login');
//管理员注销
Route::any('admin/passport/logout', 'Api\Admin\Passport\PassportController@loginout');
//密码更新 不需要？
Route::any('admin/passport/update', 'Api\Admin\Passport\PassportController@updatePassport');
//编辑学员
Route::any('admin/user/updateStu', 'Api\Admin\UserController@updateStu');
//编辑教员
Route::any('admin/user/updateTea', 'Api\Admin\UserController@updateTea');
//删除学员
Route::any('admin/user/deleteStu', 'Api\Admin\UserController@deleteStu');
//删除教员
Route::any('admin/user/deleteTea', 'Api\Admin\UserController@deleteTea');
//网站基本信息配置
Route::any('admin/base/list', 'Api\Admin\UserController@getList');

//教师注册
Route::any('user/register/tea', 'Api\User\UserController@registerTea');
//用户登录
Route::any('user/login','Api\User\UserController@login');
//用户退出登录
Route::any('user/logout','Api\User\UserController@logout');
//学生注册
Route::any('user/register/stu',"Api\User\UserController@registerStu");
//个人中心
Route::any('user/self','Api\User\UserController@userSelf');
//学员列表
Route::any('user/stu/list', 'Api\User\UserController@getStuList');
//教员列表
Route::any('user/tea/list','Api\User\UserController@getTeaList');
//用户修改
Route::any('user/update', 'Api\User\UserController@updateUser');
//用户删除
Route::any('user/delete', 'Api\User\UserController@deleteUser');
//教员详情
Route::any('user/stu/info', 'Api\User\InfoController@stuInfo');
//学员详情
Route::any('user/tea/info', 'Api\User\InfoController@teaInfo');
//教员下单
Route::any('/user/tea/order','Api\User\OrderController@teaOrder');
//学员下单
Route::any('/user/stu/order','Api\User\OrderController@stuOrder');

//网站基本信息获取
Route::any('base/info/get', 'Api\Base\InfoController@getInfo');
//网站基本信息更新
Route::any('base/info/update', 'Api\Base\InfoController@updateInfo');
//学员列表
Route::any('base/user/stuList', 'Api\Base\InfoController@getStuList');
//教员列表
Route::any('base/user/teaList', 'Api\Base\InfoController@getTeaList');



//获取图片验证码
Route::any("base/getCaptcha",'Api\Base\BaseController@getCaptcha');
//图片上传
Route::any("base/uploadFile",'Api\Base\BaseController@uploadFile');