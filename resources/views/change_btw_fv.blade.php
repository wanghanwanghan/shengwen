@extends('layouts.plane')
@section('body')

    {{csrf_field()}}
    <div class="col-sm-12">
        <form id="change_btw_form">
            <div class="form-group">
                <textarea name="btw" placeholder="请输入..." class="form-control" rows="7" style="resize: none;"></textarea>
            </div>

            <input name="btw_id" type="hidden" value="{{$id}}">

            <div class="row">
                <div class="col-xs-8">
                    <input type="checkbox" name="mycheck">   改成认证通过
                </div>
                <div class="col-xs-4">
                    <a class="btn btn-primary btn-block btn-flat" onclick="aaa_fv();">确定</a>
                </div>
            </div>

        </form>

    </div>

@stop