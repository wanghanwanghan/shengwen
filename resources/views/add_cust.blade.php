@extends('layouts.dashboard')
@section('page_heading','欢迎登陆声纹认证系统')
@section('page_heading_small','用户登记页')
@section('section')

    <form id="add_cust_form">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">用户登记&nbsp&nbsp<span style="color: red;border: solid red 1px;">A类</span></h3>
            <button type="button" class="btn btn-box-tool" data-widget="collapse" style="float: right">
                <i class="fa fa-minus"></i>
            </button>
        </div>
        <div class="box-body">
            <table class="table table-bordered">
                <tbody>
                <tr>
                    <td>
                        <div>
                            <input type="text" class="form-control" name="cust_name" placeholder="姓名">
                        </div>
                    </td>
                    <td>
                        <div>
                            <input type="text" class="form-control" name="cust_review_num" placeholder="手机号码">
                        </div>
                    </td>
                    <td onclick="select_project();">
                        <div>
                            <span id="parentIframe">redis启动失败</span>
                            <input type="hidden" name="cust_project">
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div>
                            <input type="text" class="form-control" name="cust_id" placeholder="身份证号">
                        </div>
                    </td>
                    <td>
                        <div>
                            <input type="text" class="form-control" name="cust_phone_num" placeholder="备用手机">
                        </div>
                    </td>
                    <td>
                        <div>
                            <select style="padding-left: 8px" name="cust_confirm_type" class="form-control">
                                @foreach($confirm_type as $v)
                                    <option>{{$v}}</option>
                                @endforeach
                            </select>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div>
                            <input type="text" class="form-control" name="cust_si_id" placeholder="社保编号">
                        </div>
                    </td>
                    <td>
                        <div>
                            <input type="text" class="form-control" name="cust_address" placeholder="地址信息">
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
                <tr>
                    <td align="center" colspan="3">
                        <a style="width: 100px;" onclick="add_cust_A();" class="btn btn-block btn-primary btn-sm">登记</a>
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
            <tr>
            <td height="53" colspan="8" align="center">
                <input style="text-align: center" name="cust_review_num" class="input" size="15" type="text" placeholder="年审手机">
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

        $(function () {

            //给身份证输入框绑定change事件
            $("input[name=cust_id]").on('change',function () {

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

            });

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
