@extends('layouts.dashboard')
@section('page_heading','欢迎登陆社会养老保险领取资格认证平台')
@section('page_heading_small','用户认证页')
{{--@section('page_heading_small','用户登记页')--}}
@section('section')

    <p id="cert_message" style="display: none;"></p>
    <p id="cert_message_type" style="display: none;"></p>

    <form id="add_cust_form">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">指静脉用户认证</h3>
                {{--<h3 class="box-title">指静脉用户登记</h3>--}}
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
                                <input type="text" class="form-control" name="cust_phone_num" placeholder="联系电话">
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
                                <input type="text" class="form-control" name="cust_phone_bku" placeholder="备用号码">
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
                        <td style="width: 190px;">
                            <div>
                                <select style="padding-left: 8px" name="cust_confirm_type" class="form-control">
                                    <option>指静脉</option>
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
                                <a style="width: 100px;" onclick="open_fv_page();" class="btn btn-block btn-primary btn-sm">认证指静脉</a>
                                {{--<a style="width: 100px;" onclick='$("#fvRegister").click();' class="btn btn-block btn-primary btn-sm">采集指静脉</a>--}}
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
                                <a id="sub_info" style="width: 100px;" onclick="add_fv_cust();" class="btn btn-block btn-success btn-sm">提交信息</a>
                                <a style="width: 100px;display: none;" id="add_second_ready" name="no_person" class="btn btn-block btn-primary btn-sm"></a>
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


    <div style="display: none">
        <form id="fvId_or_fvTemplate">
            <textarea id="myFV_0" name="myFV_0"></textarea>
            <textarea id="myFP_0" name="myFP_0"></textarea>
            <textarea id="myFV_1" name="myFV_1"></textarea>
            <textarea id="myFP_1" name="myFP_1"></textarea>
            <textarea id="myFV_2" name="myFV_2"></textarea>
            <textarea id="myFP_2" name="myFP_2"></textarea>
            <textarea id="myFV_3" name="myFV_3"></textarea>
            <textarea id="myFP_3" name="myFP_3"></textarea>
            <textarea id="myFV_4" name="myFV_4"></textarea>
            <textarea id="myFP_4" name="myFP_4"></textarea>
            <textarea id="myFV_5" name="myFV_5"></textarea>
            <textarea id="myFP_5" name="myFP_5"></textarea>
            <textarea id="myFV_6" name="myFV_6"></textarea>
            <textarea id="myFP_6" name="myFP_6"></textarea>
            <textarea id="myFV_7" name="myFV_7"></textarea>
            <textarea id="myFP_7" name="myFP_7"></textarea>
            <textarea id="myFV_8" name="myFV_8"></textarea>
            <textarea id="myFP_8" name="myFP_8"></textarea>
            <textarea id="myFV_9" name="myFV_9"></textarea>
            <textarea id="myFP_9" name="myFP_9"></textarea>
        </form>
    </div>
    {{--@include('layouts.zhijingmai1')--}}

    <script>

        $(function () {

//            setInterval(function () {
//
//                //修改指静脉登记类中的属性
//                var url ='/data/ajax';
//                var data={
//                    _token :$("input[name=_token]").val(),
//                    type   :'modify_fv_class_attr',
//                    key    :$("#fvId_or_fvTemplate").serializeArray()
//                };
//                $.post(url,data,function (response) {
//
//                    if (response.error=='0')
//                    {
//                        $("#coldata").children().remove();
//
//                        $.each(response.data,function (k,v) {
//
//                            var myspan=$("<span style='width: 100px;margin-left: 2px;' class='btn btn-success btn-sm'></span>");
//                            myspan.html(v);
//                            $("#coldata").append(myspan);
//
//                        });
//
//                    }else
//                    {
//                        $("#coldata").children().remove();
//                    }
//
//                },'json');
//
//            },1500);

            //给身份证输入框绑定change事件
            $("input[name=cust_id]").on('change',function () {

                //***事件一开始*****************************************************************************************
                var url ='/data/ajax';
                var data={
                    _token :$("input[name=_token]").val(),
                    type   :'get_wait_register_customer_info',
                    key    :$("input[name=cust_id]").val(),
                    tip    :'add_fv',
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

                                    if (k2=='cust_phone_num')
                                    {
                                        $("input[name=cust_phone_num]").val(v2);
                                    }

                                    if (k2=='cust_phone_bku')
                                    {
                                        $("input[name=cust_phone_bku]").val(v2);
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
                                    $.each(response.data[0],function (k2,v2) {
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

                $("#fvId").val('');
                $("#fvTemplate10").val('');
                $("#fingerId").val('');
                $("#fingerTemplate10").val('');

                //***事件一结束*****************************************************************************************

                //***事件二开始*****************************************************************************************
                var url1 ='/data/ajax';
                var data1={
                    _token :$("input[name=_token]").val(),
                    type   :'get_finger_mongo_data',
                    key    :$("input[name=cust_id]").val()
                };
                $.post(url1,data1,function (response) {

                    if (response.error=='0')
                    {
                    }else if(response.error=='1')
                    {
                    }else if(response.error=='2')
                    {
                        $("#fvId").val(response.fv_id);
                        $("#fvTemplate10").val(response.fv_tm);
                        $("#fingerId").val(response.fp_id);
                        $("#fingerTemplate10").val(response.fp_tm);
                    }else
                    {
                    }

                },'json');
                //***事件二结束*****************************************************************************************

            });









            //取得redis中的地区信息
            var url ='/data/ajax';
            var data={
                _token :$("input[name=_token]").val(),
                type   :'get_redis'
            };
            $.post(url,data,function (response) {

                $("#parentIframe").html(response.res);
                $("input[name=cust_project]").val(response.res1);

            },'json');

        });

        function open_fv_page() {

            layer.open({
                type: 2,
                title: '',
                maxmin:false,//是否显示最大化最小化按钮
                resize:false,//窗口是否可以拉伸
                shadeClose: true, //点击遮罩关闭层
                scrollbar:false,//是否允许显示滚动条
                fixed:true,
                area:['1020px','570px'],
                content: '/open/fv/page?idcard='+$("#certNumber").val()
//                cancel: function(index, layero)
//                {
//                    alert('wanghan');
//                    if(confirm('确定要关闭么'))
//                    {
//                        //只有当点击confirm框的确定时，该层才会关闭
//                        layer.close(index)
//                    }
//
//                    return false;
//                }
            });

        }
        


    </script>


@stop
