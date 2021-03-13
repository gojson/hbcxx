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
//不登录路由
Route::namespace('App\Http\Controllers\xcx')->middleware(['web'])->group(function () {
    Route::any('/reg/store', 'RegisterController@store');//注册员工
    Route::any('/reg/login', 'RegisterController@login');//登录
    Route::any('/reg/logout', 'RegisterController@logout');//登出
});

//wx不登录路由
Route::namespace('App\Http\Controllers\xcx')->group(function () {
    Route::any('/wx/login/login', 'WxLoginController@login');//登录
    Route::any('/wx/user/saveAuth', 'UserController@saveAuth');//登录
    Route::any('/wx/user/saveTel', 'UserController@saveTel');//
});

//不登录路由
Route::namespace('App\Http\Controllers\xcx')->middleware(['web'])->group(function () {
    Route::any('/common/index', 'CommonController@index');//首页信息
    Route::any('/common/news/', 'CommonController@news');//新闻列表
    Route::any('/common/intro', 'UserController@saveTel');//简介
});


//登录路由
Route::namespace('App\Http\Controllers\xcx')->middleware(['web','loginauth'])->group(function () {
    Route::any('/rec/store', 'RecController@store');//添加推荐人
    Route::any('/rec/recList', 'RecController@recList');//推荐列表
    Route::any('/rec/statList', 'RecController@statList');//推荐状态列表
});


#管理员
Route::namespace('App\Http\Controllers\xcx')->middleware(['web','adminauth'])->group(function () {
    Route::any('/admin/regList', 'AdminController@registerList');//注册列表
    Route::any('/admin/checkRec', 'AdminController@checkRec');//修改推荐人状态
});
