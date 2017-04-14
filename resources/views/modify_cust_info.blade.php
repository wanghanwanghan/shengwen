@extends('layouts.dashboard')
@section('page_heading','声纹管理')
@section('page_heading_small','修改客户信息')
@section('section')


    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#modify_cust_info_1" data-toggle="tab" aria-expanded="true">通过条件查询</a></li>
            <li class=""><a href="#modify_cust_info_2" data-toggle="tab" aria-expanded="false">备用</a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="modify_cust_info_1" style="height: auto;">

                <!--1.通过手机号查询-->
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="box">
                    <div class="box-header">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-3">
                                    <input class="form-control" type="text" id="cond" name="cond" placeholder="输入年审号码或者身份证号码"/>
                                </div>
                                <div class="col-sm-2">
                                    <select style="padding-left: 8px" name="cust_review_flag" class="form-control">
                                        <option value="1">第一年审人</option>
                                        <option value="2">第二年审人</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box-body">

                        <div class="row">
                            <div class="col-sm-12">
                                <table  onclick="modify_cust_info_click();" id="modify_cust_info" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">

                                </table>
                            </div>
                            <div class="col-sm-12">
                                <table id="modify_vopr_info" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">

                                </table>
                            </div>
                        </div>

                    </div>
                </div>
                <!--1.终了----------->

            </div>
            <div class="tab-pane" id="modify_cust_info_2" style="height: auto;">
                <span>
                    <a href="#" id="username">用户名</a>
                </span>
            </div>
        </div>
    </div>

    <script>

        function modify_cust_info_click() {

            $('#modify_cust_name').editable({
                type: "text",                //编辑框的类型。支持text|textarea|select|date|checklist等
                title: "",                   //编辑框的标题
                disabled: false,             //是否禁用编辑
                emptytext: "空",             //空值的默认文本
                mode: "inline",              //编辑框的模式：支持popup和inline两种模式，默认是popup
                validate: function (value) { //字段验证
                    //判断是不是空**********************************
                    if (!$.trim(value)) {
                        return '不能为空';
                    }
                    //*********************************************
                    //判断姓名是不是中文****************************
                    var pattern=/^[\u4E00-\u9FA5]{1,10}$/;
                    var res=pattern.test(value);
                    if (res==false) {
                        return '必须是中文';
                    }
                    //*********************************************
                    //修改数据*************************************
                    var url ='/data/ajax';
                    var data={
                        _token:$("input[name=_token]").val(),
                        type  :'modify_cust_name',
                        key   :value,
                        pid   :$("#modify_pid").html()};
                    $.post(url,data,function (response) {
                        if(response.error=='0')
                        {layer.msg(response.msg);}
                        else {layer.msg(response.msg);}
                    },'json');
                    //*********************************************
                }
            });

            $('#modify_cust_id').editable({
                type: "text",                //编辑框的类型。支持text|textarea|select|date|checklist等
                title: "",                   //编辑框的标题
                disabled: false,             //是否禁用编辑
                emptytext: "空",             //空值的默认文本
                mode: "inline",              //编辑框的模式：支持popup和inline两种模式，默认是popup
                validate: function (value) { //字段验证
                    //判断是不是空**********************************
                    if (!$.trim(value)) {
                        return '不能为空';
                    }
                    //*********************************************
                    //判断身份证号正确性****************************
                    if (!check_id_card(value)) {
                        return '号码错误';
                    }
                    //*********************************************
                    //修改数据*************************************
                    var url ='/data/ajax';
                    var data={
                        _token:$("input[name=_token]").val(),
                        type  :'modify_cust_id',
                        key   :value,
                        pid   :$("#modify_pid").html()};
                    $.post(url,data,function (response) {
                        if(response.error=='0')
                        {layer.msg(response.msg);}
                        else {layer.msg(response.msg);}
                    },'json');
                    //*********************************************
                }
            });

            $('#modify_cust_si_id').editable({
                type: "text",                //编辑框的类型。支持text|textarea|select|date|checklist等
                title: "",                   //编辑框的标题
                disabled: false,             //是否禁用编辑
                emptytext: "空",             //空值的默认文本
                mode: "inline",              //编辑框的模式：支持popup和inline两种模式，默认是popup
                validate: function (value) { //字段验证
                    //判断是不是空**********************************
                    //可以是空
                    //*********************************************
                    //判断社保号正确性******************************
                    //不判断了
                    //*********************************************
                    //修改数据*************************************
                    var url ='/data/ajax';
                    var data={
                        _token:$("input[name=_token]").val(),
                        type  :'modify_cust_si_id',
                        key   :$.trim(value),
                        pid   :$("#modify_pid").html()};
                    $.post(url,data,function (response) {
                        if(response.error=='0')
                        {layer.msg(response.msg);}
                        else {layer.msg(response.msg);}
                    },'json');
                    //*********************************************
                }
            });

            $('#modify_cust_review_num').editable({
                type: "text",                //编辑框的类型。支持text|textarea|select|date|checklist等
                title: "",                   //编辑框的标题
                disabled: false,             //是否禁用编辑
                emptytext: "空",             //空值的默认文本
                mode: "inline",              //编辑框的模式：支持popup和inline两种模式，默认是popup
                validate: function (value) { //字段验证
                    if (!$.trim(value)) {
                        return '不能为空';
                    }
                }
            });

            $('#modify_cust_phone_num').editable({
                type: "text",                //编辑框的类型。支持text|textarea|select|date|checklist等
                title: "",                   //编辑框的标题
                disabled: false,             //是否禁用编辑
                emptytext: "空",             //空值的默认文本
                mode: "inline",              //编辑框的模式：支持popup和inline两种模式，默认是popup
                validate: function (value) { //字段验证
                    if (!$.trim(value)) {
                        return '不能为空';
                    }
                }
            });

            $('#modify_cust_address').editable({
                type: "text",                //编辑框的类型。支持text|textarea|select|date|checklist等
                title: "",                   //编辑框的标题
                disabled: false,             //是否禁用编辑
                emptytext: "空",             //空值的默认文本
                mode: "inline",              //编辑框的模式：支持popup和inline两种模式，默认是popup
                validate: function (value) { //字段验证
                    if (!$.trim(value)) {
                        return '不能为空';
                    }
                }
            });

            $('#modify_cust_project').editable({
                type: "text",                //编辑框的类型。支持text|textarea|select|date|checklist等
                title: "",                   //编辑框的标题
                disabled: false,             //是否禁用编辑
                emptytext: "空",             //空值的默认文本
                mode: "inline",              //编辑框的模式：支持popup和inline两种模式，默认是popup
                validate: function (value) { //字段验证
                    if (!$.trim(value)) {
                        return '不能为空';
                    }
                }
            });

            $('#modify_cust_si_type').editable({
                type: "text",                //编辑框的类型。支持text|textarea|select|date|checklist等
                title: "",                   //编辑框的标题
                disabled: false,             //是否禁用编辑
                emptytext: "空",             //空值的默认文本
                mode: "inline",              //编辑框的模式：支持popup和inline两种模式，默认是popup
                validate: function (value) { //字段验证
                    if (!$.trim(value)) {
                        return '不能为空';
                    }
                }
            });

            $('#modify_cust_confirm_type').editable({
                type: "text",                //编辑框的类型。支持text|textarea|select|date|checklist等
                title: "",                   //编辑框的标题
                disabled: false,             //是否禁用编辑
                emptytext: "空",             //空值的默认文本
                mode: "inline",              //编辑框的模式：支持popup和inline两种模式，默认是popup
                validate: function (value) { //字段验证
                    if (!$.trim(value)) {
                        return '不能为空';
                    }
                }
            });

        }

        function all() {

            var url ='/data/ajax';
            var data={
                _token:$("input[name=_token]").val(),
                cond1   :$("#cond").val(),
                cond2   :$('select[name=cust_review_flag]').val(),
                type  :'modify_info'
            };

            $.post(url,data,function (response) {

                if(response.error=='0')
                {
                    layer.msg(response.msg);

                    //清空表格
                    $("#modify_cust_info").children().remove();

                    $.each(response.data,function (k,v) {

                        //创建一行
                        var table_tr=$("<tr></tr>");

                        table_tr.append("<td align='center' width='20%'>"+k+"</td>");
                        table_tr.append("<td>"+v+"</td>");

                        $("#modify_cust_info").append(table_tr);

                    })

                }else
                {
                    layer.msg(response.msg);
                }

            },'json');

        }

        $('#cond').change(function () {
            all();
        });

        $('select[name=cust_review_flag]').change(function () {
            all();
        });


    </script>


@stop
