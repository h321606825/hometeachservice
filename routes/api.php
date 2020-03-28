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
//Route::any(['prefix' => 'admin'],function (){
//    Route::any('passport/login', 'Api\Admin\PassportController@login');
//});
//Route::any(['prefix' => 'user'],function (){
//    Route::any();
//});