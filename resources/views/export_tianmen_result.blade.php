@extends('layouts.dashboard')
@section('page_heading','数据统计')
@section('page_heading_small','导出数据')
@section('section')

    <div class="box">
        <div class="box-header">
            <div class="col-sm-12">

                <div class="row">

                    <form id="export_excel_form">

                        <div class="col-sm-2">
                            <input class="form-control layer-date" readonly type="text" id="star_date" onclick="laydate({istoday:false,isclear:false,issure:false,choose:function(){}});" name="star_date" placeholder="开始时间"/>
                        </div>

                        <div class="col-sm-2">
                            <input class="form-control layer-date" readonly type="text" id="stop_date" onclick="laydate({istoday:false,isclear:false,issure:false,choose:function(){}});" name="stop_date" placeholder="结束时间"/>
                        </div>

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-sm-2" onclick="select_project();">
                            <span style="font-size: 12px;" id="parentIframe">redis启动失败</span>
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
                            <select class="form-control" name="export_type" style="padding-left: 8px;">
                                <option value="0">已注册</option>
                                <option value="1">未注册</option>
                                <option value="2">已登记</option>
                                <option value="3">未登记</option>
                            </select>
                        </div>

                        <div class="col-sm-2">
                            <a id="btnOFexport" style="width: 100px;height: 34px;" onclick="create_excel();" class="btn btn-block btn-primary btn-sm">导出</a>
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
                        <table id="export_confirm_result_table" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
                            <thead>
                            <th style="text-align: center">姓名</th>
                            <th style="text-align: center">身份证</th>
                            <th style="text-align: center">社保编号</th>
                            <th style="text-align: center">认证号码</th>
                            <th style="text-align: center">备用号码</th>
                            <th style="text-align: center">银行账号</th>
                            <th style="text-align: center">参保类型</th>
                            <th style="text-align: center">所属地区</th>
                            <th style="text-align: center">生物特征</th>
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
                                <a href="{{url('download/tianmen/result')}}">打开下载管理页</a>
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-7">
                        <div id="export_confirm_result_laypage" class="pagination pagination-sm no-margin pull-right">
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
        function create_excel(curr,is_export) {

            $("#export_confirm_result_table tbody").children().remove();

            $.ajax({
                url:'/data/ajax',
                type:'POST',
                cache:false,
                async:true,//true为异步，false为同步
                dataType:'JSON',
                data:{
                    _token:$("input[name=_token]").val(),
                    type:'export_tianmen_result',
                    key:$("#export_excel_form").serializeArray(),
                    page  :curr||1,
                    is_export  :is_export||1
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
                        for(var i=0;i<data.data.length;i++)
                        {
                            var tabletr=$("<tr></tr>");

                            $.each(data.data[i],function (k,v)
                            {
                                if (k=='cust_name')
                                {
                                    tabletr.append('<td align="center">'+v+'</td>');
                                }

                                if (k=='cust_id')
                                {
                                    tabletr.append('<td ondblclick="change_Attr_val($(this));" align="center" myvalue='+v+'>'+'***'+'</td>');
                                }

                                if (k=='cust_si_id')
                                {
                                    if (v=='')
                                    {
                                        tabletr.append('<td ondblclick="change_Attr_val($(this));" align="center" myvalue='+'空'+'>'+'***'+'</td>');
                                    }else
                                    {
                                        tabletr.append('<td ondblclick="change_Attr_val($(this));" align="center" myvalue='+v+'>'+'***'+'</td>');
                                    }
                                }

                                if (k=='cust_review_num')
                                {
                                    if (v=='')
                                    {
                                        tabletr.append('<td ondblclick="change_Attr_val($(this));" align="center" myvalue='+'空'+'>'+'***'+'</td>');
                                    }else
                                    {
                                        tabletr.append('<td ondblclick="change_Attr_val($(this));" align="center" myvalue='+v+'>'+'***'+'</td>');
                                    }
                                }

                                if (k=='cust_phone_num')
                                {
                                    if (v=='')
                                    {
                                        tabletr.append('<td ondblclick="change_Attr_val($(this));" align="center" myvalue='+'空'+'>'+'***'+'</td>');
                                    }else
                                    {
                                        tabletr.append('<td ondblclick="change_Attr_val($(this));" align="center" myvalue='+v+'>'+'***'+'</td>');
                                    }
                                }

                                if (k=='cust_bank_num')
                                {
                                    if (v=='')
                                    {
                                        tabletr.append('<td ondblclick="change_Attr_val($(this));" align="center" myvalue='+'空'+'>'+'***'+'</td>');
                                    }else
                                    {
                                        tabletr.append('<td ondblclick="change_Attr_val($(this));" align="center" myvalue='+v+'>'+'***'+'</td>');
                                    }
                                }

                                if (k=='cust_si_type')
                                {
                                    if (v=='')
                                    {
                                        tabletr.append('<td ondblclick="change_Attr_val($(this));" align="center" myvalue='+'空'+'>'+'***'+'</td>');
                                    }else
                                    {
                                        tabletr.append('<td ondblclick="change_Attr_val($(this));" align="center" myvalue='+v+'>'+'***'+'</td>');
                                    }
                                }

                                if (k=='cust_project')
                                {
                                    if (v=='')
                                    {
                                        tabletr.append('<td ondblclick="change_Attr_val($(this));" align="center" myvalue='+'空'+'>'+'***'+'</td>');
                                    }else
                                    {
                                        tabletr.append('<td ondblclick="change_Attr_val($(this));" align="center" myvalue='+v+'>'+'***'+'</td>');
                                    }
                                }

                                if (k=='tezheng')
                                {
                                    if (v=='')
                                    {
                                        tabletr.append('<td ondblclick="change_Attr_val($(this));" align="center" myvalue='+'空'+'>'+'***'+'</td>');
                                    }else
                                    {
                                        tabletr.append('<td ondblclick="change_Attr_val($(this));" align="center" myvalue='+v+'>'+'***'+'</td>');
                                    }
                                }
                            });

                            $("#export_confirm_result_table tbody").append(tabletr);
                        }

                        //显示分页
                        laypage({
                            cont: 'export_confirm_result_laypage', //容器
                            pages: data.pages, //通过后台拿到的总页数
                            curr: curr || 1, //当前页
                            jump: function(obj, first){ //触发分页后的回调
                                if(!first){ //点击跳页触发函数自身，并传递当前页：obj.curr
                                    create_excel(obj.curr,'123');
                                }
                            }
                        });
                    }
                },
                error:function(XMLHttpRequest,textStatus,errorThrown)
                {
                    layer.msg('导出失败');
                },
                beforeSend:function(XMLHttpRequest)
                {
                    $("#btnOFexport").html('<img style="width: 25px;height: 25px;" src="{{asset('public/img/loading.gif')}}" alt=""/>');
                },
                complete:function(XMLHttpRequest,textStatus)
                {
                    $("#btnOFexport").html('导出');
                }
            });

        }

    </script>


@stop
