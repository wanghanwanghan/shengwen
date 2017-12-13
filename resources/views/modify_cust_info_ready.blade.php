@extends('layouts.dashboard')
@section('page_heading','客户管理')
@section('page_heading_small','查询已经注册信息的客户')
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
                                    <input class="form-control" type="text" id="certNumber" name="cond" placeholder="输入年审号码或者身份证号码"/>
                                </div>
                                <div class="col-sm-2">
                                    <a style="width: 134px;height: 34px;padding-top: 8px;" id="button_readID" onclick="new Device().startFun();" class="btn btn-block btn-primary btn-sm">读取身份证</a>
                                </div>
                                <div class="col-sm-2">
                                    <select style="padding-left: 8px" name="cust_review_flag" class="form-control">
                                        <option value="1">第一年审人</option>
                                        <option value="2">第二年审人</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <select style="padding-left: 8px" name="vv_or_fv" class="form-control">
                                        <option value="1">声纹</option>
                                        {{--<option value="2">指静脉</option>--}}
                                    </select>
                                </div>
                                <button type="button" class="btn btn-box-tool" data-widget="collapse" style="float: right">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="box-body">

                        <div class="row">
                            <div class="col-sm-12">
                                <table  onclick="modify_cust_info_click_ready();" id="modify_cust_info" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">

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
                    <a href="#" id="username">备用</a>
                </span>
            </div>
        </div>
    </div>

    <script>

        $(function () {

            var url ='/data/ajax';
            var data={
                _token:$("input[name=_token]").val(),
                type  :'modify_get_data'};
            $.post(url,data,function (response) {
                {window.modify_proj=response.proj;window.modify_si=response.si;window.modify_confirm=response.confirm;}
            },'json');

        });

        function modify_cust_info_click_ready() {

            //天门用
            $('#modify_bank_num').editable({
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
                    //修改数据*************************************
                    var url ='/data/ajax';
                    var data={
                        _token:$("input[name=_token]").val(),
                        type  :'modify_bank_num',
                        key   :value,
                        pid   :$("#modify_pid").html(),
                        edit  :'1'
                    };
                    $.post(url,data,function (response) {
                        if(response.error=='0')
                        {layer.msg(response.msg);}
                        else {layer.msg(response.msg);}
                    },'json');
                    //*********************************************
                }
            });

            //天门用
            $("#cust_delete_btn").click(function () {
                layer.confirm('确认真的要删除吗', {
                    btn: ['确认','取消'], //按钮
                    shade: false //不显示遮罩
                }, function(index){
                    //删除数据*************************************
                    var url ='/data/ajax';
                    var data={
                        _token:$("input[name=_token]").val(),
                        type  :'modify_cust_delete',
                        pid   :$("#modify_pid").html(),
                        stype :$('select[name=vv_or_fv]').val(),
                        edit  :'1'
                    };
                    $.post(url,data,function (response) {
                        if(response.error=='0')
                        {layer.msg(response.msg);location.reload();}
                        else {layer.msg(response.msg);}
                    },'json');
                    //*********************************************
                    layer.close(index);
                });
            });

            $("#cust_death_btn").click(function () {
                layer.confirm('确认真的要设置成<去世>状态吗', {
                    btn: ['确认','取消'], //按钮
                    shade: false //不显示遮罩
                }, function(index){
                    //设置数据*************************************
                    var url ='/data/ajax';
                    var data={
                        _token:$("input[name=_token]").val(),
                        type  :'modify_cust_death',
                        pid   :$("#modify_pid").html(),
                        stype :$('select[name=vv_or_fv]').val(),
                        edit  :'1'
                    };
                    $.post(url,data,function (response) {
                        if(response.error=='0')
                        {layer.msg(response.msg);location.reload();}
                        else {layer.msg(response.msg);}
                    },'json');
                    //*********************************************
                    layer.close(index);
                });
            });

            $("#cust_restore_btn").click(function () {
                layer.confirm('确认真的要设置成<认证>状态吗', {
                    btn: ['确认','取消'], //按钮
                    shade: false //不显示遮罩
                }, function(index){
                    //设置数据*************************************
                    var url ='/data/ajax';
                    var data={
                        _token:$("input[name=_token]").val(),
                        type  :'modify_cust_restore',
                        pid   :$("#modify_pid").html(),
                        stype :$('select[name=vv_or_fv]').val(),
                        edit  :'1'
                    };
                    $.post(url,data,function (response) {
                        if(response.error=='0')
                        {layer.msg(response.msg);location.reload();}
                        else {layer.msg(response.msg);}
                    },'json');
                    //*********************************************
                    layer.close(index);
                });
            });

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
                        pid   :$("#modify_pid").html(),
                        stype :$('select[name=vv_or_fv]').val(),
                        edit  :'1'
                    };
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
                        pid   :$("#modify_pid").html(),
                        stype :$('select[name=vv_or_fv]').val(),
                        edit  :'1'
                    };
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
                        pid   :$("#modify_pid").html(),
                        stype :$('select[name=vv_or_fv]').val(),
                        edit  :'1'
                    };
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
                    //判断是不是空**********************************
                    if (!$.trim(value)) {
                        return '不能为空';
                    }
                    //*********************************************
                    //判断手机号码正确性*****************************
                    var pattern=/^(((1[0-9]{2})|159|153)+\d{8})$/;
                    var res=pattern.test(value);
                    if (res==false) {
                        return '号码错误';
                    }
                    //*********************************************
                    //修改数据*************************************
                    var url ='/data/ajax';
                    var data={
                        _token:$("input[name=_token]").val(),
                        type  :'modify_cust_review_num',
                        key   :value,
                        pid   :$("#modify_pid").html(),
                        edit  :'1'
                    };
                    $.post(url,data,function (response) {
                        if(response.error=='0')
                        {layer.msg(response.msg);}
                        else {layer.msg(response.msg);}
                    },'json');
                    //*********************************************
                }
            });

            $('#modify_cust_phone_num').editable({
                type: "text",                //编辑框的类型。支持text|textarea|select|date|checklist等
                title: "",                   //编辑框的标题
                disabled: false,             //是否禁用编辑
                emptytext: "空",             //空值的默认文本
                mode: "inline",              //编辑框的模式：支持popup和inline两种模式，默认是popup
                validate: function (value) { //字段验证
                    //判断是不是空**********************************
                    //不用判断
                    //*********************************************
                    //判断手机号码正确性*****************************
                    //不用判断
                    //*********************************************
                    //修改数据*************************************
                    var url ='/data/ajax';
                    var data={
                        _token:$("input[name=_token]").val(),
                        type  :'modify_cust_phone_num',
                        key   :$.trim(value),
                        pid   :$("#modify_pid").html(),
                        stype :$('select[name=vv_or_fv]').val(),
                        edit  :'1'
                    };
                    $.post(url,data,function (response) {
                        if(response.error=='0')
                        {layer.msg(response.msg);}
                        else {layer.msg(response.msg);}
                    },'json');
                    //*********************************************
                }
            });

            $('#modify_cust_address').editable({
                type: "text",                //编辑框的类型。支持text|textarea|select|date|checklist等
                title: "",                   //编辑框的标题
                disabled: false,             //是否禁用编辑
                emptytext: "空",             //空值的默认文本
                mode: "inline",              //编辑框的模式：支持popup和inline两种模式，默认是popup
                validate: function (value) { //字段验证
                    //判断是不是空**********************************
                    //不用判断
                    //*********************************************
                    //判断手机号码正确性*****************************
                    //不用判断
                    //*********************************************
                    //修改数据*************************************
                    var url ='/data/ajax';
                    var data={
                        _token:$("input[name=_token]").val(),
                        type  :'modify_cust_address',
                        key   :$.trim(value),
                        pid   :$("#modify_pid").html(),
                        stype :$('select[name=vv_or_fv]').val(),
                        edit  :'1'
                    };
                    $.post(url,data,function (response) {
                        if(response.error=='0')
                        {layer.msg(response.msg);}
                        else {layer.msg(response.msg);}
                    },'json');
                    //*********************************************
                }
            });

            $('#modify_cust_project').editable({
                type: "select",              //编辑框的类型。支持text|textarea|select|date|checklist等
                source: modify_proj,
                title: "请选择",           //编辑框的标题
                disabled: false,           //是否禁用编辑
                emptytext: "空文本",       //空值的默认文本
                mode: "popup",            //编辑框的模式：支持popup和inline两种模式，默认是popup
                validate: function (value) { //字段验证
                    //判断是不是空**********************************
                    if (!$.trim(value)) {
                        return '不能为空';
                    }
                    //*********************************************
                    //修改数据*************************************
                    var url ='/data/ajax';
                    var data={
                        _token:$("input[name=_token]").val(),
                        type  :'modify_cust_project',
                        key   :value,
                        pid   :$("#modify_pid").html(),
                        stype :$('select[name=vv_or_fv]').val(),
                        edit  :'1'
                    };
                    $.post(url,data,function (response) {
                        if(response.error=='0')
                        {layer.msg(response.msg);}
                        else {layer.msg(response.msg);}
                    },'json');
                    //*********************************************
                }
            });

            $('#modify_cust_si_type').editable({
                type: "select",              //编辑框的类型。支持text|textarea|select|date|checklist等
                source: modify_si,
                title: "请选择",           //编辑框的标题
                disabled: false,           //是否禁用编辑
                emptytext: "空文本",       //空值的默认文本
                mode: "popup",            //编辑框的模式：支持popup和inline两种模式，默认是popup
                validate: function (value) { //字段验证
                    //判断是不是空**********************************
                    if (!$.trim(value)) {
                        return '不能为空';
                    }
                    //*********************************************
                    //修改数据*************************************
                    var url ='/data/ajax';
                    var data={
                        _token:$("input[name=_token]").val(),
                        type  :'modify_cust_si_type',
                        key   :value,
                        pid   :$("#modify_pid").html(),
                        stype :$('select[name=vv_or_fv]').val(),
                        edit  :'1'
                    };
                    $.post(url,data,function (response) {
                        if(response.error=='0')
                        {layer.msg(response.msg);}
                        else {layer.msg(response.msg);}
                    },'json');
                    //*********************************************
                }
            });

            $('#modify_cust_confirm_type').editable({
                type: "select",              //编辑框的类型。支持text|textarea|select|date|checklist等
                source: modify_confirm,
                title: "请选择",           //编辑框的标题
                disabled: false,           //是否禁用编辑
                emptytext: "空文本",       //空值的默认文本
                mode: "popup",            //编辑框的模式：支持popup和inline两种模式，默认是popup
                validate: function (value) { //字段验证
                    //判断是不是空**********************************
                    if (!$.trim(value)) {
                        return '不能为空';
                    }
                    //*********************************************
                    //修改数据*************************************
                    var url ='/data/ajax';
                    var data={
                        _token:$("input[name=_token]").val(),
                        type  :'modify_cust_confirm_type',
                        key   :value,
                        pid   :$("#modify_pid").html(),
                        edit  :'1'};
                    $.post(url,data,function (response) {
                        if(response.error=='0')
                        {layer.msg(response.msg);}
                        else {layer.msg(response.msg);}
                    },'json');
                    //*********************************************
                }
            });

        }

        function all_tianmen() {

            var url ='/data/ajax';
            var data={
                _token:$("input[name=_token]").val(),
                cond1   :$("input[name=cond]").val(),
                cond2   :$('select[name=cust_review_flag]').val(),
                cond3   :$('select[name=vv_or_fv]').val(),
                type  :'modify_info_tianmen'
            };

            $.post(url,data,function (response) {

                var line=1;

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

                        //显示客户的身份证照片
                        if (line==1)
                        {
                            table_tr.append("<td rowspan='7' width='240px' height='280px'>"+"<img width='240px' height='280px' src="+response.idcard_picture+">"+"</td>");
                            line++;
                        }

                        $("#modify_cust_info").append(table_tr);

                    })

                }else
                {
                    //清空表格
                    $("#modify_cust_info").children().remove();

                    layer.msg(response.msg);
                }

            },'json');

        }

        $("input[name=cond]").change(function () {
            all_tianmen();
        });

        $('select[name=cust_review_flag]').change(function () {
            all_tianmen();
        });

        $('select[name=vv_or_fv]').change(function () {
            all_tianmen();
        });


    </script>


@stop
