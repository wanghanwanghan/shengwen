@extends('layouts.dashboard')
@section('page_heading','超级管理员功能')
@section('page_heading_small','查看员工列表')
@section('section')

    <div class="box">
        <div class="box-header">

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
                        <table id="show_staff_list_table" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
                            {{csrf_field()}}
                            <thead>
                            <th style="text-align: center">员工账号</th>
                            <th style="text-align: center">员工姓名</th>
                            <th style="text-align: center">所属区域</th>
                            <th style="text-align: center">参保类型</th>
                            <th style="text-align: center">员工权限</th>
                            <th style="text-align: center">登陆权限</th>
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
                        <div id="show_staff_list_laypage" class="pagination pagination-sm no-margin pull-right">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>

    <script>

        $(function () {
            show_staff_list(1);
        })

    </script>


@stop
