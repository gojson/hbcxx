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
//登录路由
Route::namespace('App\Http\Controllers\xcx')->middleware(['web','loginauth'])->group(function () {
    Route::any('/rec/store', 'RecController@store');//添加推荐人
    Route::any('/rec/check', 'RecController@check');//审核推荐人
});


#管理员
Route::namespace('App\Http\Controllers\xcx')->middleware(['web','adminauth'])->group(function () {
    Route::any('/admin/registerList', 'AdminController@registerList');//注册列表
    Route::any('/admin/checkRegister', 'AdminController@checkRegister');//审核注册人
});
