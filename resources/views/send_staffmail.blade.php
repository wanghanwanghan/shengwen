@extends('layouts.dashboard')
@section('page_heading','超级管理员功能')
@section('page_heading_small','发邮件')
@section('section')

    <div class="box box-warning">
        <div class="box-header with-border">
            <h3 class="box-title">请<span style="color: red">认真</span>填写邮件内容</h3>
            <button type="button" class="btn btn-box-tool" data-widget="collapse" style="float: right">
                <i class="fa fa-minus"></i>
            </button>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <form id="sendmail_form" role="form">
                <div class="form-group">
                    <div class="radio">
                        <label>
                            <input name="optionsRadios" id="RootSendMailRadios1" value="1" checked="" type="radio">
                            给全体员工
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input name="optionsRadios" id="RootSendMailRadios2" value="2" type="radio">
                            给指定属地员工
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input name="optionsRadios" id="RootSendMailRadios3" value="3" type="radio">
                            给指定参保类型员工
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input name="optionsRadios" id="RootSendMailRadios4" value="4" type="radio">
                            给指定员工
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div id="RootSendMailType">
                        <input type="hidden" name="allstaff">
                    </div>
                </div>
                <div class="form-group">
                    <label>邮件内容--限定300字内</label>
                    <textarea class="form-control" rows="15" name="mail_content" placeholder="请输入 ..."></textarea>
                </div>
                <div class="form-group">
                    <a style="width: 100px;" onclick="SendMail();" class="btn btn-block btn-primary btn-sm">发送</a>
                </div>

            </form>
        </div>
        <!-- /.box-body -->
    </div>

    <script>

        //给全体员工发
        $("#RootSendMailRadios1").click(function () {

            $("#RootSendMailType").children().remove();

            var node=$("<input type=hidden name=allstaff>");

            $("#RootSendMailType").append(node);

        });

        //给指定属地员工发
        $("#RootSendMailRadios2").click(function () {

            $("#RootSendMailType").children().remove();

            var node=$("<select name=proj></select>");

            var url ='/data/ajax';
            var data={
                _token:$("input[name=_token]").val(),
                type  :'send_mail_get_proj'
            };

            $.post(url,data,function (response) {

                if(response.error=='0')
                {
                    layer.msg(response.msg);

                    $.each(response.data,function(key,value)
                    {
                        node.append("<option value="+key+">"+value+"</option>");
                    });
                }else
                {
                    layer.msg(response.msg);
                }

            },'json');

            node.addClass("form-control");
            node.css("padding-left","8px");
            node.css("width","12%");

            $("#RootSendMailType").append(node);

        });

        //给指定参保类型员工发
        $("#RootSendMailRadios3").click(function () {

            $("#RootSendMailType").children().remove();

            var node=$("<select name=si_type></select>");

            var url ='/data/ajax';
            var data={
                _token:$("input[name=_token]").val(),
                type  :'send_mail_get_si_type'
            };

            $.post(url,data,function (response) {

                if(response.error=='0')
                {
                    layer.msg(response.msg);

                    $.each(response.data,function(key,value)
                    {
                        node.append("<option value="+key+">"+value+"</option>");
                    });
                }else
                {
                    layer.msg(response.msg);
                }

            },'json');

            node.addClass("form-control");
            node.css("padding-left","8px");
            node.css("width","12%");

            $("#RootSendMailType").append(node);

        });

        //给指定员工发
        $("#RootSendMailRadios4").click(function () {

            $("#RootSendMailType").children().remove();

            var node=$("<input type=text name=staff placeholder=请输入员工账号>");

            node.addClass("form-control");
            node.css("padding-left","8px");
            node.css("width","12%");

            $("#RootSendMailType").append(node);

        });

    </script>



@stop
