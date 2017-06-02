@extends('layouts.dashboard')
@section('page_heading','超级管理员功能')
@section('page_heading_small','修改认证配置')
@section('section')

    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">请认真填写</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form id="edit_config_form" class="form-horizontal">
            <div class="box-body">
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">动态口令组数</label>

                    <div class="col-sm-10">
                        <select style="padding-left: 8px" name="DynamicPassword" class="form-control">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputEmail4" class="col-sm-2 control-label">文本相关内容</label>

                    <div class="col-sm-10">
                        <input style="padding-left: 16px;" type="text" class="form-control" id="inputEmail4" name="TextDependent" placeholder="文本相关内容">
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <div align="center">
                    <a onclick="set_config();" class="btn btn-info">修改</a>
                </div>
            </div>
            <!-- /.box-footer -->
        </form>
    </div>

    <script>

        $(function () {

            var url='/data/ajax';
            var data={
                _token:$("input[name=_token]").val(),
                type:'get_config'
            };

            $.post(url,data,function (response) {

                if(response.error=='0')
                {
                    $("select[name=DynamicPassword]").val(response.DynamicPassword);
                    $("input[name=TextDependent]").val(response.TextDependent);
                }else
                {
                    layer.msg("未知错误");
                }

            },'json');

        })

    </script>


@stop
