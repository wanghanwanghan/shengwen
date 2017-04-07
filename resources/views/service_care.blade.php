@extends('layouts.dashboard')
@section('page_heading','声纹管理')
@section('page_heading_small','客户维护')
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

                        <div class="col-sm-2">
                            <input class="form-control layer-date" readonly type="text" id="" onclick="laydate({istoday:false,isclear:false,issure:false,choose:function(){service_care_change();}});" name="star_date" placeholder="开始时间"/>
                        </div>

                        <div class="col-sm-2">
                            <input class="form-control layer-date" readonly type="text" id="" onclick="laydate({istoday:false,isclear:false,issure:false,choose:function(){service_care_change();}});" name="stop_date" placeholder="结束时间"/>
                        </div>

                        <div class="col-sm-2">
                            <select class="form-control" name="cust_project" style="padding-left: 8px;">
                                @foreach($staff_project as $k=>$v)
                                    <option value={{$k}}>{{$v}}</option>
                                @endforeach
                            </select>
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
                                <option value="0">认证结果-默认全部</option>
                                <option value="Y">已通过</option>
                                <option value="N">未通过</option>
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
                        <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">一共<<span style="color: red;" id="data_total">0</span>>条记录</div>
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

    </script>


@stop
