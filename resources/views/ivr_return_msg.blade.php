@extends('layouts.dashboard')
@section('page_heading','操作日志')
@section('page_heading_small','登记声纹返回信息')
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
                        <table id="loop_table" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
                            <thead>
                            <th style="text-align: center">操作员</th>
                            <th style="text-align: center">动作</th>
                            <th style="text-align: center">结果</th>
                            <th style="text-align: center">结果信息</th>
                            <th style="text-align: center">客户录音</th>
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
            get_register_verify_mongo_data(1);
        })

    </script>


@stop
