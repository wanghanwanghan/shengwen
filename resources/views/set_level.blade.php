@extends('layouts.dashboard')
@section('page_heading','系统设置')
@section('page_heading_small','设置用户权限')
@section('section')

    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">请认真填写</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form id="add_level_form" class="form-horizontal">
            <div class="box-body">
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">权限名称</label>

                    <div class="col-sm-10">
                        <input style="padding-left: 16px;" type="text" class="form-control" id="inputEmail3" name="level_name" placeholder="权限名称">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">把该权限添加到某权限下</label>

                    <div class="col-sm-10">
                        <div>
                            <ul id="ztree_level" class="ztree"></ul>
                            <input type="hidden" name="level_parent" value=""/>
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
                    <a onclick="add_level();" class="btn btn-info">添加新的权限</a>
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
                type  :'get_level'
            };

            $.post(url,data,function (response) {

                if(response.error=='0')
                {
                    var setting = {
                        check: {
                            enable: true,
                            chkStyle: "radio",
                            radioType: "all"
                        },
                        data: {
                            simpleData: {
                                enable: true
                            }
                        },
                        callback: {
                            onCheck: onChecked
                        }
                    };

                    $.fn.zTree.init($("#ztree_level"),setting,response.data);

                    function onChecked(e,treeId,treeNode) {
                        $("input[name=level_parent]").val(treeNode.id);
                    }

                }else
                {
                    layer.alert(response.msg);
                }

            },'json');

        });

    </script>


@stop
