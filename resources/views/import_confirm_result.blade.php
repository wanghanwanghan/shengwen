@extends('layouts.dashboard')
@section('page_heading','数据统计')
@section('page_heading_small','采集结果统计')
@section('section')

    <div class="box">
        <div class="box-header">
            <div class="col-sm-12">

                <div class="row">

                    <form id="import_excel_form">

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-sm-2" onclick="select_project();">
                            <span id="parentIframe">redis启动失败</span>
                            <input type="hidden" name="cust_project">
                        </div>

                        <div class="col-sm-2">
                            <select class="form-control" name="cust_si_type" style="padding-left: 8px;">
                                @foreach($staff_si_type as $k=>$v)
                                    <option value={{$k}}>{{$v}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-2">
                            <select class="form-control" name="cust_type" style="padding-left: 8px;">
                                <option value="0">客户类型-默认全部</option>
                                <option value="A">A类</option>
                                <option value="B">B类</option>
                            </select>
                        </div>

                        <div class="col-sm-2">
                            <select class="form-control" name="import_type" style="padding-left: 8px;">
                                <option value="0">导出登记</option>
                                <option value="1">导出认证</option>
                            </select>
                        </div>

                        <div class="col-sm-2">
                            <input style="display: none" class="form-control layer-date" readonly type="text" id="star_date" onclick="laydate({istoday:false,isclear:false,issure:false,choose:function(){create_excel();}});" name="star_date" placeholder="开始时间"/>
                        </div>

                        <div class="col-sm-2">
                            <input style="display: none" class="form-control layer-date" readonly type="text" id="stop_date" onclick="laydate({istoday:false,isclear:false,issure:false,choose:function(){create_excel();}});" name="stop_date" placeholder="结束时间"/>
                        </div>

                    </form>

                </div>

            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                <div class="row">
                    <div class="col-sm-6">

                    </div>
                    <div class="col-sm-6">

                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <table id="import_confirm_result_table" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
                            <thead>
                            <th style="text-align: center">姓名</th>
                            <th style="text-align: center">身份证</th>
                            <th style="text-align: center">参保类型</th>
                            <th style="text-align: center">社保账号</th>
                            <th style="text-align: center">认证电话</th>
                            <th style="text-align: center">状态结果</th>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-5">
                        <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">
                            一共<<span style="color: red;" id="data_total">0</span>>条记录
                            <span id="down_this_excel">
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-7">
                        <div id="import_confirm_result_laypage" class="pagination pagination-sm no-margin pull-right">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>


    <script>

        $(function () {

            var url ='/data/ajax';
            var data={
                _token :$("input[name=_token]").val(),
                type   :'get_redis'
            };
            $.post(url,data,function (response) {

                $("#parentIframe").html(response.res);
                $("input[name=cust_project]").val(response.res1);

            },'json');

        });

        //生成表格按钮
        function create_excel(curr) {

            $("#down_this_excel").children().remove();
            $("#import_confirm_result_table tbody").children().remove();

            $.ajax({
                url:'/data/ajax',
                type:'POST',
                cache:false,
                async:true,//true为异步，false为同步
                dataType:'JSON',
                data:{
                    _token:$("input[name=_token]").val(),
                    type:'import_confirm_result',
                    key:$("#import_excel_form").serializeArray(),
                    page  :curr||1
                },
                success:function(data,textStatus)
                {
                    if(data.error=='1')
                    {
                        $("#down_this_excel").children().remove();
                        layer.msg(data.msg);
                    }else if(data.error=='0')
                    {
                        $("#data_total").html(data.count_data);

                        //遍历返回的数据-表内容
                        for(var i=0;i<data.data.length;i++){

                            var tabletr=$("<tr></tr>");

                            $.each(data.data[i],function (k,v) {

                                tabletr.append('<td align="center">'+v+'</td>');

                            });

                            $("#import_confirm_result_table tbody").append(tabletr);

                        }

                        //显示分页
                        laypage({
                            cont: 'import_confirm_result_laypage', //容器
                            pages: data.pages, //通过后台拿到的总页数
                            curr: curr || 1, //当前页
                            jump: function(obj, first){ //触发分页后的回调
                                if(!first){ //点击跳页触发函数自身，并传递当前页：obj.curr
                                    create_excel(obj.curr);
                                }
                            }
                        });

                        setTimeout(function () {
                            $("#down_this_excel").children().remove();
                            var lable_a=$("<a href="+data.filename+" download='导出的数据.xls'>可以下载了</a>");
                            $("#down_this_excel").append(lable_a);
                        },5000);
                    }
                },
                error:function(XMLHttpRequest,textStatus,errorThrown)
                {
                    $("#down_this_excel").children().remove();
                    $("#down_this_excel").append('<span>导出失败</span>');
                },
                beforeSend:function(XMLHttpRequest)
                {
                    $("#down_this_excel").append('<img style="width: 25px;height: 25px;" src="{{asset('public/img/loading.gif')}}" alt=""/>');
                },
                complete:function(XMLHttpRequest,textStatus)
                {

                }
            });

        }

        $("input[name=star_date]").change(function () {
            create_excel();
        });

        $("input[name=stop_date]").change(function () {
            create_excel();
        });

        $("select[name=cust_si_type]").change(function () {
            create_excel();
        });

        $("select[name=import_type]").change(function () {

            if ($("select[name=import_type]").val()==1)
            {
                $("input[name=star_date]").css('display','block');
                $("input[name=stop_date]").css('display','block');
            }else if($("select[name=import_type]").val()==0)
            {
                $("input[name=star_date]").css('display','none');
                $("input[name=stop_date]").css('display','none');
            }else
            {

            }

            create_excel();

        });

        $("select[name=cust_type]").change(function () {
            create_excel();
        });

    </script>


@stop
