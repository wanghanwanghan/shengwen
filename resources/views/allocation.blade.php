@extends('layouts.dashboard')
@section('page_heading','超级管理员功能')
@section('page_heading_small','分配认证未通过数据')
@section('section')

    <div class="box">
        <div class="box-header">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-2">
                        <span><b>选择需要分配的数据：</b></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="box-body">
            <div class="col-sm-12">
                <div class="row">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <form id="allocation_form">
                        <div class="col-sm-2">
                            <input class="form-control layer-date" readonly type="text" id="" onclick="laydate({istoday:false,isclear:false,issure:false,choose:function(){allocation_change();}});" name="star_date" placeholder="开始时间"/>
                        </div>

                        <div class="col-sm-2">
                            <input class="form-control layer-date" readonly type="text" id="" onclick="laydate({istoday:false,isclear:false,issure:false,choose:function(){allocation_change();}});" name="stop_date" placeholder="结束时间"/>
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
                                <option value="N">认证结果-未通过</option>
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
                </div>
            </div>
        </div>
    </div>







    <div class="box">
        <div class="box-header">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-3">
                        <span><b>选择得到这些数据的员工：</b></span>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="row">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </div>
            </div>
        </div>

        <div class="box-body">
            <div class="row">
                <div class="col-sm-12">
                    <form id="staff_form">
                    <table id="allocation_table" class="table table-bordered table-hover dataTable">
                        <thead>
                        <th style="text-align: center">员工姓名</th>
                        <th style="text-align: center">员工账号</th>
                        <th style="text-align: center">分配</th>
                        <th style="text-align: center">进行中的任务</th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div id="allocation_laypage" class="pagination pagination-sm no-margin pull-right">
                    </div>
                </div>
            </div>
        </div>
    </div>







    <div style="background-color: white;height: 30px;">
        <div class="col-sm-1"></div>
        <div class="col-sm-4">
            <span><input id="myslider1" type="text"/></span>
        </div>
        <div class="col-sm-4">
            <span>分配数据条数: <span id="myslider1_count">0</span></span>
        </div>
        <div class="col-sm-2">
            <button style="width: 100px;" type="button" onclick="allocation();" class="btn btn-block btn-primary btn-sm">分配</button>
        </div>
    </div>

    <script>

        $(function () {
            getMySlider1({
                min:0,
                max:0,
                step:1,
                value:0
            });
        });

        $("select[name=cust_project]").change(function () {
            allocation_change();
        });

        $("select[name=cust_si_type]").change(function () {
            allocation_change();
        });

//        $("input[name=cond]").change(function () {
//            service_care_change_1();
//        });

        $("select[name=confirm_res]").change(function () {
            allocation_change();
        });

        $("select[name=cust_type]").change(function () {
            allocation_change();
        });

    </script>


@stop

