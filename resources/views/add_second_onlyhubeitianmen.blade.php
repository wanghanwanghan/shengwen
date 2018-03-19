@extends('layouts.plane')
@section('body')

    <div class="box box-info">
        <!--第一年审人信息-->
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">以下是第一年审人信息</h3>
                <button type="button" class="btn btn-box-tool" data-widget="collapse" style="float: right">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
            <div class="box-body">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <td>
                            <div class="col-xs-9">
                                <input type="text" disabled class="form-control" value=
                                @if(isset($model['p_name']))
                                    {{$model['p_name']}}
                                @else
                                    {{$model['cust_name']}}
                                @endif
                                >
                            </div>
                        </td>
                        <td>
                            <div class="col-xs-9">
                                <input type="text" disabled class="form-control" value=
                                @if(isset($model['idcard']))
                                    {{$model['idcard']}}
                                @else
                                    {{$model['cust_id']}}
                                @endif
                                >
                            </div>
                        </td>
                        <td>
                            <div class="col-xs-9">
                                <input type="text" disabled class="form-control" value=
                                @if(isset($model['bank']))
                                    {{$model['bank']}}
                                @else
                                    {{$model['cust_review_num']}}
                                @endif
                                >
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="box box-info">
        <p id="cert_message" style="display: none;"></p>
        <p id="cert_message_type" style="display: none;"></p>
        <!--添加第二年审人-->
        <form id="add_second_form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="first_id" value="{{ $first_id }}">
            <input type="hidden" name="cust_project" value="{{ $first_project }}">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">以下是第二年审人信息</h3>
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
                                    @if(isset($model['cust_review_num']))
                                        <input type="text" style="display: none" class="form-control" name="cust_review_num" value="{{$model['cust_review_num']}}"/>
                                        <input type="text" disabled class="form-control" value="{{$model['cust_review_num']}}"/>
                                    @else
                                        <input type="text" class="form-control" name="cust_review_num" placeholder="手机号码"/>
                                    @endif
                                </div>
                            </td>
                            <td style="width: 190px;">
                                <div>
                                    <input type="text" class="form-control" name="cust_si_id" placeholder="社保编号">
                                </div>
                            </td>
                            <td style="width: 190px;">
                                <div>
                                    <input type="text" class="form-control" disabled name="cust_birthday" placeholder="出生年月">
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
                                    <input type="text" class="form-control" disabled name="cust_sex" placeholder="性别">
                                </div>
                            </td>
                            <td style="width: 190px;">
                                <div>
                                    <input type="text" class="form-control" disabled name="cust_c_day" placeholder="参工年月">
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
                                    <input type="text" class="form-control" name="cust_address" placeholder="常住地址">
                                </div>
                            </td>
                            <td style="width: 190px;">
                                <div>
                                    <input type="text" class="form-control" disabled name="cust_c_name" placeholder="工作单位">
                                </div>
                            </td>
                            <td style="width: 190px;">
                                <div>
                                    <input type="text" class="form-control" disabled name="cust_r_day" placeholder="退休年月">
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
                                    <a style="width: 100px;" onclick="add_second_for_first_only_tianmen();" class="btn btn-block btn-primary btn-sm">登记</a>
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
                                    <a style="width: 100px;display: none;" id="add_second_ready" name="no_person" class="btn btn-block btn-primary btn-sm">添加二年审人</a>
                                </div>
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
    </div>

    <script>

        $(function () {

            //给身份证输入框绑定change事件
            $("input[name=cust_id]").on('change',function () {

                var url ='/data/ajax';
                var data={
                    _token :$("input[name=_token]").val(),
                    type   :'get_wait_register_customer_info',
                    key    :$("input[name=cust_id]").val(),
                    tip    :'',
                    project:$("input[name=cust_project]").val()
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
                                        if (v2=='')
                                        {
                                            $("input[name=cust_si_id]").removeAttr('readonly');
                                        }else
                                        {
                                            $("input[name=cust_si_id]").attr('readonly','readonly');
                                        }

                                        $("input[name=cust_si_id]").val(v2);
                                    }

                                    if (k2=='sex')
                                    {
                                        $("input[name=cust_sex]").val(v2);
                                    }

                                    if (k2=='bank')
                                    {
                                        if (v2=='')
                                        {
                                            $("input[name=cust_bank_num]").removeAttr('readonly');
                                        }else
                                        {
                                            $("input[name=cust_bank_num]").attr('readonly','readonly');
                                        }

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
                                tip    :'pid',
                                project:$("input[name=cust_project]").val()
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
                    tip    :'',
                    project:$("input[name=cust_project]").val()
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
                                        if (v2=='')
                                        {
                                            $("input[name=cust_si_id]").removeAttr('readonly');
                                        }else
                                        {
                                            $("input[name=cust_si_id]").attr('readonly','readonly');
                                        }

                                        $("input[name=cust_si_id]").val(v2);
                                    }

                                    if (k2=='sex')
                                    {
                                        $("input[name=cust_sex]").val(v2);
                                    }

                                    if (k2=='bank')
                                    {
                                        if (v2=='')
                                        {
                                            $("input[name=cust_bank_num]").removeAttr('readonly');
                                        }else
                                        {
                                            $("input[name=cust_bank_num]").attr('readonly','readonly');
                                        }

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
                                tip    :'pid',
                                project:$("input[name=cust_project]").val()
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
                    tip    :'cust_name',
                    project:$("input[name=cust_project]").val()
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
                                        if (v2=='')
                                        {
                                            $("input[name=cust_si_id]").removeAttr('readonly');
                                        }else
                                        {
                                            $("input[name=cust_si_id]").attr('readonly','readonly');
                                        }

                                        $("input[name=cust_si_id]").val(v2);
                                    }

                                    if (k2=='sex')
                                    {
                                        $("input[name=cust_sex]").val(v2);
                                    }

                                    if (k2=='bank')
                                    {
                                        if (v2=='')
                                        {
                                            $("input[name=cust_bank_num]").removeAttr('readonly');
                                        }else
                                        {
                                            $("input[name=cust_bank_num]").attr('readonly','readonly');
                                        }

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
                                tip    :'pid',
                                project:$("input[name=cust_project]").val()
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

        });

    </script>

@stop