@extends('layouts.dashboard')
@section('page_heading','欢迎登陆声纹认证系统')
@section('page_heading_small','用户登记页')
@section('section')

    <p id="cert_message" style="display: none;"></p>
    <p id="cert_message_type" style="display: none;"></p>

    <form id="add_cust_form">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">声纹用户登记&nbsp&nbsp<span style="color: red;border: solid red 1px;">A类</span></h3>
            <button type="button" class="btn btn-box-tool" data-widget="collapse" style="float: right">
                <i class="fa fa-minus"></i>
            </button>
        </div>
        <div class="box-body">
            <table class="table table-bordered">
                <tbody>
                <tr height="50px">
                    <td style="width: 220px;">
                        <div id="duoren_div_1">
                            <input type="text" class="form-control" id="personName" name="cust_name" placeholder="姓名">
                        </div>
                    </td>
                    <td style="width: 190px;">
                        <div>
                            <input type="text" class="form-control" name="cust_review_num" placeholder="认证号码">
                        </div>
                    </td>
                    <td style="width: 190px;">
                        <div>
                            <input type="text" class="form-control" name="cust_si_id" placeholder="社保编号">
                        </div>
                    </td>
                    <td style="width: 190px;">
                        <div>
                            <input type="text" class="form-control" name="cust_birthday" placeholder="出生年月">
                        </div>
                    </td>
                    <td style="width: 190px;" onclick="select_project();">
                        <div>
                            <span style="font-size: 12px;" id="parentIframe">redis启动失败</span>
                            <input type="hidden" name="cust_project">
                        </div>
                    </td>
                    <td rowspan="4" width="194px">
                        <div id="localImag">
                            <img width="162px" height="194px" name="cust_photo" id="id_img_pers" src="{{asset('public/img/userImage.png')}}" onerror="{{asset('public/img/userImage.png')}}">
                        </div>
                    </td>
                </tr>



                <tr height="50px">
                    <td style="width: 220px;">
                        <div>
                            <input type="text" class="form-control" id="certNumber" name="cust_id" placeholder="身份证号">
                        </div>
                    </td>
                    <td style="width: 190px;">
                        <div>
                            <input type="text" class="form-control" name="cust_phone_num" placeholder="备用手机">
                        </div>
                    </td>
                    <td style="width: 190px;">
                        <div>
                            <input type="text" class="form-control" name="cust_sex" placeholder="性别">
                        </div>
                    </td>
                    <td style="width: 190px;">
                        <div>
                            <input type="text" class="form-control" name="cust_c_day" placeholder="参工年月">
                        </div>
                    </td>
                    <td style="width: 190px;">
                        <div>
                            <select style="padding-left: 8px" name="cust_confirm_type" class="form-control">
                                @foreach($confirm_type as $v)
                                    <option>{{$v}}</option>
                                @endforeach
                            </select>
                        </div>
                    </td>
                </tr>



                <tr height="50px">
                    <td style="width: 220px;">
                        <div>
                            <input type="text" class="form-control" name="cust_bank_num" placeholder="银行卡号">
                        </div>
                    </td>
                    <td style="width: 190px;">
                        <div>
                            <input type="text" class="form-control" name="cust_address" placeholder="常驻地址">
                        </div>
                    </td>
                    <td style="width: 190px;">
                        <div>
                            <input type="text" class="form-control" name="cust_c_name" placeholder="工作单位">
                        </div>
                    </td>
                    <td style="width: 190px;">
                        <div>
                            <input type="text" class="form-control" name="cust_r_day" placeholder="退休年月">
                        </div>
                    </td>
                    <td style="width: 190px;">
                        <div>
                            <select style="padding-left: 8px" name="cust_si_type" class="form-control">
                                @foreach($staff_si_type as $v)
                                    <option>{{$v}}</option>
                                @endforeach
                            </select>
                        </div>
                    </td>
                </tr>



                <tr height="1px">
                    <td style="width: 220px;" align="center">
                        <div class="checkbox">
                            <a style="width: 100px;" id="button_readID" onclick="new Device().startFun();" class="btn btn-block btn-primary btn-sm">读取身份证</a>
                        </div>
                    </td>
                    <td style="width: 190px;" align="center">
                        <div class="checkbox">
                            <a style="width: 100px;" onclick="add_cust_A();" class="btn btn-block btn-primary btn-sm">登记</a>
                        </div>
                    </td>
                    <td style="width: 190px;" align="center">
                        <div class="checkbox">
                            <label>
                                <input style="height: 20px;width: 20px;" type="checkbox" name="error_info">&nbsp;&nbsp;&nbsp;<span style="font-size: 14px;">信息异常</span>
                            </label>
                        </div>
                    </td>
                    <td  style="width: 190px;" align="center">
                        <div class="checkbox">
                            <a style="width: 100px;display: none;" id="add_second_ready" name="no_person" onclick="add_second_ready();" class="btn btn-block btn-primary btn-sm">添加二年审人</a>
                        </div>
                    </td>
                    <td  style="width: 190px;" align="center">
                        <div class="checkbox" id="add_btw">
                            <a style="width: 100px;" onclick="add_btw();" class="btn btn-block btn-primary btn-sm">添加备注</a>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    </form>

    <table id="add_cust_table" class="table" cellpadding="3" cellspacing="0" align="center" border="0" width="100%" style="margin-top:20px;">
        <thead>
        <tr style="background-color: rgb(255, 255, 255);">
            <td colspan="8" class="title" align="center"><strong>用户登记管理</strong></td>
        </tr>
        <form id="myform2">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="which_table" value="cust_a">
            <tr>
            <td height="53" colspan="8" align="center">

                <div class="input-group">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                            <span id="table_chinese_name">客户表</span>
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a onclick="change_select_table('cust_a');">客户表</a></li>
                            <li><a onclick="change_select_table('cust_ready');">临时表</a></li>
                        </ul>
                    </div>
                    <input style="text-align: center" name="cust_review_num" class="form-control" type="text" placeholder="手机或身份证">
                </div>

                <br><br><button style="width: 100px;" type="button" onclick="select_data_A();" class="btn btn-block btn-primary btn-sm">查询</button>
            </td>
        </tr>
        </form>
        <tr style="background-color: rgb(255, 255, 255);">
            <th style="text-align: center;">用户编号</th>
            <th style="text-align: center;">所属区域</th>
            <th style="text-align: center;">参保类型</th>
            <th style="text-align: center;">用户姓名</th>
            <th style="text-align: center;">年审号码</th>
            <th style="text-align: center;">当前状态</th>
            <th style="text-align: center;">二年审人</th>
            <th style="text-align: center;">当前状态</th>
        </tr>
        </thead>
        <tbody class="trcolor">
        </tbody>
    </table>

    <div class="box-footer clearfix">
        <div id="laypage1" class="pagination pagination-sm no-margin pull-right">
        </div>
    </div>

    <script>

        function change_select_table(mytable) {

            $("input[name=which_table]").attr('value',mytable);

            if (mytable=='cust_a')
            {
                $("#table_chinese_name").html('客户表');
            }else
            {
                $("#table_chinese_name").html('临时表');
            }

            var url ='/data/ajax';
            var data={
                _token :$("input[name=_token]").val(),
                key    :mytable,
                type   :'set_which_table_in_redis'
            };
            $.post(url,data,function (response) {
                //set一下
            },'json');

        }

        $(function () {

            //给身份证输入框绑定change事件
            $("input[name=cust_id]").on('change',function () {

                var url ='/data/ajax';
                var data={
                    _token :$("input[name=_token]").val(),
                    type   :'get_wait_register_customer_info',
                    key    :$("input[name=cust_id]").val(),
                    tip    :''
                };

                //need
                var arr=[
                    'id','p_name','idcard','si_num','sex','bank','c_name','birthday','c_day','r_day'
                ];

                $.post(url,data,function (response) {

                    if (response.error=='0')
                    {
                        //客户姓名控件
                        $("#duoren_div_1").children().remove();
                        var select_input=$("<select style='padding-left: 8px' id=personName name=cust_name class=form-control></select>");
                        $("#duoren_div_1").append(select_input);

                        //如果多条数据，只遍历出客户姓名，添加到控件
                        var myi=1;

                        $.each(response.data,function (k1,v1) {

                            $.each(v1,function (k2,v2) {

                                if (myi<=10)
                                {
                                    if (k2=='id')
                                    {
                                        $("#add_second_ready").attr('name',v2);
                                    }

                                    if (k2=='p_name')
                                    {
                                        $("#personName").append("<option value="+$("#add_second_ready").attr('name')+">"+v2+"</option>");
                                    }

                                    if (k2=='idcard')
                                    {
                                        $("#certNumber").val(v2);
                                    }

                                    if (k2=='si_num')
                                    {
                                        $("input[name=cust_si_id]").val(v2);
                                    }

                                    if (k2=='sex')
                                    {
                                        $("input[name=cust_sex]").val(v2);
                                    }

                                    if (k2=='bank')
                                    {
                                        $("input[name=cust_bank_num]").val(v2);
                                    }

                                    if (k2=='c_name')
                                    {
                                        $("input[name=cust_c_name]").val(v2);
                                    }

                                    if (k2=='birthday')
                                    {
                                        $("input[name=cust_birthday]").val(v2);
                                    }

                                    if (k2=='c_day')
                                    {
                                        $("input[name=cust_c_day]").val(v2);
                                    }

                                    if (k2=='r_day')
                                    {
                                        $("input[name=cust_r_day]").val(v2);
                                    }

                                    myi++;
                                }else {

                                    if (k2=='id')
                                    {
                                        $("#add_second_ready").attr('other',v2);
                                    }

                                    if (k2=='p_name')
                                    {
                                        $("#personName").append("<option value="+$("#add_second_ready").attr('other')+">"+v2+"</option>");
                                    }

                                }

                            });

                        });

                        layer.msg(response.msg);

                        //给选择框绑定change事件
                        $("#personName").change(function () {

                            var url ='/data/ajax';
                            var data={
                                _token :$("input[name=_token]").val(),
                                type   :'get_wait_register_customer_info',
                                key    :$("#personName").val(),
                                tip    :'pid'
                            };

                            $.post(url,data,function (response) {

                                if (response.error=='0')
                                {
                                    $.each(response.data,function (k2,v2) {

                                        if (k2=='id')
                                        {
                                            $("#add_second_ready").attr('name',v2);
                                        }

                                        if (k2=='idcard')
                                        {
                                            $("#certNumber").val(v2);
                                        }

                                        if (k2=='si_num')
                                        {
                                            $("input[name=cust_si_id]").val(v2);
                                        }

                                        if (k2=='sex')
                                        {
                                            $("input[name=cust_sex]").val(v2);
                                        }

                                        if (k2=='bank')
                                        {
                                            $("input[name=cust_bank_num]").val(v2);
                                        }

                                        if (k2=='c_name')
                                        {
                                            $("input[name=cust_c_name]").val(v2);
                                        }

                                        if (k2=='birthday')
                                        {
                                            $("input[name=cust_birthday]").val(v2);
                                        }

                                        if (k2=='c_day')
                                        {
                                            $("input[name=cust_c_day]").val(v2);
                                        }

                                        if (k2=='r_day')
                                        {
                                            $("input[name=cust_r_day]").val(v2);
                                        }

                                    });

                                    layer.msg(response.msg);
                                }


                            });








                        });

                    }else
                    {
                        layer.msg(response.msg);
                    }

                },'json');

            });

            //给银行账号绑定change事件
            $("input[name=cust_bank_num]").on('change',function () {

                var url ='/data/ajax';
                var data={
                    _token :$("input[name=_token]").val(),
                    type   :'get_wait_register_customer_info_tianmen',
                    key    :$("input[name=cust_bank_num]").val(),
                    tip    :''
                };

                //need
                var arr=[
                    'id','p_name','idcard','si_num','sex','bank','c_name','birthday','c_day','r_day'
                ];

                $.post(url,data,function (response) {

                    if (response.error=='0')
                    {
                        //客户姓名控件
                        $("#duoren_div_1").children().remove();
                        var select_input=$("<select style='padding-left: 8px' id=personName name=cust_name class=form-control></select>");
                        $("#duoren_div_1").append(select_input);

                        //如果多条数据，只遍历出客户姓名，添加到控件
                        var myi=1;

                        $.each(response.data,function (k1,v1) {

                            $.each(v1,function (k2,v2) {

                                if (myi<=10)
                                {
                                    if (k2=='id')
                                    {
                                        $("#add_second_ready").attr('name',v2);
                                    }

                                    if (k2=='p_name')
                                    {
                                        $("#personName").append("<option value="+$("#add_second_ready").attr('name')+">"+v2+"</option>");
                                    }

                                    if (k2=='idcard')
                                    {
                                        $("#certNumber").val(v2);
                                    }

                                    if (k2=='si_num')
                                    {
                                        $("input[name=cust_si_id]").val(v2);
                                    }

                                    if (k2=='sex')
                                    {
                                        $("input[name=cust_sex]").val(v2);
                                    }

                                    if (k2=='bank')
                                    {
                                        $("input[name=cust_bank_num]").val(v2);
                                    }

                                    if (k2=='c_name')
                                    {
                                        $("input[name=cust_c_name]").val(v2);
                                    }

                                    if (k2=='birthday')
                                    {
                                        $("input[name=cust_birthday]").val(v2);
                                    }

                                    if (k2=='c_day')
                                    {
                                        $("input[name=cust_c_day]").val(v2);
                                    }

                                    if (k2=='r_day')
                                    {
                                        $("input[name=cust_r_day]").val(v2);
                                    }

                                    myi++;
                                }else {

                                    if (k2=='id')
                                    {
                                        $("#add_second_ready").attr('other',v2);
                                    }

                                    if (k2=='p_name')
                                    {
                                        $("#personName").append("<option value="+$("#add_second_ready").attr('other')+">"+v2+"</option>");
                                    }

                                }

                            });

                        });

                        layer.msg(response.msg);

                        //给选择框绑定change事件
                        $("#personName").change(function () {

                            var url ='/data/ajax';
                            var data={
                                _token :$("input[name=_token]").val(),
                                type   :'get_wait_register_customer_info',
                                key    :$("#personName").val(),
                                tip    :'pid'
                            };

                            $.post(url,data,function (response) {

                                if (response.error=='0')
                                {
                                    $.each(response.data,function (k2,v2) {

                                        if (k2=='id')
                                        {
                                            $("#add_second_ready").attr('name',v2);
                                        }

                                        if (k2=='idcard')
                                        {
                                            $("#certNumber").val(v2);
                                        }

                                        if (k2=='si_num')
                                        {
                                            $("input[name=cust_si_id]").val(v2);
                                        }

                                        if (k2=='sex')
                                        {
                                            $("input[name=cust_sex]").val(v2);
                                        }

                                        if (k2=='bank')
                                        {
                                            $("input[name=cust_bank_num]").val(v2);
                                        }

                                        if (k2=='c_name')
                                        {
                                            $("input[name=cust_c_name]").val(v2);
                                        }

                                        if (k2=='birthday')
                                        {
                                            $("input[name=cust_birthday]").val(v2);
                                        }

                                        if (k2=='c_day')
                                        {
                                            $("input[name=cust_c_day]").val(v2);
                                        }

                                        if (k2=='r_day')
                                        {
                                            $("input[name=cust_r_day]").val(v2);
                                        }

                                    });

                                    layer.msg(response.msg);
                                }


                            });








                        });

                    }else
                    {
                        layer.msg(response.msg);
                    }

                },'json');

            });

            //给客户姓名绑定change事件
            $("input[name=cust_name]").on('change',function () {

                var url ='/data/ajax';
                var data={
                    _token :$("input[name=_token]").val(),
                    type   :'get_wait_register_customer_info_tianmen',
                    key    :$("input[name=cust_name]").val(),
                    tip    :'cust_name'
                };

                //need
                var arr=[
                    'id','p_name','idcard','si_num','sex','bank','c_name','birthday','c_day','r_day'
                ];

                $.post(url,data,function (response) {

                    if (response.error=='0')
                    {
                        //客户姓名控件
                        $("#duoren_div_1").children().remove();
                        var select_input=$("<select style='padding-left: 8px' id=personName name=cust_name class=form-control></select>");
                        $("#duoren_div_1").append(select_input);

                        //如果多条数据，只遍历出客户姓名，添加到控件
                        var myi=1;

                        $.each(response.data,function (k1,v1) {

                            $.each(v1,function (k2,v2) {

                                if (myi<=10)
                                {
                                    if (k2=='id')
                                    {
                                        $("#add_second_ready").attr('name',v2);
                                    }

                                    if (k2=='p_name')
                                    {
                                        $("#personName").append("<option value="+$("#add_second_ready").attr('name')+">"+v2+"</option>");
                                    }

                                    if (k2=='idcard')
                                    {
                                        $("#certNumber").val(v2);
                                    }

                                    if (k2=='si_num')
                                    {
                                        $("input[name=cust_si_id]").val(v2);
                                    }

                                    if (k2=='sex')
                                    {
                                        $("input[name=cust_sex]").val(v2);
                                    }

                                    if (k2=='bank')
                                    {
                                        $("input[name=cust_bank_num]").val(v2);
                                    }

                                    if (k2=='c_name')
                                    {
                                        $("input[name=cust_c_name]").val(v2);
                                    }

                                    if (k2=='birthday')
                                    {
                                        $("input[name=cust_birthday]").val(v2);
                                    }

                                    if (k2=='c_day')
                                    {
                                        $("input[name=cust_c_day]").val(v2);
                                    }

                                    if (k2=='r_day')
                                    {
                                        $("input[name=cust_r_day]").val(v2);
                                    }

                                    myi++;
                                }else {

                                    if (k2=='id')
                                    {
                                        $("#add_second_ready").attr('other',v2);
                                    }

                                    if (k2=='p_name')
                                    {
                                        $("#personName").append("<option value="+$("#add_second_ready").attr('other')+">"+v2+"</option>");
                                    }

                                }

                            });

                        });

                        layer.msg(response.msg);

                        //给选择框绑定change事件
                        $("#personName").change(function () {

                            var url ='/data/ajax';
                            var data={
                                _token :$("input[name=_token]").val(),
                                type   :'get_wait_register_customer_info',
                                key    :$("#personName").val(),
                                tip    :'pid'
                            };

                            $.post(url,data,function (response) {

                                if (response.error=='0')
                                {
                                    $.each(response.data,function (k2,v2) {

                                        if (k2=='id')
                                        {
                                            $("#add_second_ready").attr('name',v2);
                                        }

                                        if (k2=='idcard')
                                        {
                                            $("#certNumber").val(v2);
                                        }

                                        if (k2=='si_num')
                                        {
                                            $("input[name=cust_si_id]").val(v2);
                                        }

                                        if (k2=='sex')
                                        {
                                            $("input[name=cust_sex]").val(v2);
                                        }

                                        if (k2=='bank')
                                        {
                                            $("input[name=cust_bank_num]").val(v2);
                                        }

                                        if (k2=='c_name')
                                        {
                                            $("input[name=cust_c_name]").val(v2);
                                        }

                                        if (k2=='birthday')
                                        {
                                            $("input[name=cust_birthday]").val(v2);
                                        }

                                        if (k2=='c_day')
                                        {
                                            $("input[name=cust_c_day]").val(v2);
                                        }

                                        if (k2=='r_day')
                                        {
                                            $("input[name=cust_r_day]").val(v2);
                                        }

                                    });

                                    layer.msg(response.msg);
                                }


                            });








                        });

                    }else
                    {
                        layer.msg(response.msg);
                    }

                },'json');

            });

            //每次打开页面，把which_table修改成客户表，在redis里
            var url ='/data/ajax';
            var data={
                _token :$("input[name=_token]").val(),
                key    :'cust_a',
                type   :'set_which_table_in_redis'
            };
            $.post(url,data,function (response) {
                //set一下
            },'json');

            var url ='/data/ajax';
            var data={
                _token :$("input[name=_token]").val(),
                type   :'get_redis'
            };
            $.post(url,data,function (response) {

                $("#parentIframe").html(response.res);
                $("input[name=cust_project]").val(response.res1);

            },'json');

            //显示这个用户，今日处理过的用户，往日的不显示
            refresh_A();

        });

    </script>


@stop
