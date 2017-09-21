@extends('layouts.dashboard')
@section('page_heading','欢迎登陆指静脉认证系统')
@section('page_heading_small','用户登记页')
@section('section')

    <p id="cert_message" style="display: none;"></p>
    <p id="cert_message_type" style="display: none;"></p>

    <form id="add_cust_form">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">指静脉用户登记</h3>
                <button type="button" class="btn btn-box-tool" data-widget="collapse" style="float: right">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
            <div class="box-body">
                <table class="table table-bordered">
                    <tbody>
                    <tr height="60px">
                        <td>
                            <div>
                                <input type="text" class="form-control" id="personName" name="cust_name" placeholder="姓名">
                            </div>
                        </td>
                        <td>
                            <div>
                                <input type="text" class="form-control" name="cust_phone_num" placeholder="手机号码">
                            </div>
                        </td>
                        <td width="25%" onclick="select_project();">
                            <div>
                                <span id="parentIframe">redis启动失败</span>
                                <input type="hidden" name="cust_project">
                            </div>
                        </td>
                        <td rowspan="4" width="240px">
                            <div id="localImag">
                                <img width="200px" height="240px" name="cust_photo" id="id_img_pers" src="{{asset('public/img/userImage.png')}}" onerror="{{asset('public/img/userImage.png')}}">
                            </div>
                        </td>
                    </tr>
                    <tr height="60px">
                        <td>
                            <div>
                                <input type="text" class="form-control" id="certNumber" name="cust_id" placeholder="身份证号">
                            </div>
                        </td>
                        <td>
                            <div>
                                <input type="text" class="form-control" name="cust_phone_bku" placeholder="备用手机">
                            </div>
                        </td>
                        <td>
                            <div>
                                <select style="padding-left: 8px" name="cust_confirm_type" class="form-control">
                                    <option>指静脉</option>
                                </select>
                            </div>
                        </td>
                    </tr>
                    <tr height="60px">
                        <td>
                            <div>
                                <input type="text" class="form-control" name="cust_si_id" placeholder="社保编号">
                            </div>
                        </td>
                        <td>
                            <div>
                                <input type="text" class="form-control" id="address" name="cust_address" placeholder="地址信息">
                            </div>
                        </td>
                        <td>
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
                        <td align="center">
                            <a style="width: 100px;" id="button_readID" onclick="new Device().startFun();" class="btn btn-block btn-primary btn-sm">读取身份证</a>
                        </td>
                        <td align="center">
                            <a style="width: 100px;" onclick='$("#fvRegister").click();' class="btn btn-block btn-primary btn-sm">采集指静脉</a>
                        </td>
                        <td align="center">
                            <a id="sub_info" style="width: 100px;" onclick="add_fv_cust();" class="btn btn-block btn-success btn-sm">提交信息</a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </form>

    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">采集指静脉信息</h3>
            <button type="button" class="btn btn-box-tool" data-widget="collapse" style="float: right">
                <i class="fa fa-minus"></i>
            </button>
        </div>
        <div class="box-body">

            <div id="fvRegisterDiv" style="display: none;">
                <a id="fvRegister" onclick='submitFVRegister("指静脉","指静脉数:","确认保存当前修改吗？","驱动下载", false)' title="请安装指静脉驱动或启动该服务" class="showGray" onmouseover="this.className='showGray'">注册</a>
            </div>

            <div id="bg" style="display: none;"></div>

            <div id="zhijingmai_box" class="zhijingmai_box" style="display: none;">
                <h2>指静脉登记</h2>
                <div class="list">
                    <canvas id="canvas" width="430px" height="450px" style="background: rgb(243, 245, 240)"></canvas>
                    <input type="hidden" id="whetherModify" name="whetherModify" alt="" value="111" />

                    <div style="position: absolute; left: 310px; top: 325px; width: 70px; height: 28px;">
                        <button type="button" id="submitButtonId" name="makeSureName" onclick="mysubmitEvent();" class="button-form">确定</button>
                    </div>
                    <div style="position: absolute; left: 310px; top: 365px; width: 70px; height: 28px;">
                        <button class="button-form" type="button" id="closeButton" name="closeButton" onclick='cancelEvent("确认保存当前修改吗?", "指静脉数:");'>取消</button>
                    </div>
                </div>
            </div>

            <div>
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <td width="15%" style="text-align: center">
                            已经采集：
                        </td>
                        <td id="coldata">

                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div hidden>
                <form id="fvId_or_fvTemplate">
                    <fieldset style="width:130px" id="t">
                        <legend>三枚指静脉的id</legend>
                        <textarea rows="1" cols="70" id="fvId" name="my_fvID"></textarea>
                    </fieldset>
                    <fieldset style="width:530px" id="te">
                        <legend>三枚指静脉模板数据</legend>
                        <textarea rows="1" cols="70" id="fvTemplate10" name="my_fvTemplate"></textarea>
                    </fieldset>

                    <fieldset style="width:130px" id="t">
                        <legend>指纹的id</legend>
                        <textarea rows="1" cols="70" id="fingerId" name="my_fpID"></textarea>
                    </fieldset>
                    <fieldset style="width:530px" id="te">
                        <legend>指纹模板数据</legend>
                        <textarea rows="1" cols="70" id="fingerTemplate10" name="my_fpTemplate"></textarea>
                    </fieldset>
                </form>
            </div>

        </div>
    </div>

    <script>

        $(function () {

            myfunction();

            setInterval(function () {

                //修改指静脉登记类中的属性
                var url ='/data/ajax';
                var data={
                    _token :$("input[name=_token]").val(),
                    type   :'modify_fv_class_attr',
                    key    :$("#fvId_or_fvTemplate").serializeArray()
                };
                $.post(url,data,function (response) {

                    if (response.error=='0')
                    {
                        $("#coldata").children().remove();

                        $.each(response.data,function (k,v) {

                            var myspan=$("<span style='width: 100px;margin-left: 2px;' class='btn btn-success btn-sm'></span>");
                            myspan.html(v);
                            $("#coldata").append(myspan);

                        });

                    }else
                    {
                        $("#coldata").children().remove();
                    }

                },'json');

            },1500);

            //给身份证输入框绑定change事件
            $("input[name=cust_id]").on('change',function () {

                //***事件一开始*****************************************************************************************
                var url ='/data/ajax';
                var data={
                    _token :$("input[name=_token]").val(),
                    type   :'get_wait_register_customer_info',
                    key    :$("input[name=cust_id]").val()
                };
                $.post(url,data,function (response) {

                    if (response.error=='0')
                    {
                        $.each(response.data,function (k,v) {

                            if (k=='name')
                            {
                                $("input[name=cust_name]").val(v);
                            }

                            if (k=='sicard')
                            {
                                $("input[name=cust_si_id]").val(v);
                            }

                            if (k=='sitype')
                            {
                                $("select[name=cust_si_type]").val(v);
                            }

                        });

                        layer.msg(response.msg);

                    }else
                    {
                        layer.msg(response.msg);
                    }

                },'json');
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

                    layer.msg(response.msg);

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
        
        function mysubmitEvent() {
            submitEvent();
        }

    </script>


@stop
