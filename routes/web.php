<?php

//登陆
Route::get('/', function () {
    return view('login');
});
Route::group(['middleware'=>['LoginMiddleware']],function (){

    //主页
    Route::get('index', function () {
        return view('index');
    });

});

//用户登记
Route::group(['middleware'=>['LoginMiddleware','AddCustMiddleware']],function (){

    //添加客户信息A
    Route::get('add/cust','WebController@add_cust');

    //添加客户信息B
    Route::get('add/cust/b','WebController@add_cust_b');

    //添加第二年审人
    Route::get('add/second','WebController@add_second');

});

//客服功能
Route::group(['middleware'=>['LoginMiddleware','ServiceCareMiddleware']],function (){

    //维护现有客户
    Route::get('service/care','WebController@service_care');

});

//客户管理
Route::group(['middleware'=>['LoginMiddleware','CustManagementMiddleware']],function (){

    //修改客户信息
    Route::get('modify/cust/info','WebController@modify_cust_info');

    //录音返回信息
    Route::get('ivr/return/msg','WebController@ivr_return_msg');

});

//声纹管理
Route::group(['middleware'=>['LoginMiddleware','VoiceManagementMiddleware']],function (){

    //循环拨打用户认证
    Route::get('loop/call','WebController@loop_call');

    //轮播返回信息
    Route::get('ivr/return/loop/msg','WebController@ivr_return_loop_msg');

    //统计
    Route::get('statistics','WebController@statistics');

    //分析
    Route::get('analysis','WebController@analysis');

});

//系统设置的路由组
Route::group(['middleware'=>['LoginMiddleware','SetSystemMiddleware']],function (){

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

//超级管理员路由组
Route::group(['middleware'=>['LoginMiddleware','RootMiddleware']],function (){

    //修改认证配置
    Route::get('edit/config', function () {
        return view('edit_config');
    });

    //修改员工信息
    Route::get('edit/staff', function () {
        return view('edit_staff');
    });

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

    //验证动态口令
    Route::get('api/dynamic','APIController@check_dynamicpassword');

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

//所有ajax数据处理
Route::post('data/ajax','DataController@ajax');

//change_btw打开的iframe
Route::get('change_btw/{id}', function ($id) {
    $id=$id;
    return view('change_btw',compact('id'));
});

//轮播页面的详细信息超链接路由
Route::get('detail_info/{id}', function ($id) {
    $id=$id;

    static $redis=null;
    if ($redis==null)
    {
        $redis=new Redis;
        $host='127.0.0.1';
        $post='6379';
        $res=$redis->connect($host,$post);
        if(!$res) die('redis server链接失败');
    }

    //轮播总用户数
    $loop_totle=$redis->get('loop_totle_'.$id);
    if($loop_totle===false) $loop_totle='未取得';
    //未完成用户数
    $loop_unfinished=$redis->get('loop_unfinished_'.$id);
    if($loop_unfinished===false) $loop_unfinished='未取得';
    //完成用户数
    $loop_finish=$redis->get('loop_finish_'.$id);
    if($loop_finish===false) $loop_finish='未取得';
    //未认证通过用户数
    $loop_unpass=$redis->get('loop_unpass_'.$id);
    if($loop_unpass===false) $loop_unpass='未取得';
    //认证通过用户数
    $loop_pass=$redis->get('loop_pass_'.$id);
    if($loop_pass===false) $loop_pass='未取得';

    return view('detail_info',compact('loop_totle','loop_unfinished','loop_finish','loop_unpass','loop_pass'));
});

//得到session中的username
Route::get('get/user/name','WebController@get_username');







