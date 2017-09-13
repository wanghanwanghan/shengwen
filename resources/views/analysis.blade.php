@extends('layouts.dashboard')
@section('page_heading','声纹管理')
@section('page_heading_small','分析')
@section('section')

    <link rel="stylesheet" href="{{asset('public/bower_components/AdminLTE/plugins/morris/morris.css')}}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="{{asset('public/bower_components/AdminLTE/plugins/morris/morris.js')}}"></script>

    <div class="box box-info">
        <div class="box-header with-border">

            <form id="analysis_form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="col-sm-2" onclick="select_project();">
                    <span id="parentIframe">redis启动失败</span>
                    <input type="hidden" name="cust_project">
                </div>
                <div class="col-sm-2">
                    <input class="form-control layer-date" readonly type="text" id="" onclick="laydate({istoday:false,isclear:false,issure:false,choose:function(){analysis_change();}});" name="year_and_month" placeholder="选择年/月(不支持日)"/>
                </div>
                <div class="col-sm-2">
                    <select style="padding-left: 8px" name="vv_or_fv" class="form-control">
                        <option value="1">声纹</option>
                        <option value="2">指静脉</option>
                    </select>
                </div>
            </form>

            <div class="col-sm-3">
                <div style="padding-top: 8px;">当前月份一共<<span style="color: red;" id="data_total">0</span>>条记录</div>
            </div>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body chart-responsive">
            <div class="chart" id="line-chart" style="height: 300px;">

            </div>
        </div>
    </div>

    <script>

        $(function () {

            $("select[name=vv_or_fv]").on('change',function () {
                analysis_change();
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

        });

    </script>

@stop
