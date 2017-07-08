@extends('layouts.plane')
@section('body')

    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">请选择一个地区</h3>
                    </div>
                    {{csrf_field()}}
                    <form role="form">
                        <div class="box-body">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="col-sm-3">

                                    </div>
                                    <div class="col-sm-4">
                                        <ul id="ztree_project" class="ztree"></ul>
                                        <input type="hidden" name="project_parent" value=""/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="box-footer">
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <a href="javascript:;" id="transmit" class="btn btn-primary">选择这个地区</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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

        //给父页面传值
        $('#transmit').on('click', function(){

            //传一个值出去
            var url ='/data/ajax';
            var data={
                _token :$("input[name=_token]").val(),
                type   :'set_redis',
                key    :'chose_project',
                //value:$("#chose_project").val(),
                time   :36000,
                proj_id:$("input[name=project_parent]").val()
            };

            $.post(url,data,function (response) {

                if (response.error=='0')
                {
                    parent.$('#parentIframe').text(response.value);
                    parent.$("input[name=cust_project]").val(response.id_in_mysql);
                    parent.layer.closeAll();
                }else
                {

                }

            },'json');
        });

    </script>


@stop
