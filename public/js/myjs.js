function check_id_card(value){
    var arrExp = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];//加权因子
    var arrValid = [1, 0, "X", 9, 8, 7, 6, 5, 4, 3, 2];//校验码
    if(/^\d{17}\d|x$/i.test(value)){
        var sum = 0, idx;
        for(var i = 0; i < value.length - 1; i++){
            // 对前17位数字与权值乘积求和
            sum += parseInt(value.substr(i, 1), 10) * arrExp[i];
        }
        // 计算模（固定算法）
        idx = sum % 11;
        // 检验第18为是否与校验码相等
        return arrValid[idx] == value.substr(17, 1).toUpperCase();
    }else{
        return false;
    }
}

function get_ip_address() {

    var url='/data/ajax';
    var dat={
        _token :$("input[name=_token]").val(),
        type   :'get_ip_address'
    };

    $.post(url,dat,function (response) {

        if (response.error=='0')
        {
            $("#ip_address").html(response.data['area']);
            $("#ip_network").html(response.data['location']);
        }else
        {
            layer.msg(response.msg);
        }

    },'json');

}

function set_treeview_active(id) {

    var url='/data/ajax';
    var dat={
        _token :$("input[name=_token]").val(),
        type   :'set_treeview_active',
        key    :id
    };

    $.post(url,dat,function (response) {

        if (response.error=='0')
        {
        }else
        {
            layer.msg(response.msg);
        }

    },'json');

}

function get_treeview_active() {

    var url='/data/ajax';
    var dat={
        _token :$("input[name=_token]").val(),
        type   :'get_treeview_active'
    };

    $.post(url,dat,function (response) {

        if (response.error=='0')
        {
            $.each(response.res,function (k,v) {

                if (v!='')
                {
                    $("#"+k).parent().addClass("active");
                }

            });

        }else
        {
            layer.msg(response.msg);
        }

    },'json');
}

function daochudiqu() {

    if ($("#daochudiqu_lable").val()=='xxx')
    {
        layer.msg('还没有查询到可导出的地区');

    }else
    {

        var url ='/data/ajax';
        var data={
            _token :$("input[name=_token]").val(),
            type   :'project_for_redis'
        };

        $.post(url,data,function (response) {

            if(response.error=='0')
            {
                var lable_a=$("<a href="+response.file_name+" download='导出的地区数据.xls'>可以下载了</a>");
                $("#excel_file_download").append(lable_a);
                layer.msg(response.msg);
            }else
            {
                layer.msg(response.msg);
            }

        },'json');

    }

}

function select_project() {

    layer.open({
        type: 2,
        title: '添加地区信息',
        maxmin:false,//是否显示最大化最小化按钮
        resize:false,//窗口是否可以拉伸
        shadeClose: true, //点击遮罩关闭层
        scrollbar:false,//是否允许显示滚动条
        fixed:true,
        area:['850px','600px'],
        content: '/select_project'
    });



}

function staff_login() {

    var url ='/data/ajax';
    var data={
        _token:$("input[name=_token]").val(),
        type  :'staff_login',
        key   :$("#staff_login_form").serializeArray()
    };

    $.post(url,data,function (response) {

        if(response.error=='0')
        {
            location.assign('/index');
        }else
        {
            layer.msg(response.msg);
        }

    },'json');

}

function loginout() {

    location.assign('/');

}

function SendMail() {

    var url ='/data/ajax';
    var data={
        _token:$("input[name=_token]").val(),
        type  :'send_mail',
        key   :$("#sendmail_form").serializeArray()
    };

    $.post(url,data,function (response) {

        if(response.error=='0')
        {
            layer.msg(response.msg);
        }else
        {
            layer.msg(response.msg);
        }

    },'json');

}

function refresh_mail() {

    $("#mail_ul").children().remove();

    var url ='/data/ajax';
    var data={
        _token:$("input[name=_token]").val(),
        type  :'get_mail'
    };

    $.post(url,data,function (response) {

        if(response.error=='0')
        {
            //遍历所有邮件，显示在前端页面
            if (response.AllMail.length!='0')
            {
                for (var i=1;i<=response.AllMail.length;i++)
                {
                    var li_node=$("<li></li>");
                    var a_node=$("<a href=javascript:;></a>");
                    var h5_node=$("<h5 class=control-sidebar-subheading></h5>");
                    var div_node=$("<div class='progress progress-xxs'></div>");
                    div_node.append("<div class='progress-bar progress-bar-danger style=width: 100%'></div>");

                    $.each(response.AllMail[i-1],function(key,value)
                    {
                        if (key=='mail_content')
                        {
                            h5_node.append("<div>"+value+"</div>");
                        }
                        if (key=='created_at')
                        {
                            h5_node.append("<div>"+value+"</div>");
                        }

                        a_node.append(h5_node);
                        a_node.append(div_node);
                        li_node.append(a_node);
                        $("#mail_ul").append(li_node);
                    });
                }
            }
            if (response.ProjMail.length!='0')
            {
                for (var i=1;i<=response.ProjMail.length;i++)
                {
                    var li_node=$("<li></li>");
                    var a_node=$("<a href=javascript:;></a>");
                    var h5_node=$("<h5 class=control-sidebar-subheading></h5>");
                    var div_node=$("<div class='progress progress-xxs'></div>");
                    div_node.append("<div class='progress-bar progress-bar-danger style=width: 100%'></div>");

                    $.each(response.ProjMail[i-1],function(key,value)
                    {
                        if (key=='mail_content')
                        {
                            h5_node.append("<div>"+value+"</div>");
                        }
                        if (key=='created_at')
                        {
                            h5_node.append("<div>"+value+"</div>");
                        }

                        a_node.append(h5_node);
                        a_node.append(div_node);
                        li_node.append(a_node);
                        $("#mail_ul").append(li_node);
                    });
                }
            }
            if (response.SiTypeMail.length!='0')
            {
                for (var i=1;i<=response.SiTypeMail.length;i++)
                {
                    var li_node=$("<li></li>");
                    var a_node=$("<a href=javascript:;></a>");
                    var h5_node=$("<h5 class=control-sidebar-subheading></h5>");
                    var div_node=$("<div class='progress progress-xxs'></div>");
                    div_node.append("<div class='progress-bar progress-bar-danger style=width: 100%'></div>");

                    $.each(response.SiTypeMail[i-1],function(key,value)
                    {
                        if (key=='mail_content')
                        {
                            h5_node.append("<div>"+value+"</div>");
                        }
                        if (key=='created_at')
                        {
                            h5_node.append("<div>"+value+"</div>");
                        }

                        a_node.append(h5_node);
                        a_node.append(div_node);
                        li_node.append(a_node);
                        $("#mail_ul").append(li_node);
                    });
                }
            }
            if (response.StaffMail.length!='0')
            {
                for (var i=1;i<=response.StaffMail.length;i++)
                {
                    var li_node=$("<li></li>");
                    var a_node=$("<a href=javascript:;></a>");
                    var h5_node=$("<h5 class=control-sidebar-subheading></h5>");
                    var div_node=$("<div class='progress progress-xxs'></div>");
                    div_node.append("<div class='progress-bar progress-bar-danger style=width: 100%'></div>");

                    $.each(response.StaffMail[i-1],function(key,value)
                    {
                        if (key=='mail_content')
                        {
                            h5_node.append("<div>"+value+"</div>");
                        }
                        if (key=='created_at')
                        {
                            h5_node.append("<div>"+value+"</div>");
                        }

                        a_node.append(h5_node);
                        a_node.append(div_node);
                        li_node.append(a_node);
                        $("#mail_ul").append(li_node);
                    });
                }
            }
        }else
        {
            layer.msg(response.msg);
        }

    },'json');

}

