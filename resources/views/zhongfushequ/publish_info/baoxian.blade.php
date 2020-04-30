@extends('layouts.dashboard')
@section('page_heading','欢迎登陆中服社区')
@section('page_heading_small','保险登记页')
@section('section')

    <form id="baoxian">
        {{csrf_field()}}
        <div class="box" style="width: 1012px">
            <div class="box-body">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <td align="center">
                            购买保险意向人
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
                            希望购买保险类别
                        </td>
                        <td>
                            <select style="padding-left: 8px" name="xiwanggoumaibaoxianleibie" class="form-control">
                                <option value="疾病">疾病</option>
                                <option value="意外">意外</option>
                                <option value="投资">投资</option>
                                <option value="养老">养老</option>
                                <option value="教育">教育</option>
                                <option value="其他">其他</option>
                            </select>
                        </td>
                        <td align="center">
                            希望购买保险公司
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="xiwanggoumaibaoxiangongsi" placeholder="希望购买保险公司">
                        </td>
                        <td align="center">
                            其它保险要求
                        </td>
                        <td colspan="3">
                            <input type="text" class="form-control" id="" name="qitabaoxianyaoqiu" placeholder="其它保险要求">
                        </td>
                    </tr>

                    <tr>
                        <td align="center">
                            备注
                        </td>
                        <td colspan="5">
                            <input type="text" class="form-control" id="" name="beizhu" placeholder="备注">
                        </td>
                    </tr>

                    <tr>
                        <td align="center" colspan="6">
                            <a style="width: 100px;" onclick="add_baoxian();" class="btn btn-block btn-primary btn-sm">登记</a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </form>





    <script>

        function add_baoxian() {

            var url='/publishinfo/ajax';
            var dat={
                _token :$("input[name=_token]").val(),
                type   :'publish_baoxian',
                key    :$("#baoxian").serializeArray()
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