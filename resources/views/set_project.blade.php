@extends('layouts.dashboard')
@section('page_heading','系统设置')
@section('page_heading_small','设置所属地区')
@section('section')

    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title">请认真填写</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form id="add_project_form" class="form-horizontal">
            <div class="box-body">
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">属地名称</label>

                    <div class="col-sm-10">
                        <input style="padding-left: 16px;" type="text" class="form-control" id="inputEmail3" name="project_name" placeholder="属地名称">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword3" class="col-sm-2 control-label">把该属地添加到某属地下</label>

                    <div class="col-sm-10">
                        <div>
                            <ul id="ztree_project" class="ztree"></ul>
                            <input type="hidden" name="project_parent" value=""/>
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
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-6">
                            <div align="center">
                                <a onclick="add_project();" class="btn btn-info">添加新的属地</a>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div align="center">
                                <a href="{{url('/import/new/project')}}" class="btn btn-info">导入新的属地</a>
                            </div>
                        </div>
                    </div>
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
                type  :'get_project'
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

                    $.fn.zTree.init($("#ztree_project"),setting,response.data);
                    
                    function onChecked(e,treeId,treeNode) {
                        $("input[name=project_parent]").val(treeNode.id);
                    }

                }else
                {
                    layer.alert(response.msg);
                }

            },'json');
        });

    </script>


@stop