function add_project() {

    var url ='/data/ajax';
    var data={
        _token:$("input[name=_token]").val(),
        type  :'add_project',
        key   :$("#add_project_form").serializeArray()
    };

    $.post(url,data,function (response) {

        if(response.error=='0')
        {
            layer.alert(response.msg);
            location.reload();
        }else
        {
            layer.alert(response.msg);
        }

    },'json');
    
}

function add_staff() {

    var treeObj1 = $.fn.zTree.getZTreeObj("ztree_staff_project");
    var nodes1 = treeObj1.getCheckedNodes(true);
    var checked_list1 = {};
    $.each(nodes1, function(index, val) {
        var checked1 = {};
        checked1.id = val.id;
        checked_list1[index] = checked1;
    });
    var json1 = JSON.stringify(checked_list1);
    $("input[name=staff_project]").val(json1);

    var treeObj2 = $.fn.zTree.getZTreeObj("ztree_staff_si_type");
    var nodes2 = treeObj2.getCheckedNodes(true);
    var checked_list2 = {};
    $.each(nodes2, function(index, val) {
        var checked2 = {};
        checked2.id = val.id;
        checked_list2[index] = checked2;
    });
    var json2 = JSON.stringify(checked_list2);
    $("input[name=staff_si_type]").val(json2);

    var treeObj3 = $.fn.zTree.getZTreeObj("ztree_staff_level");
    var nodes3 = treeObj3.getCheckedNodes(true);
    var checked_list3 = {};
    $.each(nodes3, function(index, val) {
        var checked3 = {};
        checked3.id = val.id;
        checked_list3[index] = checked3;
    });
    var json3 = JSON.stringify(checked_list3);
    $("input[name=staff_level]").val(json3);

    var url ='/data/ajax';
    var data={
        _token:$("input[name=_token]").val(),
        type  :'add_staff',
        key   :$("#add_staff_form").serializeArray(),
    };

    $.post(url,data,function (response) {

        if(response.error=='0')
        {
            layer.alert(response.msg);
            location.reload();
        }else
        {
            layer.alert(response.msg);
        }

    },'json');

}

function edit_staff() {

    var treeObj1 = $.fn.zTree.getZTreeObj("ztree_staff_project");
    var nodes1 = treeObj1.getCheckedNodes(true);
    var checked_list1 = {};
    $.each(nodes1, function(index, val) {
        var checked1 = {};
        checked1.id = val.id;
        checked_list1[index] = checked1;
    });
    var json1 = JSON.stringify(checked_list1);
    $("input[name=staff_project]").val(json1);

    var treeObj2 = $.fn.zTree.getZTreeObj("ztree_staff_si_type");
    var nodes2 = treeObj2.getCheckedNodes(true);
    var checked_list2 = {};
    $.each(nodes2, function(index, val) {
        var checked2 = {};
        checked2.id = val.id;
        checked_list2[index] = checked2;
    });
    var json2 = JSON.stringify(checked_list2);
    $("input[name=staff_si_type]").val(json2);

    var treeObj3 = $.fn.zTree.getZTreeObj("ztree_staff_level");
    var nodes3 = treeObj3.getCheckedNodes(true);
    var checked_list3 = {};
    $.each(nodes3, function(index, val) {
        var checked3 = {};
        checked3.id = val.id;
        checked_list3[index] = checked3;
    });
    var json3 = JSON.stringify(checked_list3);
    $("input[name=staff_level]").val(json3);

    var url ='/data/ajax';
    var data={
        _token:$("input[name=_token]").val(),
        type  :'edit_staff',
        key   :$("#edit_staff_form").serializeArray(),
    };

    $.post(url,data,function (response) {

        if(response.error=='0')
        {
            layer.alert(response.msg);
            location.reload();
        }else
        {
            layer.alert(response.msg);
        }

    },'json');

}

function add_level() {

    var url ='/data/ajax';
    var data={
        _token:$("input[name=_token]").val(),
        type  :'add_level',
        key   :$("#add_level_form").serializeArray()
    };

    $.post(url,data,function (response) {

        if(response.error=='0')
        {
            layer.alert(response.msg);
            location.reload();
        }else
        {
            layer.alert(response.msg);
        }

    },'json');

}

