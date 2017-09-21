@extends('layouts.dashboard')
@section('page_heading','欢迎登陆指静脉认证系统')
@section('page_heading_small','用户认证页')
@section('section')

    <p id="cert_message" style="display: none;"></p>
    <p id="cert_message_type" style="display: none;"></p>

    <form id="add_cust_form">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">指静脉用户认证</h3>
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
                            <a id="OPENfv" style="width: 100px;display: none" onclick='fpVerification("指静脉比对","请安装指静脉驱动或启动服务",true,globalContext)' class="btn btn-block btn-primary btn-sm">指静脉认证</a>
                            <a id="CLOSEfv" style="width: 150px;display: block" class="btn btn-block btn-warning btn-sm">请先输入身份证号码</a>
                        </td>
                        <td align="center">
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </form>

    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">当日认证的用户</h3>
            <button type="button" class="btn btn-box-tool" data-widget="collapse" style="float: right">
                <i class="fa fa-minus"></i>
            </button>
        </div>
        <div class="box-body">

            <div id="comparisonDiv" class="zhijingmai_box" style="display: none">
                <h2>指静脉比对</h2>
                <div class="list">
                    <canvas id="canvasComp" width="430" height="320" style="background:url({{asset('public/img/base_fpVerify.jpg')}}) rgb(243, 245, 240)"></canvas>
                    <input type="button" value="关闭" onclick='closeCompa()' />
                </div>
            </div>

            <div id="bg" style="display: none;"></div>

            <div hidden>
                <fieldset style="width:530px">
                    <legend>指静脉比对的模板数据</legend>
                    <textarea rows="22" cols="70" id="fpVerify"></textarea>
                </fieldset>
            </div>

            <form id="fvId_and_fvTemplate">
                <input type="hidden" id="verifyTemplate" name="my_fvTemplate" />
                <input type="hidden" id="verifyTemplate_myfp" name="my_fpTemplate" />
            </form>

            <div>
                <table class="table table-bordered" id="fv_match_table">
                    <thead>
                    <th style="text-align: center">姓名</th>
                    <th style="text-align: center">身份证</th>
                    <th style="text-align: center">指静脉</th>
                    <th style="text-align: center">指纹</th>
                    <th style="text-align: center">手指</th>
                    <th style="text-align: center">手机号码</th>
                    <th style="text-align: center">备用电话</th>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-sm-5">
                    <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">一共<<span style="color: red;" id="data_total">0</span>>条记录</div>
                </div>
                <div class="col-sm-7">
                    <div id="fv_match_laypage" class="pagination pagination-sm no-margin pull-right">
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>

        $(function () {

            myfunction();
            fv_match_refresh();

            var mytimer=setInterval(function () {

                //修改指静脉登记类中的属性
                var url ='/data/ajax';
                var data={
                    _token :$("input[name=_token]").val(),
                    type   :'fv_match',
                    key    :$("#fvId_and_fvTemplate").serializeArray(),
                    cust_id:$("#certNumber").val()
                };
                $.post(url,data,function (response) {

                    if (response.error=='0' || response.error=='1')
                    {
                        layer.msg(response.msg,{time:2000});
                        clearInterval(mytimer);
                        setTimeout(function () {
                            location.reload();
                        },2500);
                    }else
                    {

                    }

                },'json');

            },1500);

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

            //给身份证输入框绑定事件
            $("#certNumber").on('change',function () {

                var urla ='/data/ajax';
                var dataa={
                    _token :$("input[name=_token]").val(),
                    type   :'get_wait_register_customer_fv_info',
                    key    :$("#certNumber").val()
                };
                $.post(urla,dataa,function (response) {

                    if (response.error=='0')
                    {
                        $("#OPENfv").css('display','block');
                        $("#CLOSEfv").css('display','none');
                    }else
                    {
                        $("#OPENfv").css('display','none');
                        $("#CLOSEfv").css('display','block');
                    }

                    layer.msg(response.msg);

                },'json');

            })

        });

    </script>


@stop
