<?php

//测试
Route::get('/test1','TestController@test_1');

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

//select_project
Route::get('select_project', function () {
    return view('select_project');
});

//用户登记
Route::group(['middleware'=>['LoginMiddleware','AddCustMiddleware']],function (){

    //添加客户信息A
    Route::get('add/cust','WebController@add_cust');

    //添加客户信息B
    Route::get('add/cust/b','WebController@add_cust_b');

    //添加指静脉
    Route::get('add/cust/vena','WebController@add_cust_vena');
    Route::get('open/fv/page',function (){return view('zhijingmai1');});

    //添加第二年审人
    Route::get('add/second','WebController@add_second');

    //添加无号码客户信息
    Route::get('add/cust/ready','WebController@add_cust_ready');

});

//客服功能 => 用户认证
Route::group(['middleware'=>['LoginMiddleware','ServiceCareMiddleware']],function (){

    //指静脉认证
    Route::get('fv/match','WebController@fv_match');
    Route::get('open/fv/page/renzheng',function (){return view('zhijingmai2');});

    //轮播认证
    Route::get('loop/call','WebController@loop_call');

});

//客户管理
Route::group(['middleware'=>['LoginMiddleware','CustManagementMiddleware']],function (){

    //修改客户信息
    Route::get('modify/cust/info','WebController@modify_cust_info');

    //修改客户信息
    Route::get('modify/cust/info/ready','WebController@modify_cust_info_ready');

});

//数据统计
Route::group(['middleware'=>['LoginMiddleware','DataStatisticsMiddleware']],function (){

    //维护现有客户 => 认证结果统计
    Route::get('service/care','WebController@service_care');

    //导出认证结果 => 采集结果统计
    Route::get('import/confirm/result','WebController@import_confirm_result');

    //天门导出
    Route::get('export/tianmen/result','WebController@export_tianmen_result');

    //天门下载管理页
    Route::get('download/tianmen/result','WebController@download_tianmen_result');

});

//分析
Route::group(['middleware'=>['LoginMiddleware','DataAnalysisMiddleware']],function (){

    //统计 => 声纹登记检查
    Route::get('statistics','WebController@statistics');

    //分析 => 采集总览
    Route::get('analysis','WebController@analysis');

});

//声纹管理 => 操作日志
Route::group(['middleware'=>['LoginMiddleware','VoiceManagementMiddleware']],function (){

    //录音返回信息
    Route::get('ivr/return/msg','WebController@ivr_return_msg');

    //轮播返回信息
    Route::get('ivr/return/loop/msg','WebController@ivr_return_loop_msg');

    //指静脉登记返回信息
    Route::get('fv/register/return/msg','WebController@fv_register_return_msg');

    //指静脉认证返回信息
    Route::get('fv/confirm/return/msg','WebController@fv_confirm_return_msg');

});

//系统设置的路由组
Route::group(['middleware'=>['LoginMiddleware','SetSystemMiddleware']],function (){

    Route::get('set/project', function () {
        return view('set_project');
    });
    Route::get('import/new/project', function () {
        return view('import_new_project');
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

    //导入待采集客户信息
    Route::get('source/cust/data','WebController@source_cust_data');

    //基础信息与地区关联
    Route::get('choose/basedata/tablename', function () {
        return view('choose_basedata_tablename');
    });

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

    //查看员工列表
    Route::get('show/staff/list', function () {
        return view('show_staff_list');
    });

    //查看员工工作量
    Route::get('show/staff/work/status', function () {
        return view('show_staff_work_status');
    });

    //查看系统日志
    Route::get('show/system/log', function () {
        return view('show_system_log');
    });

    //给客服分配需要电话回访的客户
    Route::get('allocation','WebController@allocation')->name('allocation');

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

//change_btw_fv打开的iframe
Route::get('change_btw_fv/{id}', function ($id) {
    $id=$id;
    return view('change_btw_fv',compact('id'));
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

//Excel操作
Route::post('/import1','ExcelController@import_1');
Route::post('/import2','ExcelController@import_2');
Route::post('/import3','ExcelController@import_3');

//Excel操作中的导入，用到的路由
//声纹
Route::get('/insert_excel_data_1','ExcelController@insert_excel_data_1');
Route::get('/insert_excel_data_2','ExcelController@insert_excel_data_2');
Route::get('/export1/{key}','ExcelController@export1');
Route::get('/export2/{key}','ExcelController@export2');
//指静脉
Route::get('/export3/{key}','ExcelController@export3');
//导出声纹指静脉认证结果
Route::get('/export4/{key}','ExcelController@export4');
Route::get('/export5/{key}','ExcelController@export5');
Route::get('/export6/{key}','ExcelController@export6');
Route::get('/export7/{key}','ExcelController@export7');

//导出天门专用已采集已注册未采集未注册数据
Route::get('/export8/{key}','ExcelController@export8');

//南陵导出每天采集结果
Route::get('/nanling/{key}','ExcelController@nanling_export');