function add_si_type() {

    var url ='/data/ajax';
    var data={
        _token:$("input[name=_token]").val(),
        type  :'add_si_type',
        key   :$("#add_si_type_form").serializeArray()
    };

    $.post(url,data,function (response) {

        if(response.error=='0')
        {
            layer.alert(response.msg);
        }else
        {
            layer.alert(response.msg);
        }

    },'json');

}

function add_confirm_type() {

    var url ='/data/ajax';
    var data={
        _token:$("input[name=_token]").val(),
        type  :'add_confirm_type',
        key   :$("#add_confirm_type_form").serializeArray()
    };

    $.post(url,data,function (response) {

        if(response.error=='0')
        {
            layer.alert(response.msg);
        }else
        {
            layer.alert(response.msg);
        }

    },'json');

}

function add_cust_A() {

    var url ='/data/ajax';
    var data={
        _token:$("input[name=_token]").val(),
        type  :'add_cust',
        key   :$("#add_cust_form").serializeArray(),
        cust_type:'A',
        cust_review_flag:'1',
        cust_register_flag:'0',
        cust_relation_flag:'0'
    };

    $.post(url,data,function (response) {

        if(response.error=='0')
        {
            layer.msg(response.msg);

            //显示这个用户，今日处理过的用户，往日的不显示
            refresh_A();
        }else
        {
            layer.alert(response.msg);
        }

    },'json');

}

function add_cust_B() {

    var url ='/data/ajax';
    var data={
        _token:$("input[name=_token]").val(),
        type  :'add_cust',
        key   :$("#add_cust_form").serializeArray(),
        cust_type:'B',
        cust_review_flag:'1',
        cust_register_flag:'0',
        cust_relation_flag:'0'
    };

    $.post(url,data,function (response) {

        if(response.error=='0')
        {
            layer.msg(response.msg);

            //显示这个用户，今日处理过的用户，往日的不显示
            refresh_B();
        }else
        {
            layer.alert(response.msg);
        }

    },'json');

}

function add_second_cust() {

    var url ='/data/ajax';
    var data={
        _token:$("input[name=_token]").val(),
        type  :'add_cust',
        key   :$("#add_second_form").serializeArray(),
        cust_type:$("input[name=cust_type_hidden]").val(),
        cust_review_flag:'2',
        cust_register_flag:'0',
        cust_relation_flag:$("input[name=first_id]").val()
    };

    $.post(url,data,function (response) {

        if(response.error=='0')
        {
            layer.msg(response.msg);

        }else
        {
            layer.alert(response.msg);
        }

    },'json');

}

function refresh_A(curr) {

    $("#add_cust_table tbody").children().remove();

    //显示这个用户，今日处理过的用户，往日的不显示
    var url ='/data/ajax';
    var data={
        _token:$("input[name=_token]").val(),
        page:curr||1,
        type  :'refresh_A'
    };

    $.post(url,data,function (response) {

        if(response.error=='0')
        {
            layer.msg(response.msg);

            //遍历返回的数据-表内容
            for(var i=0;i<response.data.length;i++){

                var tabletr=$("<tr></tr>");

                $.each(response.data[i],function (k,v) {

                    if (k=='cust_register_flag')
                    {
                        if (v=='0')
                        {
                            //开始注册按钮
                            tabletr.append('<td align="center">'+'<a class="btn btn-success" onclick=api_register($(this).attr("id")); id='+response.data[i]['cust_num']+'>开始注册</a>'+'</td>');
                        }else if (v=='3')
                        {
                            //客户已去世
                            tabletr.append('<td align="center">'+'客户已去世'+'</td>');
                        }else
                        {
                            //验证，删除按钮
                            tabletr.append('<td align="center">'+'<a class="btn btn-info" onclick=api_verify($(this).attr("id")); id='+response.data[i]['cust_num']+'>验证</a>'+'<a class="btn btn-danger" onclick=delete_cust_voice($(this).attr("id")); id='+response.data[i]['cust_num']+'>删除</a>'+'</td>');
                        }
                    }else if (k=='cust_relation_flag')
                    {
                        if (v=='0')
                        {
                            //还没有添加第二年审人的情况
                            tabletr.append('<td align="center">'+'<a class="btn btn-warning" onclick="add_second($(this));" id='+response.data[i]['cust_num']+'>添加第二年审人</a>'+'</td>');
                        }else
                        {
                            //显示第二年审人姓名
                            $.each(v[0],function (key,value) {
                                if (key=='cust_name')
                                {
                                    tabletr.append('<td align="center">'+value+'</td>');
                                }else if (key=='cust_register_flag')
                                {
                                    if (value=='0')
                                    {
                                        //开始注册按钮
                                        tabletr.append('<td align="center">'+'<a class="btn btn-success" onclick=api_register($(this).attr("id")); id='+v[0]['cust_num']+'>开始注册</a>'+'</td>');
                                    }else if (value=='3')
                                    {
                                        //客户已去世
                                        tabletr.append('<td align="center">'+'客户已去世'+'</td>');
                                    }else
                                    {
                                        //验证，删除按钮
                                        tabletr.append('<td align="center">'+'<a class="btn btn-info" onclick=api_verify($(this).attr("id")); id='+v[0]['cust_num']+'>验证</a>'+'<a class="btn btn-danger" onclick=delete_cust_voice($(this).attr("id")); id='+v[0]['cust_num']+'>删除</a>'+'</td>');
                                    }
                                }
                            });
                        }
                    }else
                    {
                        tabletr.append('<td align="center">'+v+'</td>');
                    }

                });

                $("#add_cust_table tbody").append(tabletr);

            }

            //显示分页
            laypage({
                cont: 'laypage1', //容器
                pages: response.pages, //通过后台拿到的总页数
                curr: curr || 1, //当前页
                jump: function(obj, first){ //触发分页后的回调
                    if(!first){ //点击跳页触发函数自身，并传递当前页：obj.curr
                        refresh_A(obj.curr);
                    }
                }
            });

        }else
        {
            layer.alert(response.msg);
        }

    },'json');

}

