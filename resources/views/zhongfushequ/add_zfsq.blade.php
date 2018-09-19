@extends('layouts.dashboard')
@section('page_heading','欢迎登陆中服社区')
@section('page_heading_small','用户登记页')
@section('section')

    {{csrf_field()}}
    <form id="jichu" style="width: 1000px">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">基础信息登记</h3>
            </div>
            <div class="box-body">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <td>
                            <div>
                                <input type="text" class="form-control" id="" name="xingming" placeholder="姓名">
                            </div>
                        </td>
                        <td>
                            <div>
                                <select style="padding-left: 8px" name="jueseleixing" class="form-control">
                                    <option value="">角色类型</option>
                                    <option value="认证客户">认证客户</option>
                                    <option value="配偶">配偶</option>
                                    <option value="子女">子女</option>
                                    <option value="父母">父母</option>
                                    <option value="其他">其他</option>
                                </select>
                            </div>
                        </td>
                        <td>
                            <div>
                                <input type="text" class="form-control" id="" name="xingbie" placeholder="性别">
                            </div>
                        </td>
                        <td>
                            <div>
                                <input type="text" class="form-control" id="" name="chushengnianyue" placeholder="出生年月-如19990101">
                            </div>
                        </td>
                        <td>
                            <div>
                                <input type="text" class="form-control" id="" name="lianxidianhua" placeholder="联系电话">
                            </div>
                        </td>
                    <tr>
                    <tr>
                        <td>
                            <div>
                                <select style="padding-left: 8px" name="congshihangye" class="form-control">
                                    <option value="">从事行业</option>
                                    <option value="工作单位">工作单位</option>
                                    <option value="自由职业">自由职业</option>
                                    <option value="个体经营">个体经营</option>
                                </select>
                            </div>
                        </td>
                        <td colspan="2">
                            <div>
                                <input type="text" class="form-control" id="" name="juticongshi" placeholder="具体从事">
                            </div>
                        </td>
                        <td colspan="2">
                            <div>
                                <input type="text" class="form-control" id="" name="zhuzhi" placeholder="住址">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" colspan="5">
                            <a style="width: 100px;" onclick="add_jichu();" class="btn btn-block btn-primary btn-sm">登记</a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </form>

    <div class="box" style="width: 1000px">
        <div class="box-header with-border">
            <h3 class="box-title">信息登记</h3>
        </div>
        <div class="box-body">
            <table class="table table-bordered">
                <tbody>
                <tr>
                    <td style="text-align: center">
                        <a href="wugong">务工登记</a>
                    </td>
                    <td style="text-align: center">
                        <a href="shengyi">生意登记</a>
                    </td>
                    <td style="text-align: center">
                        <a href="baoxian">保险登记</a>
                    </td>
                    <td style="text-align: center">
                        <a href="chanpin">产品登记</a>
                    </td>
                    <td style="text-align: center">
                        <a href="jiatingzhuangxiu">家庭装修登记</a>
                    </td>
                    <td style="text-align: center">
                        <a href="hunjie">婚介登记</a>
                    </td>
                <tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="box" style="width: 1000px">
        <div class="box-header with-border">
            <h3 class="box-title">信息查询</h3>
        </div>
        <div class="box-body">
            <table class="table table-bordered">
                <tbody>
                <tr>
                    <td align="center" colspan="6">
                        <a style="width: 100px;" href='/zfsq/select' class="btn btn-block btn-primary btn-sm">查询登记信息</a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>

        function add_jichu() {

            var url='/publishinfo/ajax';
            var dat={
                _token :$("input[name=_token]").val(),
                type   :'add_jichu',
                key    :$("#jichu").serializeArray()
            };

            $.post(url,dat,function (response) {

                $("#append_06").children().remove();

                if (response.error=='0')
                {
                    $.each(response.data,function (k,v) {

                        var myrow=$("<div class='row'></div>");

                        myrow.append("<div class='col-sm-2' align='center'>"+v.xingming+"</div>");
                        myrow.append("<div class='col-sm-2' align='center'>"+v.xingbie+"</div>");
                        myrow.append("<div class='col-sm-2' align='center'>"+v.shengao+"</div>");
                        myrow.append("<div class='col-sm-2' align='center'>"+v.tizhong+"</div>");
                        myrow.append("<div class='col-sm-2' align='center'>"+v.chushengnianyue+"</div>");
                        myrow.append("<div class='col-sm-2' align='center'><a href=/show/hunjie/"+v.id+">单击查看</a></div>");

                        $("#append_06").append(myrow);

                    });

                    layer.msg(response.msg);
                }else
                {
                    layer.msg(response.msg);
                }

            },'json');
        }


    </script>


@stop
