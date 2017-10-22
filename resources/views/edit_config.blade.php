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

    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">ivr参数配置</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form id="ivr_config_form" class="form-horizontal">
            <div class="box-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label">verify_score_threshold</label>

                    <div class="col-sm-4">
                        <input style="padding-left: 16px;" type="text" class="form-control" name="verify_score_threshold" placeholder="文本相关内容">
                    </div>

                    <label class="col-sm-2 control-label">max_lines</label>

                    <div class="col-sm-4">
                        <input style="padding-left: 16px;" type="text" class="form-control" name="max_lines" placeholder="文本相关内容">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">outgoing_pool_size</label>

                    <div class="col-sm-4">
                        <input style="padding-left: 16px;" type="text" class="form-control" name="outgoing_pool_size" placeholder="文本相关内容">
                    </div>

                    <label class="col-sm-2 control-label">verify_record_time</label>

                    <div class="col-sm-4">
                        <input style="padding-left: 16px;" type="text" class="form-control" name="verify_record_time" placeholder="文本相关内容">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">register_record_time</label>

                    <div class="col-sm-4">
                        <input style="padding-left: 16px;" type="text" class="form-control" name="register_record_time" placeholder="文本相关内容">
                    </div>

                    <label class="col-sm-2 control-label">vpr_silence_hits</label>

                    <div class="col-sm-4">
                        <input style="padding-left: 16px;" type="text" class="form-control" name="vpr_silence_hits" placeholder="文本相关内容">
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <div class="col-sm-5">
                </div>
                <div class="col-sm-1">
                    <a onclick="set_config_for_ivr('1');" class="btn btn-info">修改</a>
                </div>
                <div class="col-sm-1">
                    <a onclick="set_config_for_ivr('0');" class="btn btn-info">恢复默认</a>
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

                    $("input[name=verify_score_threshold]").val(response.ivr_verify_score_threshold);
                    $("input[name=max_lines]").val(response.ivr_max_lines);
                    $("input[name=outgoing_pool_size]").val(response.ivr_outgoing_pool_size);
                    $("input[name=verify_record_time]").val(response.ivr_verify_record_time);
                    $("input[name=register_record_time]").val(response.ivr_register_record_time);
                    $("input[name=vpr_silence_hits]").val(response.ivr_vpr_silence_hits);

                }else
                {
                    layer.msg("未知错误");
                }

            },'json');

        })

    </script>


@stop