function refresh_B(curr) {

    $("#add_cust_table tbody").children().remove();

    //显示这个用户，今日处理过的用户，往日的不显示
    var url ='/data/ajax';
    var data={
        _token:$("input[name=_token]").val(),
        page:curr||1,
        type  :'refresh_B'
    };

    $.post(url,data,function (response) {

        if(response.error=='0')
        {
            layer.msg(response.msg);

            //遍历返回的数据-表内容
            for(var i=0;i<response.data.length;i++){

                var tabletr=$("<tr></tr>");

                $.each(response.data[i],function (k,v) {

                    if (k=='cust_register_flag')
                    {
                        if (v=='0')
                        {
                            //开始注册按钮
                            tabletr.append('<td align="center">'+'<a class="btn btn-success" onclick=api_register($(this).attr("id")); id='+response.data[i]['cust_num']+'>开始注册</a>'+'</td>');
                        }else if (v=='3')
                        {
                            //客户已去世
                            tabletr.append('<td align="center">'+'客户已去世'+'</td>');
                        }else
                        {
                            //验证，删除按钮
                            tabletr.append('<td align="center">'+'<a class="btn btn-info" onclick=api_verify($(this).attr("id")); id='+response.data[i]['cust_num']+'>验证</a>'+'<a class="btn btn-danger" onclick=delete_cust_voice($(this).attr("id")); id='+response.data[i]['cust_num']+'>删除</a>'+'</td>');
                        }
                    }else if (k=='cust_relation_flag')
                    {
                        if (v=='0')
                        {
                            //还没有添加第二年审人的情况
                            tabletr.append('<td align="center">'+'<a class="btn btn-warning" onclick="add_second($(this));" id='+response.data[i]['cust_num']+'>添加第二年审人</a>'+'</td>');
                        }else
                        {
                            //显示第二年审人姓名
                            $.each(v[0],function (key,value) {
                                if (key=='cust_name')
                                {
                                    tabletr.append('<td align="center">'+value+'</td>');
                                }else if (key=='cust_register_flag')
                                {
                                    if (value=='0')
                                    {
                                        //开始注册按钮
                                        tabletr.append('<td align="center">'+'<a class="btn btn-success" onclick=api_register($(this).attr("id")); id='+v[0]['cust_num']+'>开始注册</a>'+'</td>');
                                    }else if (value=='3')
                                    {
                                        //客户已去世
                                        tabletr.append('<td align="center">'+'客户已去世'+'</td>');
                                    }else
                                    {
                                        //验证，删除按钮
                                        tabletr.append('<td align="center">'+'<a class="btn btn-info" onclick=api_verify($(this).attr("id")); id='+v[0]['cust_num']+'>验证</a>'+'<a class="btn btn-danger" onclick=delete_cust_voice($(this).attr("id")); id='+v[0]['cust_num']+'>删除</a>'+'</td>');
                                    }
                                }
                            });
                        }
                    }else
                    {
                        tabletr.append('<td align="center">'+v+'</td>');
                    }

                });

                $("#add_cust_table tbody").append(tabletr);

            }

            //显示分页
            laypage({
                cont: 'laypage1', //容器
                pages: response.pages, //通过后台拿到的总页数
                curr: curr || 1, //当前页
                jump: function(obj, first){ //触发分页后的回调
                    if(!first){ //点击跳页触发函数自身，并传递当前页：obj.curr
                        refresh_A(obj.curr);
                    }
                }
            });

        }else
        {
            layer.alert(response.msg);
        }

    },'json');

}

function add_second(id) {

    layer.open({
        type: 2,
        title: '添加第二年审人信息',
        maxmin:false,//是否显示最大化最小化按钮
        resize:false,//窗口是否可以拉伸
        shadeClose: true, //点击遮罩关闭层
        scrollbar:false,//是否允许显示滚动条
        fixed:true,
        area:['850px','600px'],
        content: '/add/second?id='+id.attr("id")
    });

}

function select_data_A(curr) {

    $("#add_cust_table tbody").children().remove();

    var url ='/data/ajax';
    var data={
        _token:$("input[name=_token]").val(),
        page:curr||1,
        type  :'select_data_A',
        key   :$("#myform2").serializeArray()
    };

    $.post(url,data,function (response) {

        if(response.error=='0')
        {
            layer.msg(response.msg);

            //遍历返回的数据-表内容
            for(var i=0;i<response.data.length;i++){

                var tabletr=$("<tr></tr>");

                $.each(response.data[i],function (k,v) {

                    if (k=='cust_register_flag')
                    {
                        if (v=='0')
                        {
                            //开始注册按钮
                            tabletr.append('<td align="center">'+'<a class="btn btn-success" onclick=api_register($(this).attr("id")); id='+response.data[i]['cust_num']+'>开始注册</a>'+'</td>');
                        }else if (v=='3')
                        {
                            //客户已去世
                            tabletr.append('<td align="center">'+'客户已去世'+'</td>');
                        }else
                        {
                            //验证，删除按钮
                            tabletr.append('<td align="center">'+'<a class="btn btn-info" onclick=api_verify($(this).attr("id")); id='+response.data[i]['cust_num']+'>验证</a>'+'<a class="btn btn-danger" onclick=delete_cust_voice($(this).attr("id")); id='+response.data[i]['cust_num']+'>删除</a>'+'</td>');
                        }
                    }else if (k=='cust_relation_flag')
                    {
                        if (v=='0')
                        {
                            //还没有添加第二年审人的情况
                            tabletr.append('<td align="center">'+'<a class="btn btn-warning" onclick="add_second($(this));" id='+response.data[i]['cust_num']+'>添加第二年审人</a>'+'</td>');
                        }else
                        {
                            //显示第二年审人姓名
                            $.each(v[0],function (key,value) {
                                if (key=='cust_name')
                                {
                                    tabletr.append('<td align="center">'+value+'</td>');
                                }else if (key=='cust_register_flag')
                                {
                                    if (value=='0')
                                    {
                                        //开始注册按钮
                                        tabletr.append('<td align="center">'+'<a class="btn btn-success" onclick=api_register($(this).attr("id")); id='+v[0]['cust_num']+'>开始注册</a>'+'</td>');
                                    }else if (value=='3')
                                    {
                                        //客户已去世
                                        tabletr.append('<td align="center">'+'客户已去世'+'</td>');
                                    }else
                                    {
                                        //验证，删除按钮
                                        tabletr.append('<td align="center">'+'<a class="btn btn-info" onclick=api_verify($(this).attr("id")); id='+v[0]['cust_num']+'>验证</a>'+'<a class="btn btn-danger" onclick=delete_cust_voice($(this).attr("id")); id='+v[0]['cust_num']+'>删除</a>'+'</td>');
                                    }
                                }
                            });
                        }
                    }else
                    {
                        tabletr.append('<td align="center">'+v+'</td>');
                    }

                });

                $("#add_cust_table tbody").append(tabletr);

            }

            //显示分页
            laypage({
                cont: 'laypage1', //容器
                pages: response.pages, //通过后台拿到的总页数
                curr: curr || 1, //当前页
                jump: function(obj, first){ //触发分页后的回调
                    if(!first){ //点击跳页触发函数自身，并传递当前页：obj.curr
                        select_data_A(obj.curr);
                    }
                }
            });

        }else
        {
            layer.alert(response.msg);
        }

    },'json');

}

