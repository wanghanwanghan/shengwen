@extends('layouts.dashboard')
@section('page_heading','数据统计')
@section('page_heading_small','下载管理页')
@section('section')

    <div class="box">
        {{csrf_field()}}
        <div class="box-body no-padding">
            <table class="table table-condensed">
                <thead>
                <tr>
                    <th style="text-align: center;">员工姓名</th>
                    <th style="text-align: center;">截至时间</th>
                    <th style="text-align: center;">归属地区</th>
                    <th style="text-align: center;">参保类型</th>
                    <th style="text-align: center;">客户状态</th>
                    <th style="text-align: center;">导出进度</th>
                    <th style="text-align: center;">文件下载</th>
                </tr>
                </thead>
                <tbody id="kk">

                </tbody>
            </table>
        </div>
    </div>

    <script>

        //标记一下该文件是否被下载
        function mark_download(filename) {

            var url ='/data/ajax';
            var data={
                _token:$("input[name=_token]").val(),
                type  :'mark_download',
                key   :filename
            };

            $.post(url,data,function (response) {},'json');

        }

        var url ='/data/ajax';
        var data={
            _token:$("input[name=_token]").val(),
            type  :'get_download_info_in_mongo'
        };

        $.post(url,data,function (response) {

            if(response.error=='0')
            {
                $.each(response.unfinish,function(key,value)
                {
                    var tabletr=$("<tr></tr>");

                    $.each(response.unfinish[key],function(i,v)
                    {
                        if (i=='staff_name')
                        {
                            tabletr.append('<td style="text-align: center;">'+v+'</td>');
                        }
                        if (i=='export_time')
                        {
                            tabletr.append('<td style="text-align: center;">'+v+'</td>');
                        }
                        if (i=='export_proj')
                        {
                            tabletr.append('<td style="text-align: center;">'+v+'</td>');
                        }
                        if (i=='export_si')
                        {
                            tabletr.append('<td style="text-align: center;">'+v+'</td>');
                        }
                        if (i=='export_type')
                        {
                            tabletr.append('<td style="text-align: center;">'+v+'</td>');
                        }
                        if (i=='schedule')
                        {
                            if (v < 100)
                            {
                                tabletr.append('<td style="text-align: center;"><span class="badge bg-yellow">'+v+'%'+'</span></td>');
                            }else
                            {
                                tabletr.append('<td style="text-align: center;"><span class="badge bg-green">'+v+'%'+'</span></td>');
                            }
                        }
                        if (i=='filename')
                        {
                            tabletr.append('<td style="text-align: center;">'+v+'</td>');
                        }
                    });

                    $("#kk").append(tabletr);

                });

                $.each(response.finish,function(key,value)
                {
                    var tabletr=$("<tr></tr>");

                    $.each(response.finish[key],function(i,v)
                    {
                        if (i=='staff_name')
                        {
                            tabletr.append('<td style="text-align: center;">'+v+'</td>');
                        }
                        if (i=='export_time')
                        {
                            tabletr.append('<td style="text-align: center;">'+v+'</td>');
                        }
                        if (i=='export_proj')
                        {
                            tabletr.append('<td style="text-align: center;">'+v+'</td>');
                        }
                        if (i=='export_si')
                        {
                            tabletr.append('<td style="text-align: center;">'+v+'</td>');
                        }
                        if (i=='export_type')
                        {
                            tabletr.append('<td style="text-align: center;">'+v+'</td>');
                        }
                        if (i=='schedule')
                        {
                            tabletr.append('<td style="text-align: center;"><span class="badge bg-green">'+v+'%'+'</span></td>');
                        }
                        if (i=='filename')
                        {
                            tabletr.append('<td style="text-align: center;">'+'<a href="'+v+'" onclick=mark_download($(this).attr("href"));>下载</a>'+'</td>');
                        }
                    });

                    $("#kk").append(tabletr);

                });
            }else
            {
                layer.msg('获取失败');
            }

        },'json');

    </script>

@stop
