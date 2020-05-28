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
Route::any('admin/passport/add','Api\Admin\Passport\PassportController@add')->middleware('jurisdiction');  //1
//管理员登录
Route::any('admin/passport/login', 'Api\Admin\Passport\PassportController@login');   //1
//管理员注销
Route::any('admin/passport/logout', 'Api\Admin\Passport\PassportController@loginout')->middleware('jurisdiction');  //1
//密码更新 不需要？
Route::any('admin/passport/update', 'Api\Admin\Passport\PassportController@updatePassport')->middleware('jurisdiction');
//编辑学员
Route::any('admin/user/updateStu', 'Api\Admin\UserController@updateStu')->middleware('jurisdiction');  //1
//编辑教员
Route::any('admin/user/updateTea', 'Api\Admin\UserController@updateTea')->middleware('jurisdiction');  //1
//删除学员
Route::any('admin/user/deleteStu', 'Api\Admin\UserController@deleteStu');//->middleware('jurisdiction');  //1
//删除教员
Route::any('admin/user/deleteTea', 'Api\Admin\UserController@deleteTea')->middleware('jurisdiction');  //1
//编辑客服
Route::any('admin/user/updateCustomer', 'Api\Admin\UserController@updateCustomer')->middleware('jurisdiction');  //1
//获取资讯信息
Route::any('admin/base/getInfo', 'Api\Base\BaseController@getList');
//添加资讯信息
Route::any('admin/base/addInfo','Api\Base\BaseController@addList');
//获取资讯类型
Route::any('admin/base/customerTypeList','Api\Base\BaseController@getCustomerType');
//删除资讯信息
Route::any('admin/base/deleteInfo','Api\Base\BaseController@deleteList');
//发布通知公告
Route::any('admin/base/addAffiche','Api\Base\BaseController@addAffiche');
//修改通知公告
Route::any('admin/base/updateAffiche','Api\Base\BaseController@updateAffiche');
//删除通知公告
Route::any('admin/base/deleteAffiche','Api\Base\BaseController@deleteAffiche');
//订单列表
Route::any('admin/info/getOrder','Api\Admin\OrderController@getOrder');
//通过订单
Route::any('admin/info/updateOrder','Api\Base\InfoController@updateOrder');
//删除订单
Route::any('admin/info/delOrder','Api\Admin\OrderController@delOrder');

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
Route::any('user/tea/order','Api\User\OrderController@teaOrder');
//学员下单
Route::any('user/stu/order','Api\User\OrderController@stuOrder');
//取消订单
Route::any('user/delOrder','Api\User\OrderController@delOrder');
//评价订单
Route::any('user/evaluateOrder','Api\User\OrderController@evaluateOrder');
//完成订单
Route::any('user/finishOrder','Api\User\OrderController@finishOrder');
//用户订单信息
Route::any('user/info/order','Api\User\OrderController@orderInfo');
//获取客服信息
Route::any('user/info/customer','Api\User\InfoController@customer');
//教师推荐
Route::any('user/info/recommendTea','Api\User\InfoController@recommendTea');
//学生推荐
Route::any('user/info/recommendStu','Api\User\InfoController@recommendStu');

//网站基本信息获取
Route::any('base/info/get', 'Api\Base\InfoController@getInfo');
//网站基本信息更新
Route::any('base/info/update', 'Api\Base\InfoController@updateInfo');
//学员列表
Route::any('base/user/stuList', 'Api\Base\InfoController@getStuList');
//教员列表
Route::any('base/user/teaList', 'Api\Base\InfoController@getTeaList');
//获取授课信息
Route::any('base/info/getClass','Api\Base\InfoController@getClass');
//获取首页图片信息
Route::any('base/info/getPicture','Api\Base\InfoController@getPicture');
//添加首页图片信息
Route::any('base/info/addPicture','Api\Base\InfoController@addPicture');
//删除首页图片
Route::any('base/info/delPicture','Api\Base\InfoController@deletePicture');
//通知公告
Route::any('base/info/getAffiche','Api\Base\InfoController@getAffiche');

//获取图片验证码
Route::any("base/getCaptcha",'Api\Base\BaseController@getCaptcha');
//图片上传
Route::any("base/uploadFile",'Api\Base\BaseController@uploadFile');