@extends('layouts.dashboard')
@section('page_heading','声纹管理')
@section('page_heading_small','循环拨打电话进行用户认证')
@section('section')

    <div class="box">
        <div class="box-header">
            <div class="col-sm-12">

                <div class="row">

                    <form id="statistics_form">

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

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
                            <select class="form-control" name="cust_register_flag" style="padding-left: 8px;">
                                <option value="1">已注册</option>
                                <option value="0">未注册</option>
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
                        <table id="statistics_table" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
                            <thead>
                            <th style="text-align: center">属地</th>
                            <th style="text-align: center">参保</th>
                            <th style="text-align: center">姓名</th>
                            <th style="text-align: center">身份证</th>
                            <th style="text-align: center">社保号</th>
                            <th style="text-align: center">认证电话</th>
                            <th style="text-align: center">备用电话</th>
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
                        <div id="statistics_laypage" class="pagination pagination-sm no-margin pull-right">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>

    <script>

        $(function () {

            var url ='/api/loop/call';
            var data={
                _token:$("input[name=_token]").val(),
                type  :'loop_call'
            };

            $.post(url,data,function (response) {

                if(response.error=='0')
                {
                    layer.msg(response.msg);
                }else
                {
                    layer.msg(response.msg);
                }

            },'json');

        })

    </script>


@stop
