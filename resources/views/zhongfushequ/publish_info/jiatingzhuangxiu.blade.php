@extends('layouts.dashboard')
@section('page_heading','欢迎登陆中服社区')
@section('page_heading_small','家庭装修登记页')
@section('section')

    <form id="jiatingzhuangxiu">
        {{csrf_field()}}
        <div class="box" style="width: 1012px">
            <div class="box-body">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <td align="center">
                            需求人
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
                            装修所在位置
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="zhuangxiusuozaiweizhi" placeholder="装修所在位置">
                        </td>
                        <td align="center">
                            装修范围描述
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="zhuangxiufanweimiaoshu" placeholder="装修范围描述">
                        </td>
                        <td align="center">
                            是否希望分期
                        </td>
                        <td>
                            <select style="padding-left: 8px" name="shifouxiwangfenqi" class="form-control">
                                <option value="否">否</option>
                                <option value="是">是</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td align="center">
                            装修类型
                        </td>
                        <td colspan="2">
                            <select style="padding-left: 8px" name="zhuangxiuleixing" class="form-control">
                                <option value="新房装修">新房装修</option>
                                <option value="二手房翻新">二手房翻新</option>
                                <option value="局部装修">局部装修</option>
                                <option value="其他">其他</option>
                            </select>
                        </td>
                        <td align="center">
                            备注
                        </td>
                        <td colspan="2">
                            <input type="text" class="form-control" id="" name="beizhu" placeholder="备注">
                        </td>
                    </tr>

                    <tr>
                        <td align="center" colspan="6">
                            <a style="width: 100px;" onclick="add_jiatingzhuangxiu();" class="btn btn-block btn-primary btn-sm">登记</a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </form>





    <script>

        function add_jiatingzhuangxiu() {

            var url='/publishinfo/ajax';
            var dat={
                _token :$("input[name=_token]").val(),
                type   :'publish_jiatingzhuangxiu',
                key    :$("#jiatingzhuangxiu").serializeArray()
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
