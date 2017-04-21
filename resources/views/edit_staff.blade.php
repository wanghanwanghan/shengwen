@extends('layouts.dashboard')
@section('page_heading','系统设置')
@section('page_heading_small','修改员工信息')
@section('section')

    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">请认真填写</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form id="edit_staff_form" class="form-horizontal">
            <div class="box-body">
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">员工账号</label>

                    <div class="col-sm-10">
                        <input style="padding-left: 16px;" type="text" class="form-control" id="inputEmail3" name="staff_account" placeholder="员工账号">
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputEmail4" class="col-sm-2 control-label">员工密码</label>

                    <div class="col-sm-10">
                        <input style="padding-left: 16px;" type="text" class="form-control" id="inputEmail4" name="staff_password" placeholder="如果不想修改密码，这项不要填">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-12">
                        <div class="col-sm-4">
                            <!--<label class="col-sm-2 control-label">设置新员工所属地区</label>-->
                            <ul id="ztree_staff_project" class="ztree"></ul>
                            <input type="hidden" name="staff_project" value=""/>
                        </div>
                        <div class="col-sm-4">
                            <!--<label class="col-sm-2 control-label">设置新员工参保类型</label>-->
                            <ul id="ztree_staff_si_type" class="ztree"></ul>
                            <input type="hidden" name="staff_si_type" value=""/>
                        </div>
                        <div class="col-sm-4">
                            <!--<label class="col-sm-2 control-label">设置新员工权限信息</label>-->
                            <ul id="ztree_staff_level" class="ztree"></ul>
                            <input type="hidden" name="staff_level" value=""/>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <div class="checkbox">
                            <label>

                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <div align="center">
                    <a onclick="edit_staff();" class="btn btn-info">修改</a>
                </div>
            </div>
            <!-- /.box-footer -->
        </form>
    </div>

    <script>

        $(function () {
            var url ='/data/ajax';
            var data={
                _token:$("input[name=_token]").val(),
                type  :'get_si_type'
            };

            $.post(url,data,function (response) {

                if(response.error=='0')
                {
                    var setting = {
                        check: {
                            enable: true,
                            chkStyle: "checkbox",
                            chkboxType:{ "Y" : "", "N" : "" }
                        },
                        data: {
                            simpleData: {
                                enable: true
                            }
                        }
                    };

                    $.fn.zTree.init($("#ztree_staff_si_type"),setting,response.data);

                }else
                {
                    layer.alert(response.msg);
                }

            },'json');

            var url ='/data/ajax';
            var data={
                _token:$("input[name=_token]").val(),
                type  :'get_level'
            };

            $.post(url,data,function (response) {

                if(response.error=='0')
                {
                    var setting = {
                        check: {
                            enable: true,
                            chkStyle: "checkbox",
                            chkboxType:{ "Y" : "", "N" : "" }
                        },
                        data: {
                            simpleData: {
                                enable: true
                            }
                        }
                    };

                    $.fn.zTree.init($("#ztree_staff_level"),setting,response.data);

                }else
                {
                    layer.alert(response.msg);
                }

            },'json');

            var url ='/data/ajax';
            var data={
                _token:$("input[name=_token]").val(),
                type  :'get_project'
            };

            $.post(url,data,function (response) {

                if(response.error=='0')
                {
                    var setting = {
                        check: {
                            enable: true,
                            chkStyle: "checkbox",
                            chkboxType:{ "Y" : "", "N" : "" }
                        },
                        data: {
                            simpleData: {
                                enable: true
                            }
                        }
                    };

                    $.fn.zTree.init($("#ztree_staff_project"),setting,response.data);

                }else
                {
                    layer.alert(response.msg);
                }

            },'json');

        });

    </script>


@stop
