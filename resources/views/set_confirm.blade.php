@extends('layouts.dashboard')
@section('page_heading','系统设置')
@section('page_heading_small','设置认证类型')
@section('section')

    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">请认真填写</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form id="add_confirm_type_form" class="form-horizontal">
            <div class="box-body">
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">认证类型</label>

                    <div class="col-sm-10">
                        <input style="padding-left: 16px;" type="text" class="form-control" id="inputEmail3" name="confirm_name" placeholder="认证类型">
                    </div>
                </div>
                <div class="form-group">

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
                    <a onclick="add_confirm_type();" class="btn btn-info">添加新的认证类型</a>
                </div>
            </div>
            <!-- /.box-footer -->
        </form>
    </div>

@stop
