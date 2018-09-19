@extends('layouts.dashboard')
@section('page_heading','欢迎登陆中服社区')
@section('page_heading_small','务工登记页')
@section('section')

    <form id="wugong">
        {{csrf_field()}}
        <div class="box" style="width: 1000px">
            <div class="box-body">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <td align="center">
                            务工人姓名
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
                            家庭住址
                        </td>
                        <td colspan="5">
                            <input type="text" class="form-control" id="" name="jiatingzhuzhi" placeholder="家庭住址">
                        </td>
                    </tr>

                    <tr>
                        <td align="center">
                            希望务工行业
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="xiwangwugonghangye" placeholder="希望务工行业">
                        </td>
                        <td align="center">
                            希望结算方式
                        </td>
                        <td>
                            <select style="padding-left: 8px" name="xiwangjiesuanfangshi" class="form-control">
                                <option value="日结">日结</option>
                                <option value="月结">月结</option>
                                <option value="其他">其他</option>
                            </select>
                        </td>
                        <td align="center">
                            希望薪资范围
                        </td>
                        <td>
                            <select style="padding-left: 8px" name="xiwangxinzifanwei" class="form-control">
                                <option value="2000-4000">2000-4000</option>
                                <option value="4000-8000">4000-8000</option>
                                <option value="8000-20000">8000-20000</option>
                                <option value="20000以上">20000以上</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td align="center">
                            希望务工工种
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="xiwangwugonggongzhong" placeholder="希望务工工种">
                        </td>
                        <td align="center">
                            希望务工地点
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="xiwangwugongdidian" placeholder="希望务工地点">
                        </td>
                        <td align="center">
                            备注
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="beizhu" placeholder="备注">
                        </td>
                    </tr>

                    <tr>
                        <td align="center" colspan="6">
                            <a style="width: 100px;" onclick="add_wugong();" class="btn btn-block btn-primary btn-sm">登记</a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </form>





    <script>

        function add_wugong() {

            var url='/publishinfo/ajax';
            var dat={
                _token :$("input[name=_token]").val(),
                type   :'publish_wugong',
                key    :$("#wugong").serializeArray()
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
