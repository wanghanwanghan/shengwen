@extends('layouts.dashboard')
@section('page_heading','超级管理员功能')
@section('page_heading_small','基础信息与数据表关联')
@section('section')

    {{csrf_field()}}
    <form id="add_cust_form">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="box">
        <div class="box-body">
            <table class="table table-bordered">
                <tbody>
                <tr style="background-color: darkgray" id="afterThis">
                    <td align="center">
                        表名
                    </td>
                    <td align="center">
                        关联的节点以及以下所有子节点
                    </td>
                    <td align="center">
                        操作
                    </td>
                </tr>
                <tr>
                    <td style="width: 200px;text-align: center">
                        <input type="text" style="text-align: center" name="tablename">
                    </td>
                    <td style="width: 200px;text-align: center" onclick="select_project();">
                        <div>
                            <span style="font-size: 12px;" id="parentIframe">redis启动失败</span>
                            <input type="hidden" name="cust_project">
                        </div>
                    </td>
                    <td style="width: 200px;text-align: center">
                        <a style="width: 100px;margin: auto" onclick="relation_save();" class="btn btn-block btn-primary btn-sm">保存</a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    </form>

    <div id="laypage1" class="pagination pagination-sm no-margin pull-right"></div>

    <script>
        
        function relation_save()
        {
            var url ='/data/ajax';
            var data={
                _token :$("input[name=_token]").val(),
                type   :'relation_save',
                TBN    :$("input[name=tablename]").val(),
                Node   :$("input[name=cust_project]").val()
            };
            $.post(url,data,function (response)
            {

                layer.msg(response.msg);
                location.reload();

            },'json');
        }

        $(function () {

            //展示关系************************************************
            var url ='/data/ajax';
            var data={
                _token :$("input[name=_token]").val(),
                type   :'show_relation'
            };
            $.post(url,data,function (response)
            {
                $.each(response.data,function (k,v)
                {
                    var tabletr=$("<tr></tr>");

                    $.each(v,function (key,value)
                    {
                        if (key=='tablename')
                        {
                            tablename=value;
                        }
                        tabletr.append('<td style="width: 200px;text-align: center">'+value+'</td>');
                    });

                    tabletr.append('<td style="width: 200px;text-align: center">'+'<a style="width: 100px;margin: auto" onclick="delete_relation('+tablename+');" class="btn btn-block btn-danger btn-sm">删除</a>'+'</td>');

                    $("#afterThis").after(tabletr);
                });








            },'json');
            //***********************************************************













            //得到区域信息************************************************
            var url ='/data/ajax';
            var data={
                _token :$("input[name=_token]").val(),
                type   :'get_redis'
            };
            $.post(url,data,function (response) {

                $("#parentIframe").html(response.res);
                $("input[name=cust_project]").val(response.res1);

            },'json');
            //***********************************************************
        })



    </script>


@stop
