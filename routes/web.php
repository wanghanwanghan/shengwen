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

    //登记用户信息页面，A类用户，开始注册按钮
    Route::post('api/register','APIController@web_ivr_api');

    //ivr返回注册用户的结果
    Route::get('api/register/return','APIController@ivr_return_1');

    //ivr返回验证的结果
    Route::get('api/verify/return','APIController@ivr_return_2');

    //声纹引擎的操作
    Route::post('api/vocalprint','APIController@web_vocalprint_api');

});//*******************************************************

//登陆
//Route::get('/','APIController@baiducheck');
Route::get('/', function () {
    return view('login');
});

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

//循环拨打用户认证
Route::get('loop/call','WebController@loop_call');

//统计
Route::get('statistics','WebController@statistics');

//分析
Route::get('analysis','WebController@analysis');

//change_btw打开的iframe
Route::get('change_btw/{id}', function ($id) {
    $id=$id;
    return view('change_btw',compact('id'));
});








