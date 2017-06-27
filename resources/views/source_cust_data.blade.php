@extends('layouts.dashboard')
@section('page_heading','超级管理员功能')
@section('page_heading_small','导入待采集客户信息')
@section('section')

    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">请认真填写</h3>
                    </div>
                    @include('layouts.msg')
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" action="{{url('/import1')}}" method="post" enctype="multipart/form-data">

                        {{csrf_field()}}
                        <div class="box-body">
                            {{--<div class="form-group">--}}
                                {{--<div class="col-sm-12">--}}
                                    {{--<div class="row">--}}
                                        {{--<div class="col-sm-4">--}}
                                            {{--<label>选择<县></label>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-sm-4">--}}
                                            {{--<label>选择<镇></label>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-sm-4">--}}
                                            {{--<label>选择<村></label>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="row">--}}
                                        {{--<div class="col-sm-4">--}}
                                            {{--<select style="padding-left: 8px" name="county" class="form-control">--}}
                                            {{--</select>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-sm-4">--}}
                                            {{--<select style="padding-left: 8px" name="town" class="form-control">--}}
                                            {{--</select>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-sm-4">--}}
                                            {{--<select style="padding-left: 8px" name="village" class="form-control">--}}
                                            {{--</select>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <p class="help-block">选择导入文件（文件后缀是.xls，不是手动改，而是用另存为.xls）</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <input type="file" name="myfile">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <p class="help-block">如果对导入文件的格式或者内容有疑问，请联系管理员</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="checkbox">
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label>
                                                <p class="help-block"><input type="checkbox" name="check"> 是否检查没问题，确定要导入？</p>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">导入</button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>



    <script>

        $(function () {

            var url ='/data/ajax';
            var data={
                _token :$("input[name=_token]").val(),
                type   :'get_china_all_position'
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
