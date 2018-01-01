@extends('layouts.plane')
@section('body')

    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">请选择一个地区</h3>
                    </div>

                    <div style="height:50px;width:100%;position:fixed;z-index: 999;">
                        <div class="col-sm-12">
                            <div class="row">
                                <div style="text-align: center" class="col-sm-12">
                                    <a href="javascript:;" id="transmit" class="btn btn-primary">选择这个地区</a>
                                    <a href="javascript:;" onclick="select_node($('#select_str').val());" class="btn btn-primary">查找一个地区</a>
                                    <input style="text-align: center" id="select_str" class="input" size="15" type="text" placeholder="输入要查找的地区">
                                </div>
                            </div>
                        </div>
                    </div>
                    {{csrf_field()}}

                    {{--自动吸顶--}}
                    <div style="height: 50px;width: 100px" class="col-sm-12"></div>

                    <form role="form">
                        <div style="z-index: 1;" class="box-body">
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

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>

        function select_node(str) {

            if (str=='')
            {
                layer.msg('没有输入要查询的地区');
                return;
            }

            //得到ztree对象
            var myztree=$.fn.zTree.getZTreeObj("ztree_project");

            var mynodes=myztree.getNodesByParamFuzzy("name",str,null);

            $.each(mynodes,function(key,value)
            {
                myztree.selectNode(value);
                //myztree.checkNode(value,true,true);

                value.checked=true;
                myztree.updateNode(value);
            });

        }

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
                time   :360000,
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