function select_data_B(curr) {

    $("#add_cust_table tbody").children().remove();

    var url ='/data/ajax';
    var data={
        _token:$("input[name=_token]").val(),
        page:curr||1,
        type  :'select_data_B',
        key   :$("#myform2").serializeArray()
    };

    $.post(url,data,function (response) {

        if(response.error=='0')
        {
            layer.msg(response.msg);

            //遍历返回的数据-表内容
            for(var i=0;i<response.data.length;i++){

                var tabletr=$("<tr></tr>");

                $.each(response.data[i],function (k,v) {

                    if (k=='cust_register_flag')
                    {
                        if (v=='0')
                        {
                            //开始注册按钮
                            tabletr.append('<td align="center">'+'<a class="btn btn-success" onclick=api_register($(this).attr("id")); id='+response.data[i]['cust_num']+'>开始注册</a>'+'</td>');
                        }else if (v=='3')
                        {
                            //客户已去世
                            tabletr.append('<td align="center">'+'客户已去世'+'</td>');
                        }else
                        {
                            //验证，删除按钮
                            tabletr.append('<td align="center">'+'<a class="btn btn-info" onclick=api_verify($(this).attr("id")); id='+response.data[i]['cust_num']+'>验证</a>'+'<a class="btn btn-danger" onclick=delete_cust_voice($(this).attr("id")); id='+response.data[i]['cust_num']+'>删除</a>'+'</td>');
                        }
                    }else if (k=='cust_relation_flag')
                    {
                        if (v=='0')
                        {
                            //还没有添加第二年审人的情况
                            tabletr.append('<td align="center">'+'<a class="btn btn-warning" onclick="add_second($(this));" id='+response.data[i]['cust_num']+'>添加第二年审人</a>'+'</td>');
                        }else
                        {
                            //显示第二年审人姓名
                            $.each(v[0],function (key,value) {
                                if (key=='cust_name')
                                {
                                    tabletr.append('<td align="center">'+value+'</td>');
                                }else if (key=='cust_register_flag')
                                {
                                    if (value=='0')
                                    {
                                        //开始注册按钮
                                        tabletr.append('<td align="center">'+'<a class="btn btn-success" onclick=api_register($(this).attr("id")); id='+v[0]['cust_num']+'>开始注册</a>'+'</td>');
                                    }else if (value=='3')
                                    {
                                        //客户已去世
                                        tabletr.append('<td align="center">'+'客户已去世'+'</td>');
                                    }else
                                    {
                                        //验证，删除按钮
                                        tabletr.append('<td align="center">'+'<a class="btn btn-info" onclick=api_verify($(this).attr("id")); id='+v[0]['cust_num']+'>验证</a>'+'<a class="btn btn-danger" onclick=delete_cust_voice($(this).attr("id")); id='+v[0]['cust_num']+'>删除</a>'+'</td>');
                                    }
                                }
                            });
                        }
                    }else
                    {
                        tabletr.append('<td align="center">'+v+'</td>');
                    }

                });

                $("#add_cust_table tbody").append(tabletr);

            }

            //显示分页
            laypage({
                cont: 'laypage1', //容器
                pages: response.pages, //通过后台拿到的总页数
                curr: curr || 1, //当前页
                jump: function(obj, first){ //触发分页后的回调
                    if(!first){ //点击跳页触发函数自身，并传递当前页：obj.curr
                        select_data_B(obj.curr);
                    }
                }
            });

        }else
        {
            layer.alert(response.msg);
        }

    },'json');

}

function tell_ivr_make_phone_call(id,phone) {

    location.assign('/add/cust');

}

function statistics_change(curr) {

    $("#statistics_table tbody").children().remove();

    var url ='/data/ajax';
    var data={
        _token:$("input[name=_token]").val(),
        page  :curr||1,
        type  :'statistics_change',
        key   :$("#statistics_form").serializeArray()
    };

    $.post(url,data,function (response) {

        if(response.error=='0')
        {
            layer.msg(response.msg);

            $("#data_total").html(response.count_data);

            //遍历返回的数据-表内容
            for(var i=0;i<response.data.length;i++){

                var tabletr=$("<tr></tr>");

                $.each(response.data[i],function (k,v) {

                    tabletr.append('<td align="center">'+v+'</td>');

                });

                $("#statistics_table tbody").append(tabletr);

            }

            //显示分页
            laypage({
                cont: 'statistics_laypage', //容器
                pages: response.pages, //通过后台拿到的总页数
                curr: curr || 1, //当前页
                jump: function(obj, first){ //触发分页后的回调
                    if(!first){ //点击跳页触发函数自身，并传递当前页：obj.curr
                        statistics_change(obj.curr);
                    }
                }
            });

        }else
        {
            layer.msg(response.msg);
        }

    },'json');

}

