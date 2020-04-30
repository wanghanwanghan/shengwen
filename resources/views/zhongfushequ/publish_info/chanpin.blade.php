@extends('layouts.dashboard')
@section('page_heading','欢迎登陆中服社区')
@section('page_heading_small','产品登记页')
@section('section')

    <form id="chanpin">
        {{csrf_field()}}
        <div class="box" style="width: 1000px">
            <div class="box-body">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <td align="center">
                            产品类型
                        </td>
                        <td colspan="5">
                            <select style="padding-left: 8px" name="chanpinleixing" class="form-control">
                                <option value="净水器">净水器</option>
                                <option value="电动车(两轮)">电动车（两轮）</option>
                                <option value="电动车(三轮)">电动车（三轮）</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td align="center">
                            需求人
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="xingming" placeholder="姓名">
                        </td>
                        <td align="center">
                            家庭住址
                        </td>
                        <td colspan="3">
                            <input type="text" class="form-control" id="" name="jiatingzhuzhi" placeholder="家庭住址">
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
                            备注
                        </td>
                        <td colspan="5">
                            <input type="text" class="form-control" id="" name="beizhu" placeholder="备注">
                        </td>
                    </tr>

                    <tr>
                        <td align="center" colspan="6">
                            <a style="width: 100px;" onclick="add_chanpin();" class="btn btn-block btn-primary btn-sm">登记</a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </form>





    <script>

        function add_chanpin() {

            var url='/publishinfo/ajax';
            var dat={
                _token :$("input[name=_token]").val(),
                type   :'publish_chanpin',
                key    :$("#chanpin").serializeArray()
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