<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


//wx和员工都不登录
Route::namespace('App\Http\Controllers\xcx')->middleware(['web'])->group(function () {
    Route::any('/common/index', 'CommonController@index');//首页信息
    Route::any('/common/news', 'CommonController@news');//新闻列表
    Route::any('/common/intro', 'CommonController@intro');//简介
    Route::any('/wx/login/tokenAvaliable', 'WxLoginController@tokenAvaliable');//校验token
    Route::any('/wx/login/login', 'WxLoginController@login');//wx登录
});
//wx登录,工号不需要登录
Route::namespace('App\Http\Controllers\xcx')->middleware(['web','wxloginauth'])->group(function () {
    Route::any('/reg/store', 'RegisterController@store');//注册员工
    Route::any('/reg/login', 'RegisterController@login');//员工登录

    Route::any('/wx/user/saveAuth', 'UserController@saveAuth');//授权
    Route::any('/wx/user/saveTel', 'UserController@saveTel');//授权
    Route::any('/wx/user/getUserInfo', 'UserController@getUserInfo');//读取用户信息
});
//员工登录 && wx登录
Route::namespace('App\Http\Controllers\xcx')->middleware(['web','wxloginauth','registerloginauth'])->group(function () {
    Route::any('/rec/store', 'RecController@store');//添加推荐人
    Route::any('/rec/recList', 'RecController@recList');//推荐列表
    Route::any('/rec/statList', 'RecController@statList');//推荐状态列表
});
#管理员
Route::namespace('App\Http\Controllers\xcx')->middleware(['web','wxloginauth','registerloginauth','adminauth'])->group(function () {
    Route::any('/admin/regList', 'AdminController@registerList');//注册列表
    Route::any('/admin/checkRec', 'AdminController@checkRec');//修改推荐人状态
    Route::any('/admin/recInfo', 'AdminController@recInfo');//推荐人信息
});