function service_care_change(curr) {

    $("#service_care_table tbody").children().remove();
    $("#excel_file_download").children().remove();

    var url ='/data/ajax';
    var data={
        _token:$("input[name=_token]").val(),
        page  :curr||1,
        type  :'service_care_change',
        key   :$("#service_care_form").serializeArray(),
        tip   :'0'//选择卡的第几个页面
    };

    $.post(url,data,function (response) {

        if(response.error=='0')
        {
            layer.msg(response.msg);

            //把redis_key给前台加上
            $("#daochu").attr("name",response.redis_key);

            $("#data_total").html(response.count_data);

            //遍历返回的数据-表内容
            for(var i=0;i<response.data.length;i++){

                var tabletr=$("<tr></tr>");
                var id='0';

                $.each(response.data[i],function (k,v) {

                    if(k=='confirm_num')
                    {
                        tabletr.attr('id',v);
                        id=v;
                    }else if(k=='confirm_btw')
                    {
                        if(v=='')
                        {
                            //没有备注的情况
                            tabletr.append('<td align=center><i onclick=change_btw($(this).attr("id")); id='+id+' class="fa fa-edit"></i></td>');
                        }else
                        {
                            //有备注的情况
                            tabletr.append('<td onclick=change_btw($(this).attr("id")); id='+id+' align="center">'+v+'</td>');
                        }

                    }else if(k=='cust_review_num' || k=='cust_phone_num')
                    {
                        //给年审号和备用号码添加js方法，用来传递给ivr外呼
                        tabletr.append('<td ondblclick=tell_ivr_make_phone_call($(this).attr("id"),$(this).html()); id='+id+' align="center">'+v+'</td>');

                    }else
                    {
                        tabletr.append('<td align="center">'+v+'</td>');
                    }

                });

                $("#service_care_table tbody").append(tabletr);

            }

            //显示分页
            laypage({
                cont: 'service_care_laypage', //容器
                pages: response.pages, //通过后台拿到的总页数
                curr: curr || 1, //当前页
                jump: function(obj, first){ //触发分页后的回调
                    if(!first){ //点击跳页触发函数自身，并传递当前页：obj.curr
                        service_care_change(obj.curr);
                    }
                }
            });

        }else
        {
            layer.msg(response.msg);
        }

    },'json');

}

function service_care_change_1(curr) {

    $("#service_care_table tbody").children().remove();

    var url ='/data/ajax';
    var data={
        _token:$("input[name=_token]").val(),
        page  :curr||1,
        type  :'service_care_change',
        key   :$("#service_care_form_1").serializeArray(),
        tip   :'1'
    };

    $.post(url,data,function (response) {

        if(response.error=='0')
        {
            layer.msg(response.msg);

            $("#data_total").html(response.count_data);

            //遍历返回的数据-表内容
            for(var i=0;i<response.data.length;i++){

                var tabletr=$("<tr></tr>");
                var id='0';

                $.each(response.data[i],function (k,v) {

                    if(k=='confirm_num')
                    {
                        tabletr.attr('id',v);
                        id=v;
                    }else if(k=='confirm_btw')
                    {
                        if(v=='')
                        {
                            //没有备注的情况
                            tabletr.append('<td align=center><i onclick=change_btw($(this).attr("id")); id='+id+' class="fa fa-edit"></i></td>');
                        }else
                        {
                            //有备注的情况
                            tabletr.append('<td onclick=change_btw($(this).attr("id")); id='+id+' align="center">'+v+'</td>');
                        }

                    }else
                    {
                        tabletr.append('<td align="center">'+v+'</td>');
                    }

                });

                $("#service_care_table tbody").append(tabletr);

            }

            //显示分页
            laypage({
                cont: 'service_care_laypage', //容器
                pages: response.pages, //通过后台拿到的总页数
                curr: curr || 1, //当前页
                jump: function(obj, first){ //触发分页后的回调
                    if(!first){ //点击跳页触发函数自身，并传递当前页：obj.curr
                        service_care_change_1(obj.curr);
                    }
                }
            });

        }else
        {
            layer.msg(response.msg);
        }

    },'json');

}

function getMySlider1(cond) {

    $("#myslider1").children().remove();
    $("#myslider1").slider(cond);

    $("#myslider1_count").css("color","red");
    $(".slider-selection").css("background","red");

    $("#myslider1").on("slide", function(slideEvt) {
        $("#myslider1_count").text(slideEvt.value);
    });

}

function allocation() {

    var url ='/data/ajax';
    var data={
        _token :$("input[name=_token]").val(),
        type   :'allocation',
        key1   :$("#staff_form").serializeArray(),
        key2   :$("#myslider1_count").html(),
        key3   :$("#allocation_form").serializeArray()
    };

    $.post(url,data,function (response) {

        if(response.error=='0')
        {
            layer.msg(response.msg);
            location.reload();
        }else
        {
            layer.msg(response.msg);
        }

    },'json');

}

