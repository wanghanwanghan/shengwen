@extends('layouts.dashboard')
@section('page_heading','系统设置')
@section('page_heading_small','设置参保类型')
@section('section')

    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">请认真填写</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form id="add_si_type_form" class="form-horizontal">
            <div class="box-body">

                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">参保类型</label>

                    <div class="col-sm-10">
                        <input style="padding-left: 16px;" type="text" class="form-control" id="inputEmail3" name="si_name" placeholder="参保类型">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">已经有的参保类型</label>

                    <div id="add_si_page_si_type" class="col-sm-10">

                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <div class="checkbox">
                            <label>

                            </label>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <div align="center">
                    <a onclick="add_si_type();" class="btn btn-info">添加新的参保类型</a>
                </div>
            </div>
            <!-- /.box-footer -->
        </form>
    </div>

    <script>

        $(function () {

            var url='/data/ajax';
            var dat={
                _token :$("input[name=_token]").val(),
                type   :'get_my_si_type'
            };

            $.post(url,dat,function (response) {

                if (response.error=='0')
                {
                    $.each(response.data,function (k,v) {
                        $("#add_si_page_si_type").append('<label class="col-sm-1 control-label"><span style="color: red">'+v+'</span></label>');
                    });
                }else
                {
                    layer.msg(response.msg);
                }

            },'json');

        });

    </script>

@stop
