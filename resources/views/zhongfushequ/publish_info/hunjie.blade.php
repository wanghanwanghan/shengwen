@extends('layouts.dashboard')
@section('page_heading','欢迎登陆中服社区')
@section('page_heading_small','婚介登记页')
@section('section')

    <form id="hunjie">
        {{csrf_field()}}
        <div class="box" style="width: 1000px">
            <div class="box-body">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <td style="text-align: center">
                            姓名
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="xingming">
                        </td>
                        <td style="text-align: center">
                            性别
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="xingbie">
                        </td>
                        <td style="text-align: center">
                            民族
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="minzu">
                        </td>
                        <td colspan="2" rowspan="5">

                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: center">
                            出生年月
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="chushengnianyue" placeholder="如19990101">
                        </td>
                        <td style="text-align: center">
                            籍贯
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="jiguan">
                        </td>
                        <td style="text-align: center">
                            血型
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="xuexing">
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: center">
                            身份证
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="shenfenzheng">
                        </td>
                        <td style="text-align: center">
                            身高
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="shengao">
                        </td>
                        <td style="text-align: center">
                            体重
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="tizhong">
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: center">
                            身体状态
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="shentizhuangtai">
                        </td>
                        <td style="text-align: center">
                            兴趣爱好
                        </td>
                        <td colspan="3">
                            <input type="text" class="form-control" id="" name="xingquaihao">
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: center">
                            毕业学校
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="biyexuexiao">
                        </td>
                        <td style="text-align: center">
                            学历
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="xueli">
                        </td>
                        <td style="text-align: center">
                            专业
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="zhuanye">
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: center">
                            从事行业
                        </td>
                        <td colspan="7">
                            <select style="padding-left: 8px" name="congshihangye" class="form-control">
                                <option value="行政机关">行政机关</option>
                                <option value="事业单位">事业单位</option>
                                <option value="国企">国企</option>
                                <option value="私企">私企</option>
                                <option value="外企">外企</option>
                                <option value="个体">个体</option>
                                <option value="其他">其他</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: center">
                            职业
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="zhiye">
                        </td>
                        <td style="text-align: center">
                            职务
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="zhiwu">
                        </td>
                        <td style="text-align: center">
                            职称
                        </td>
                        <td colspan="2">
                            <input type="text" class="form-control" id="" name="zhicheng">
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: center">
                            工作单位
                        </td>
                        <td colspan="3">
                            <input type="text" class="form-control" id="" name="gongzuodanwei">
                        </td>
                        <td style="text-align: center">
                            家庭住址
                        </td>
                        <td colspan="3">
                            <input type="text" class="form-control" id="" name="jiatingzhuzhi">
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: center">
                            手机号码
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="shoujihaoma">
                        </td>
                        <td style="text-align: center">
                            QQ
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="QQ">
                        </td>
                        <td style="text-align: center">
                            微信
                        </td>
                        <td colspan="3">
                            <input type="text" class="form-control" id="" name="weixin">
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: center">
                            婚姻状态
                        </td>
                        <td colspan="7">
                            <select style="padding-left: 8px" name="hunyinzhuangtai" class="form-control">
                                <option value="未婚">未婚</option>
                                <option value="离异未育">离异未育</option>
                                <option value="离异不带孩">离异不带孩</option>
                                <option value="离异带孩">离异带孩</option>
                                <option value="丧偶带孩">丧偶带孩</option>
                                <option value="丧偶不带孩">丧偶不带孩</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: center">
                            收入情况
                        </td>
                        <td colspan="7">
                            <select style="padding-left: 8px" name="shouruqingkuang" class="form-control">
                                <option value="2000-4000">2000-4000</option>
                                <option value="4000-8000">4000-8000</option>
                                <option value="8000-20000">8000-20000</option>
                                <option value="20000以上">20000以上</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: center">
                            房产
                        </td>
                        <td colspan="3">
                            <select style="padding-left: 8px" name="fangchan" class="form-control">
                                <option value="有">有</option>
                                <option value="无">无</option>
                            </select>
                        </td>
                        <td style="text-align: center">
                            私家车
                        </td>
                        <td colspan="3">
                            <select style="padding-left: 8px" name="sijiache" class="form-control">
                                <option value="有">有</option>
                                <option value="无">无</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: center" rowspan="2">
                            家庭成员
                        </td>
                        <td style="text-align: center">
                            姓名
                        </td>
                        <td style="text-align: center">
                            关系
                        </td>
                        <td style="text-align: center">
                            职业
                        </td>
                        <td style="text-align: center">
                            姓名
                        </td>
                        <td style="text-align: center">
                            关系
                        </td>
                        <td style="text-align: center">
                            职业
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: center">
                            <input type="text" class="form-control" id="" name="xingming1">
                        </td>
                        <td style="text-align: center">
                            <input type="text" class="form-control" id="" name="guanxi1">
                        </td>
                        <td style="text-align: center">
                            <input type="text" class="form-control" id="" name="zhiye1">
                        </td>
                        <td style="text-align: center">
                            <input type="text" class="form-control" id="" name="xingming2">
                        </td>
                        <td style="text-align: center">
                            <input type="text" class="form-control" id="" name="guanxi2">
                        </td>
                        <td style="text-align: center">
                            <input type="text" class="form-control" id="" name="zhiye2">
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: center" rowspan="10">
                            配偶要求
                        </td>
                        <td style="text-align: center" colspan="2">
                            年龄段
                        </td>
                        <td style="text-align: center" colspan="2">
                            学历
                        </td>
                        <td style="text-align: center" colspan="3">
                            专业
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: center" colspan="2">
                            <input type="text" class="form-control" id="" name="nianlingduan_peiou">
                        </td>
                        <td style="text-align: center" colspan="2">
                            <input type="text" class="form-control" id="" name="xueli_peiou">
                        </td>
                        <td style="text-align: center" colspan="3">
                            <input type="text" class="form-control" id="" name="zhuanye_peiou">
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: center" colspan="2">
                            住房要求
                        </td>
                        <td style="text-align: center" colspan="2">
                            家庭状态
                        </td>
                        <td style="text-align: center" colspan="3">
                            地域范围
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: center" colspan="2">
                            <input type="text" class="form-control" id="" name="zhufangyaoqiu_peiou">
                        </td>
                        <td style="text-align: center" colspan="2">
                            <input type="text" class="form-control" id="" name="jiatingzhuangtai_peiou">
                        </td>
                        <td style="text-align: center" colspan="3">
                            <input type="text" class="form-control" id="" name="diyufanwei_peiou">
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: center" colspan="2">
                            职业要求
                        </td>
                        <td style="text-align: center" colspan="2">
                            身体范围
                        </td>
                        <td style="text-align: center" colspan="3">
                            体重范围
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: center" colspan="2">
                            <input type="text" class="form-control" id="" name="zhiyeyaoqiu_peiou">
                        </td>
                        <td style="text-align: center" colspan="2">
                            <input type="text" class="form-control" id="" name="shentifanwei_peiou">
                        </td>
                        <td style="text-align: center" colspan="3">
                            <input type="text" class="form-control" id="" name="tizhongfanwei_peiou">
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: center">
                            婚姻状态
                        </td>
                        <td colspan="6">
                            <select style="padding-left: 8px" name="hunyinzhuangtai_peiou" class="form-control">
                                <option value="未婚">未婚</option>
                                <option value="离异未育">离异未育</option>
                                <option value="离异不带孩">离异不带孩</option>
                                <option value="离异带孩">离异带孩</option>
                                <option value="丧偶带孩">丧偶带孩</option>
                                <option value="丧偶不带孩">丧偶不带孩</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: center">
                            收入情况
                        </td>
                        <td colspan="6">
                            <select style="padding-left: 8px" name="shouruqingkuang_peiou" class="form-control">
                                <option value="2000-4000">2000-4000</option>
                                <option value="4000-8000">4000-8000</option>
                                <option value="8000-20000">8000-20000</option>
                                <option value="20000以上">20000以上</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: center" colspan="3">
                            才貌要求
                        </td>
                        <td style="text-align: center" colspan="4">
                            兴趣爱好
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: center" colspan="3">
                            <input type="text" class="form-control" id="" name="caimaoyaoqiu_peiou">
                        </td>
                        <td style="text-align: center" colspan="4">
                            <input type="text" class="form-control" id="" name="xingquaihao_peiou">
                        </td>
                    </tr>

                    <tr>
                        <td colspan="7" align="center">
                            <a style="width: 100px;" onclick="add_hunjie();" class="btn btn-block btn-primary btn-sm">登记</a>
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </form>





    <script>

        function add_hunjie() {

            var url='/publishinfo/ajax';
            var dat={
                _token :$("input[name=_token]").val(),
                type   :'publish_hunjie',
                key    :$("#hunjie").serializeArray()
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