function allocation_change(curr) {

    $("#allocation_table tbody").children().remove();

    var url ='/data/ajax';
    var data={
        _token:$("input[name=_token]").val(),
        page  :curr||1,
        type  :'allocation_change',
        key   :$("#allocation_form").serializeArray(),
        tip   :'0'
    };

    $.post(url,data,function (response) {

        if(response.error=='0')
        {
            layer.msg(response.msg);

            //创建滚动条************************************
            $("#myslider1_count").html(response.count_data);
            getMySlider1({
                min:0,
                max:response.count_data,
                step:1,
                value:response.count_data
            });
            //**********************************************

            //遍历返回的员工信息
            for(var i=0;i<response.staff.length;i++)
            {
                var tabletr=$("<tr></tr>");
                
                $.each(response.staff[i],function (index,value) {

                    if(index=='staff_num')
                    {
                        tabletr.append('<td align=center><input type="radio" name="myRadios" value='+value+'></td>');
                    }else if(index=='mission')
                    {
                        tabletr.append('<td align=center>'+''+'</td>');
                    }else
                    {
                        tabletr.append('<td align=center>'+value+'</td>');
                    }

                });

                $("#allocation_table tbody").append(tabletr);

            }

            //显示分页
            laypage({
                cont: 'allocation_laypage', //容器
                pages: response.pages, //通过后台拿到的总页数
                curr: curr || 1, //当前页
                jump: function(obj, first){ //触发分页后的回调
                    if(!first){ //点击跳页触发函数自身，并传递当前页：obj.curr
                        allocation_change(obj.curr);
                    }
                }
            });

        }else
        {
            layer.msg(response.msg);
        }

    },'json');

}

function analysis_change() {

    $("#line-chart").children().remove();
    $("#data_total").html(0);

    var url ='/data/ajax';
    var data={
        _token:$("input[name=_token]").val(),
        type  :'analysis_change',
        key   :$("#analysis_form").serializeArray()
    };

    $.post(url,data,function (response) {

        if(response.error=='0')
        {
            layer.msg(response.msg);

            $("#data_total").html(response.data_total);

            //设置morris的数据
            var line = new Morris.Line({
                element: 'line-chart',
                resize: true,
                data: response.data,
                xkey: 'y',
                //dateFormat:function(x){return new Date(x).toString();},
                //xLabelFormat:function(x)
                //{
                //    var day=new Date(x);
                //    return day.getDay().toString();
                //},
                hoverCallback:function(index,options,content,row)
                {
                    return content;
                },
                ykeys: ['mytarget'],
                labels: ['当天数量'],
                lineColors: ['red'],
                hideHover: 'auto'
            });
        }else
        {
            layer.msg(response.msg);
        }

    },'json');


}

function api_register(id) {

    var url ='/api/register';
    var data={
        _token:$("input[name=_token]").val(),
        type  :'register',
        key   :id
    };

    $.post(url,data,function (response) {

        if(response.error=='0')
        {
            layer.msg(response.msg);
        }else
        {
            layer.msg(response.msg);
        }

    },'json');

}

function delete_cust_voice(id) {

    var url ='/data/ajax';
    var data={
        _token:$("input[name=_token]").val(),
        type  :'delete_cust_voice',
        key   :id
    };

    $.post(url,data,function (response) {

        if(response.error=='0')
        {
            layer.msg(response.msg);
            location.reload();
        }else
        {
            layer.msg(response.msg);
        }

    },'json');

}

function api_verify(id) {

    var url ='/api/verify';
    var data={
        _token:$("input[name=_token]").val(),
        type  :'verify',
        key   :id
    };

    $.post(url,data,function (response) {

        if(response.error=='0')
        {
            layer.msg(response.msg);
        }else
        {
            layer.msg(response.msg);
        }

    },'json');

}

function detail_info(id) {

    //传入的是redis的key
    layer.open({
        type: 2,
        title: '详细信息',
        shadeClose: true,
        shade: 0.8,
        area: ['500px', '250px'],
        resize:false,
        content: ['/detail_info/'+id,'no'] //iframe的url
    });

}

function change_btw(id) {

    //传入的是customer_confirm的主键
    layer.open({
        type: 2,
        title: '修改备注信息',
        shadeClose: true,
        shade: 0.8,
        area: ['400px', '300px'],
        resize:false,
        content: ['/change_btw/'+id,'no'] //iframe的url
    });

}

function aaa() {

    var url ='/data/ajax';
    var data={
        _token:$("input[name=_token]").val(),
        type  :'modify_btw',
        key   :$("#change_btw_form").serializeArray()
    };

    $.post(url,data,function (response) {

        if(response.error=='0')
        {
            layer.msg(response.msg);
            parent.layer.closeAll();
        }else
        {
            layer.msg(response.msg);
        }

    },'json');

}

function get_register_verify_mongo_data(curr) {

    $("#loop_table tbody").children().remove();

    var url ='/api/ajax';
    var data={
        _token:$("input[name=_token]").val(),
        page  :curr||1,
        type  :'get_register_verify_mongo_data'
    };

    $.post(url,data,function (response) {

        if(response.error=='0')
        {
            $("#data_total").html(response.count_data);

            //遍历返回的数据-表内容
            for(var i=0;i<response.data.length;i++) {

                var tabletr = $("<tr></tr>");

                $.each(response.data[i], function (k, v) {

                    tabletr.append('<td align="center">' + v + '</td>');

                });

                $("#loop_table tbody").append(tabletr);

                //显示分页
                laypage({
                    cont: 'loop_laypage', //容器
                    pages: response.pages, //通过后台拿到的总页数
                    curr: curr || 1, //当前页
                    jump: function(obj, first){ //触发分页后的回调
                        if(!first){ //点击跳页触发函数自身，并传递当前页：obj.curr
                            get_register_verify_mongo_data(obj.curr);
                        }
                    }
                });
            }

        }else
        {
            layer.msg(response.msg);
        }

    },'json');

}

