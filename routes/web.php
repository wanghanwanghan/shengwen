<?php

//系统设置的路由组
Route::group(['middleware'=>['SetSystemMiddleware']],function (){

    Route::get('set/project', function () {
        return view('set_project');
    });

    Route::get('set/si', function () {
        return view('set_si');
    });

    Route::get('set/confirm', function () {
        return view('set_confirm');
    });

    Route::get('set/level', function () {
        return view('set_level');
    });

    Route::get('set/staff', function () {
        return view('set_staff');
    });

});

//如果session里没有user，就转到登陆页面
Route::group(['middleware'=>['LoginMiddleware']],function (){

    //主页
    Route::get('index', function () {
        return view('index');
    });

});

//超级管理员路由组
Route::group(['middleware'=>['RootMiddleware']],function (){

    //给员工发邮件
    Route::get('send/staffmail', function () {
        return view('send_staffmail');
    });

});

//本web的接口路由组******************************************
Route::group(['middleware'=>['APIMiddleware']],function (){

    //查询
    Route::get('api/select','APIController@select_something');
    Route::post('api/ajax','APIController@ajax');

    //开始注册按钮
    Route::post('api/register','APIController@web_ivr_api');

    //开始验证按钮
    Route::post('api/verify','APIController@web_ivr_api');

    //轮播
    Route::post('api/loop/call','APIController@web_ivr_api');

    //ivr返回注册的结果
    Route::get('api/register/return','APIController@ivr_return_1');

    //ivr返回验证的结果
    Route::get('api/verify/return','APIController@ivr_return_2');

    //ivr返回轮播的结果
    Route::get('api/loop/return','APIController@ivr_return_3');

    //客户主动认证的结果
    Route::get('api/initiative/return','APIController@ivr_return_4');





});//*******************************************************

//登陆
Route::get('/', function () {
    return view('login');
});
//Route::get('/','APIController@ceshi_test');

//所有ajax数据处理
Route::post('data/ajax','DataController@ajax');

//添加客户信息A
Route::get('add/cust','WebController@add_cust');

//添加客户信息B
Route::get('add/cust/b','WebController@add_cust_b');

//维护现有客户
Route::get('service/care','WebController@service_care');

//添加第二年审人
Route::get('add/second','WebController@add_second');

//添加客户信息B
Route::get('add/cust/b','WebController@add_cust_b');

//查询用户声纹信息
Route::get('select/info','WebController@select_info');

//修改客户信息
Route::get('modify/cust/info','WebController@modify_cust_info');

//录音返回信息
Route::get('ivr/return/msg','WebController@ivr_return_msg');

//循环拨打用户认证
Route::get('loop/call','WebController@loop_call');

//轮播返回信息
Route::get('ivr/return/loop/msg','WebController@ivr_return_loop_msg');

//统计
Route::get('statistics','WebController@statistics');

//分析
Route::get('analysis','WebController@analysis');

//change_btw打开的iframe
Route::get('change_btw/{id}', function ($id) {
    $id=$id;
    return view('change_btw',compact('id'));
});








