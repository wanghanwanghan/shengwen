@extends('layouts.dashboard')
@section('page_heading','超级管理员功能')
@section('page_heading_small','查看工作量')
@section('section')

    <link rel="stylesheet" href="{{asset('public/cos_baidu_input/autosuggest.css')}}">
    <script src="{{asset('public/cos_baidu_input/autosuggest.js')}}"></script>

    <div class="box">
        <div class="box-header">
            <div class="box-body">
                <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
                                <tr>
                                    <td align="center">
                                        <input type="text" class="form-control" id="cos1" placeholder="输入姓名或工号">
                                    </td>

                                    <td align="center">
                                        <input class="form-control layer-date" readonly type="text" id="star" onclick="laydate({istoday:false,isclear:false,issure:false,choose:function(){}});" name="star_date" placeholder="开始时间"/>
                                    </td>

                                    <td align="center">
                                        <input class="form-control layer-date" readonly type="text" id="stop" onclick="laydate({istoday:false,isclear:false,issure:false,choose:function(){}});" name="stop_date" placeholder="结束时间"/>
                                    </td>

                                    <td align="center">
                                        <a style="width: 100px;" id="" onclick="select_staff_work();" class="btn btn-block btn-primary btn-sm">查询</a>
                                    </td>
                                </tr>
                            </table>







                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
                                {{csrf_field()}}
                                <thead>
                                <th style="text-align: center">注册</th>
                                <th style="text-align: center">登记</th>
                                <th style="text-align: center">接听</th>
                                <th style="text-align: center">外呼</th>
                                <th style="text-align: center">日期</th>
                                </thead>
                                <tbody style="text-align: center" id="show_staff_work_tbody">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div hidden class="col-sm-5">
                            <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">一共<<span style="color: red;" id="data_total">0</span>>条记录</div>
                        </div>
                        <div class="col-sm-7">
                            <div id="show_staff_list_laypage" class="pagination pagination-sm no-margin pull-right">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        $("#cos1").autosuggest({
            url:'/data/ajax',
            method:'POST',
            queryParamName:'name_or_account',
            firstSelected:true,
            type:'select_staff_or_account',
            wanghantoken:$("input[name=_token]").val()
        });

        function select_staff_work(curr) {

            $('#show_staff_work_tbody').children().remove();

            var url ='/data/ajax';
            var data={
                _token :$("input[name=_token]").val(),
                type   :'select_staff_work',
                cond  :$('#cos1').val(),
                star  :$('#star').val(),
                stop  :$('#stop').val(),
                page  :curr||1
            };
            $.post(url,data,function (response) {

                if (response.error=='0')
                {
                    for(var i=0;i<response.date.length;i++)
                    {
                        var tabletr=$("<tr></tr>");
                        tabletr.append('<td>'+response.vp_register[i]+'</td>');
                        tabletr.append('<td style="width: 200px">'+'声纹'+response.vp_checkIn[i]+'&nbsp&nbsp&nbsp'+'指静脉'+response.fp_checkIn[i]+'</td>');
                        tabletr.append('<td>'+response.send[i]+'</td>');
                        tabletr.append('<td>'+response.receive[i]+'</td>');
                        tabletr.append('<td style="width: 150px">'+response.date[i]+'</td>');

                        $('#show_staff_work_tbody').append(tabletr);
                    }

                    //显示分页
                    laypage({
                        cont: 'show_staff_list_laypage', //容器
                        pages: response.pages, //通过后台拿到的总页数
                        curr: curr || 1, //当前页
                        jump: function(obj, first){ //触发分页后的回调
                            if(!first){ //点击跳页触发函数自身，并传递当前页：obj.curr
                                select_staff_work(obj.curr);
                            }
                        }
                    });

                }else
                {
                    layer.msg(response.msg)
                }

            },'json');

        }

    </script>






@stop