function show_system_log(curr) {

    $("#show_system_log_table tbody").children().remove();

    var url ='/data/ajax';
    var data={
        _token:$("input[name=_token]").val(),
        page  :curr||1,
        type  :'get_show_system_log_data'
    };

    $.post(url,data,function (response) {

        if(response.error=='0')
        {
            $("#data_total").html(response.count_data);

            //遍历返回的数据-表内容
            for(var i=0;i<response.data.length;i++) {

                var tabletr = $("<tr></tr>");

                $.each(response.data[i], function (k, v) {

                    tabletr.append('<td align="center">' + v + '</td>');

                });

                $("#show_system_log_table tbody").append(tabletr);

                //显示分页
                laypage({
                    cont: 'show_system_log_laypage', //容器
                    pages: response.pages, //通过后台拿到的总页数
                    curr: curr || 1, //当前页
                    jump: function(obj, first){ //触发分页后的回调
                        if(!first){ //点击跳页触发函数自身，并传递当前页：obj.curr
                            show_system_log(obj.curr);
                        }
                    }
                });
            }

        }else
        {
            layer.msg(response.msg);
        }

    },'json');

}

function show_staff_list(curr) {

    $("#show_staff_list_table tbody").children().remove();

    var url ='/data/ajax';
    var data={
        _token:$("input[name=_token]").val(),
        page  :curr||1,
        type  :'get_show_staff_list_data'
    };

    $.post(url,data,function (response) {

        if(response.error=='0')
        {
            $("#data_total").html(response.count_data);

            //遍历返回的数据-表内容
            for(var i=0;i<response.data.length;i++) {

                var tabletr = $("<tr></tr>");

                $.each(response.data[i], function (k, v) {

                    tabletr.append('<td align="center">' + v + '</td>');

                });

                $("#show_staff_list_table tbody").append(tabletr);

                //显示分页
                laypage({
                    cont: 'show_staff_list_laypage', //容器
                    pages: response.pages, //通过后台拿到的总页数
                    curr: curr || 1, //当前页
                    jump: function(obj, first){ //触发分页后的回调
                        if(!first){ //点击跳页触发函数自身，并传递当前页：obj.curr
                            show_staff_list(obj.curr);
                        }
                    }
                });
            }

        }else
        {
            layer.msg(response.msg);
        }

    },'json');

}

function get_loop_return_mongo_data(curr) {

    $("#loop_table tbody").children().remove();

    var url ='/api/ajax';
    var data={
        _token:$("input[name=_token]").val(),
        page  :curr||1,
        type  :'get_loop_return_mongo_data'
    };

    $.post(url,data,function (response) {

        if(response.error=='0')
        {
            $("#data_total").html(response.count_data);

            //遍历返回的数据-表内容
            for(var i=0;i<response.data.length;i++) {

                var tabletr = $("<tr></tr>");

                $.each(response.data[i], function (k, v) {

                    tabletr.append('<td align="center">' + v + '</td>');

                });

                $("#loop_table tbody").append(tabletr);

                //显示分页
                laypage({
                    cont: 'loop_laypage', //容器
                    pages: response.pages, //通过后台拿到的总页数
                    curr: curr || 1, //当前页
                    jump: function(obj, first){ //触发分页后的回调
                        if(!first){ //点击跳页触发函数自身，并传递当前页：obj.curr
                            get_loop_return_mongo_data(obj.curr);
                        }
                    }
                });
            }

        }else
        {
            layer.msg(response.msg);
        }

    },'json');

}

function get_loop_mongo_data(curr) {

    $("#loop_table tbody").children().remove();

    var url ='/api/ajax';
    var data={
        _token:$("input[name=_token]").val(),
        page  :curr||1,
        type  :'get_loop_mongo_data'
    };

    $.post(url,data,function (response) {

        if(response.error=='0')
        {
            $("#data_total").html(response.count_data);

            //遍历返回的数据-表内容
            for(var i=0;i<response.data.length;i++) {

                var tabletr = $("<tr></tr>");

                $.each(response.data[i], function (k, v) {

                    if (k=='finishANDtotal' && v!='')
                    {
                        tabletr.append('<td align="center"><a onclick=detail_info('+v+'); href=#>' + '详细信息' + '</a></td>');
                    }else
                    {
                        tabletr.append('<td align="center">' + v + '</td>');
                    }

                });

                $("#loop_table tbody").append(tabletr);

                //显示分页
                laypage({
                    cont: 'loop_laypage', //容器
                    pages: response.pages, //通过后台拿到的总页数
                    curr: curr || 1, //当前页
                    jump: function(obj, first){ //触发分页后的回调
                        if(!first){ //点击跳页触发函数自身，并传递当前页：obj.curr
                            get_loop_mongo_data(obj.curr);
                        }
                    }
                });
            }

        }else
        {
            layer.msg(response.msg);
        }

    },'json');

}

function set_config() {

    var url='/data/ajax';
    var data={
        _token:$("input[name=_token]").val(),
        key:$("#edit_config_form").serializeArray(),
        type:'set_config'
    };

    $.post(url,data,function (response) {

        if(response.error=='0')
        {
            layer.msg("跑到了");
        }else
        {
            layer.msg("未知错误");
        }

    },'json');

}

function select_china_all_position() {

    $("#daochudiqu_lable").val('xxx');

    var url='/data/ajax';
    var data={
        _token:$("input[name=_token]").val(),
        key:$("#select_china_all_position_form").serializeArray(),
        type:'select_china_all_position'
    };

    $.post(url,data,function (response) {

        if(response.error=='0')
        {
            //解析res_single
            $.each(response.res_single, function (k, v) {

                $("select[name=s_"+k+"] option").remove();

                $.each(v,function (key,value) {

                    $("select[name=s_"+k+"]").append("<option>"+value[k]+"</option>>");

                });

            });

            //解析res_all
            if(response.res_all)
            {
                $("#daochudiqu_lable").val('project_for_redis');

                $("select[name=all_path_name] option").remove();

                $.each(response.res_all, function (key, value) {

                    $("select[name=all_path_name]").append("<option>"+
                        value['province_name']+'-'+
                        value['city_name']+'-'+
                        value['county_name']+'-'+
                        value['town_name']+'-'+
                        value['village_name']+"</option>");
                });
            }

            layer.msg(response.msg);
        }else
        {
            layer.msg(response.msg);
        }

    },'json');

}




















