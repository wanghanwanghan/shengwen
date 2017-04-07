@extends('layouts.dashboard')
@section('page_heading','声纹管理')
@section('page_heading_small','循环拨打电话进行用户认证')
@section('section')

    <div class="box">
        <div class="box-header">
            <div class="col-sm-12">

                <div class="row">

                    <form id="loop_form">

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="col-sm-2">
                            <input class="form-control layer-date" readonly type="text" id="" onclick="laydate({istoday:false,isclear:false,issure:false,choose:function(){}});" name="star_date" placeholder="开始时间"/>
                        </div>

                        <div class="col-sm-2">
                            <input class="form-control layer-date" readonly type="text" id="" onclick="laydate({istoday:false,isclear:false,issure:false,choose:function(){}});" name="stop_date" placeholder="结束时间"/>
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
                            <select class="form-control" name="cust_type" style="padding-left: 8px;">
                                <option value="A">客户类型-默认A类</option>
                                <option value="B">B类</option>
                            </select>
                        </div>

                        <div class="col-sm-2">
                            <a style="width: 100px;height: 100%;" onclick="start_loop();" class="btn btn-block btn-primary btn-sm">开始认证</a>
                        </div>

                    </form>

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
                        <table id="loop_table" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
                            <thead>
                            <th style="text-align: center">操作员</th>
                            <th style="text-align: center">动作</th>
                            <th style="text-align: center">属地</th>
                            <th style="text-align: center">参保</th>
                            <th style="text-align: center">类型</th>
                            <th style="text-align: center">认证时间段</th>
                            <th style="text-align: center">结果</th>
                            <th style="text-align: center">结果信息</th>
                            <th style="text-align: center">操作时间</th>
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
                        <div id="loop_laypage" class="pagination pagination-sm no-margin pull-right">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>

    <script>

        $(function () {
            get_loop_mongo_data(1);
        })

    </script>


@stop
