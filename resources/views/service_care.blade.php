@extends('layouts.dashboard')
@section('page_heading','数据统计')
@section('page_heading_small','认证结果统计')
@section('section')

    <div class="box">
        <div class="box-header">
            <div class="col-sm-12">
                <div class="row">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#service_care_1" data-toggle="tab" aria-expanded="true">通过条件查询</a></li>
                            <li class=""><a href="#service_care_2" data-toggle="tab" aria-expanded="false">通过身份证或手机号查询</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="service_care_1" style="height: 40px;">
                                <span id="service_care_1_span">
                                    <form id="service_care_form">
                                        <div class="col-sm-1">
                                            <input class="form-control layer-date" readonly type="text" id="" onclick="laydate({istoday:false,isclear:false,issure:false,choose:function(){service_care_change();}});" name="star_date" placeholder="开始时间"/>
                                        </div>
                                        <div class="col-sm-1">
                                            <input class="form-control layer-date" readonly type="text" id="" onclick="laydate({istoday:false,isclear:false,issure:false,choose:function(){service_care_change();}});" name="stop_date" placeholder="结束时间"/>
                                        </div>

                                        <div class="col-sm-2" onclick="select_project();">
                                            <span id="parentIframe">redis启动失败</span>
                                            <input type="hidden" name="cust_project">
                                        </div>

                                        <div class="col-sm-2">
                                            <select class="form-control" name="cust_si_type" style="padding-left: 8px;">
                                                @foreach($staff_si_type as $k=>$v)
                                                    <option value={{$k}}>{{$v}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-sm-2">
                                            <select class="form-control" name="confirm_res" style="padding-left: 8px;">
                                                <option value="0">请选择认证结果</option>
                                                <option value="Y">已通过</option>
                                                <option value="N">未通过</option>
                                            </select>
                                        </div>

                                        <div class="col-sm-2">
                                            <select class="form-control" name="vv_or_fv" style="padding-left: 8px;">
                                                <option value="all">认证类型-默认全部</option>
                                                <option value="vocalvena">声纹</option>
                                                <option value="fingervena">指静脉</option>
                                            </select>
                                        </div>

                                        <div class="col-sm-2">
                                            <select class="form-control" name="cust_type" style="padding-left: 8px;">
                                                <option value="0">客户类型-默认全部</option>
                                                <option value="A">A类</option>
                                                <option value="B">B类</option>
                                            </select>
                                        </div>

                                    </form>
                                </span>
                            </div>
                            <div class="tab-pane" id="service_care_2" style="height: 40px;">
                                <span id="service_care_2_span">
                                    <form id="service_care_form_1">
                                        <div class="col-sm-2">
                                            <input type="text" class="form-control" name="cond" placeholder="身份证或手机号">
                                        </div>
                                    </form>
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                <div class="row">
                    <div class="col-sm-6">

                    </div>
                    <div class="col-sm-6">

                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <table id="service_care_table" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
                            <thead>
                            <th style="text-align: center">姓名</th>
                            <th style="text-align: center">身份证</th>
                            <th style="text-align: center">认证电话</th>
                            <th style="text-align: center">备用电话</th>
                            <th style="text-align: center">类型</th>
                            <th style="text-align: center">时间</th>
                            <th style="text-align: center">结果</th>
                            <th style="text-align: center">备注</th>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-5">
                        <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">一共<<span style="color: red;" id="data_total">0</span>>条记录，超级管理员开始<a href="{{url('allocation')}}">分配</a>，或者<a href="javascript:void(0);" id="daochu" name="xxx" onclick="daochushuju($(this).attr('name'));">导出&nbsp&nbsp&nbsp</a>
                            <span id="excel_file_download"></span>
                        </div>
                    </div>
                    <div class="col-sm-7">
                        <div id="service_care_laypage" class="pagination pagination-sm no-margin pull-right">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>


    <script>

        $(function () {

            var url1 ='/data/ajax';
            var data1={
                _token :$("input[name=_token]").val(),
                type   :'get_redis'
            };
            $.post(url1,data1,function (response) {

                $("#parentIframe").html(response.res);
                $("input[name=cust_project]").val(response.res1);

            },'json');


        });

        function daochushuju(redis_key) {

            if (redis_key=='xxx')
            {
                layer.msg('还没有查询出来数据，不能导出');
            }else
            {
                var url ='/data/ajax';
                var data={
                    _token:$("input[name=_token]").val(),
                    type  :'daochushuju',
                    key   :redis_key
                };

                $.post(url,data,function (response) {

                    $("#excel_file_download").children().remove();

                    if (response.error=='0')
                    {
                        layer.msg(response.msg);
                        var lable_a=$("<a href="+response.file_name+" download='导出的数据.xls'>可以下载了</a>");
                        $("#excel_file_download").append(lable_a);
                    }else
                    {
                        layer.msg(response.msg);
                    }


                },'json')
            }
        }

        $("select[name=cust_project]").change(function () {
            service_care_change();
        });

        $("select[name=cust_si_type]").change(function () {
            service_care_change();
        });

        $("input[name=cond]").change(function () {
            service_care_change_1();
        });

        $("select[name=confirm_res]").change(function () {
            service_care_change();
        });

        $("select[name=cust_type]").change(function () {
            service_care_change();
        });

        $("select[name=vv_or_fv]").change(function () {
            service_care_change();
        });


    </script>


@stop
