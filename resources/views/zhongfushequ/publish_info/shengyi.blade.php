@extends('layouts.dashboard')
@section('page_heading','欢迎登陆中服社区')
@section('page_heading_small','生意登记页')
@section('section')

    <form id="shengyi">
        {{csrf_field()}}
        <div class="box" style="width: 1000px">
            <div class="box-body">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <td align="center">
                            生意需求人
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="xingming" placeholder="姓名">
                        </td>
                        <td align="center">
                            性别
                        </td>
                        <td>
                            <select style="padding-left: 8px" name="xingbie" class="form-control">
                                <option value="男">男</option>
                                <option value="女">女</option>
                            </select>
                        </td>
                        <td align="center">
                            年龄
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="nianling" placeholder="年龄">
                        </td>
                    </tr>

                    <tr>
                        <td align="center">
                            手机号
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="shoujihao" placeholder="手机号">
                        </td>
                        <td align="center">
                            微信
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="weixin" placeholder="微信">
                        </td>
                        <td align="center">
                            QQ
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="QQ" placeholder="QQ">
                        </td>
                    </tr>

                    <tr>
                        <td align="center">
                            生意所属类别
                        </td>
                        <td>
                            <select style="padding-left: 8px" name="shengyisuoshuleibie" class="form-control">
                                <option value="合作需求">合作需求</option>
                                <option value="资金需求">资金需求</option>
                                <option value="人才需求">人才需求</option>
                                <option value="其他">其他</option>
                            </select>
                        </td>
                        <td align="center">
                            生意所属行业
                        </td>
                        <td colspan="3">
                            <input type="text" class="form-control" id="" name="shengyisuoshuhangye" placeholder="生意所属行业">
                        </td>
                    </tr>

                    <tr>
                        <td align="center">
                            生意描述
                        </td>
                        <td colspan="5">
                            <input type="text" class="form-control" id="" name="shengyimiaoshu" placeholder="生意描述">
                        </td>
                    </tr>

                    <tr>
                        <td align="center" colspan="6">
                            <a style="width: 100px;" onclick="add_shengyi();" class="btn btn-block btn-primary btn-sm">登记</a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </form>





    <script>

        function add_shengyi() {

            var url='/publishinfo/ajax';
            var dat={
                _token :$("input[name=_token]").val(),
                type   :'publish_shengyi',
                key    :$("#shengyi").serializeArray()
            };

            $.post(url,dat,function (response) {

                if (response.error=='0')
                {
                    layer.msg(response.msg);
                    location.reload();
                }else
                {
                    layer.msg(response.msg);
                }

            },'json');


        }


    </script>


@stop
