@extends('layouts.dashboard')
@section('page_heading','声纹管理')
@section('page_heading_small','修改客户信息')
@section('section')


    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#modify_cust_info_1" data-toggle="tab" aria-expanded="true">通过条件查询</a></li>
            <li class=""><a href="#modify_cust_info_2" data-toggle="tab" aria-expanded="false">备用</a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="modify_cust_info_1" style="height: auto;">

                <!--1.通过手机号查询-->
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="box">
                    <div class="box-header">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-2">
                                    <input class="form-control" type="text" id="" onclick="" name="" placeholder="输入年审号码"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box-body">

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

                    </div>
                </div>
                <!--1.终了----------->

            </div>
            <div class="tab-pane" id="modify_cust_info_2" style="height: auto;">
                <span>
                    <a href="#" id="username1" data-type="text" data-title="用户名">用户名</a>
                </span>
            </div>
        </div>
    </div>

    <script>
        $(function () {

        });
    </script>


@stop
