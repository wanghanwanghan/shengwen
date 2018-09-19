@extends('layouts.dashboard')
@section('page_heading','欢迎登陆中服社区')
@section('page_heading_small','婚介详细页')
@section('section')

    <form id="">
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
                            <input type="text" class="form-control" id="" name="xingming" value="{{$res->xingming}}" readonly>
                        </td>
                        <td style="text-align: center">
                            性别
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="xingbie" value="{{$res->xingbie}}" readonly>
                        </td>
                        <td style="text-align: center">
                            民族
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="minzu" value="{{$res->minzu}}" readonly>
                        </td>
                        <td colspan="2" rowspan="5">

                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: center">
                            出生年月
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="chushengnianyue" value="{{$res->chushengnianyue}}" readonly>
                        </td>
                        <td style="text-align: center">
                            籍贯
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="jiguan" value="{{$res->jiguan}}" readonly>
                        </td>
                        <td style="text-align: center">
                            血型
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="xuexing" value="{{$res->xuexing}}" readonly>
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: center">
                            身份证
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="shenfenzheng" value="{{$res->shenfenzheng}}" readonly>
                        </td>
                        <td style="text-align: center">
                            身高
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="shengao" value="{{$res->shengao}}" readonly>
                        </td>
                        <td style="text-align: center">
                            体重
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="tizhong" value="{{$res->tizhong}}" readonly>
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: center">
                            身体状态
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="shentizhuangtai" value="{{$res->shentizhuangtai}}" readonly>
                        </td>
                        <td style="text-align: center">
                            兴趣爱好
                        </td>
                        <td colspan="3">
                            <input type="text" class="form-control" id="" name="xingquaihao" value="{{$res->xingquaihao}}" readonly>
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: center">
                            毕业学校
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="biyexuexiao" value="{{$res->biyexuexiao}}" readonly>
                        </td>
                        <td style="text-align: center">
                            学历
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="xueli" value="{{$res->xueli}}" readonly>
                        </td>
                        <td style="text-align: center">
                            专业
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="zhuanye" value="{{$res->zhuanye}}" readonly>
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: center">
                            从事行业
                        </td>
                        <td colspan="7">
                            <select style="padding-left: 8px" name="congshihangye" class="form-control">
                                <option value="">{{$res->congshihangye}}</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: center">
                            职业
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="zhiye" value="{{$res->zhiye}}" readonly>
                        </td>
                        <td style="text-align: center">
                            职务
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="zhiwu" value="{{$res->zhiwu}}" readonly>
                        </td>
                        <td style="text-align: center">
                            职称
                        </td>
                        <td colspan="2">
                            <input type="text" class="form-control" id="" name="zhicheng" value="{{$res->zhicheng}}" readonly>
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: center">
                            工作单位
                        </td>
                        <td colspan="3">
                            <input type="text" class="form-control" id="" name="gongzuodanwei" value="{{$res->gongzuodanwei}}" readonly>
                        </td>
                        <td style="text-align: center">
                            家庭住址
                        </td>
                        <td colspan="3">
                            <input type="text" class="form-control" id="" name="jiatingzhuzhi" value="{{$res->jiatingzhuzhi}}" readonly>
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: center">
                            手机号码
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="shoujihaoma" value="{{$res->shoujihaoma}}" readonly>
                        </td>
                        <td style="text-align: center">
                            QQ
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="QQ" value="{{$res->QQ}}" readonly>
                        </td>
                        <td style="text-align: center">
                            微信
                        </td>
                        <td colspan="3">
                            <input type="text" class="form-control" id="" name="weixin" value="{{$res->weixin}}" readonly>
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: center">
                            婚姻状态
                        </td>
                        <td colspan="7">
                            <select style="padding-left: 8px" name="hunyinzhuangtai" class="form-control">
                                <option value="">{{$res->hunyinzhuangtai}}</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: center">
                            收入情况
                        </td>
                        <td colspan="7">
                            <select style="padding-left: 8px" name="shouruqingkuang" class="form-control">
                                <option value="">{{$res->shouruqingkuang}}</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: center">
                            房产
                        </td>
                        <td colspan="3">
                            <select style="padding-left: 8px" name="fangchan" class="form-control">
                                <option value="">{{$res->fangchan}}</option>
                            </select>
                        </td>
                        <td style="text-align: center">
                            私家车
                        </td>
                        <td colspan="3">
                            <select style="padding-left: 8px" name="sijiache" class="form-control">
                                <option value="">{{$res->sijiache}}</option>
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
                            <input type="text" class="form-control" id="" name="xingming1" value="{{$res->xingming1}}" readonly>
                        </td>
                        <td style="text-align: center">
                            <input type="text" class="form-control" id="" name="guanxi1" value="{{$res->guanxi1}}" readonly>
                        </td>
                        <td style="text-align: center">
                            <input type="text" class="form-control" id="" name="zhiye1" value="{{$res->zhiye1}}" readonly>
                        </td>
                        <td style="text-align: center">
                            <input type="text" class="form-control" id="" name="xingming2" value="{{$res->xingming2}}" readonly>
                        </td>
                        <td style="text-align: center">
                            <input type="text" class="form-control" id="" name="guanxi2" value="{{$res->guanxi2}}" readonly>
                        </td>
                        <td style="text-align: center">
                            <input type="text" class="form-control" id="" name="zhiye2" value="{{$res->zhiye2}}" readonly>
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
                            <input type="text" class="form-control" id="" name="nianlingduan_peiou" value="{{$res->nianlingduan_peiou}}" readonly>
                        </td>
                        <td style="text-align: center" colspan="2">
                            <input type="text" class="form-control" id="" name="xueli_peiou" value="{{$res->xueli_peiou}}" readonly>
                        </td>
                        <td style="text-align: center" colspan="3">
                            <input type="text" class="form-control" id="" name="zhuanye_peiou" value="{{$res->zhuanye_peiou}}" readonly>
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
                            <input type="text" class="form-control" id="" name="zhufangyaoqiu_peiou" value="{{$res->zhufangyaoqiu_peiou}}" readonly>
                        </td>
                        <td style="text-align: center" colspan="2">
                            <input type="text" class="form-control" id="" name="jiatingzhuangtai_peiou" value="{{$res->jiatingzhuangtai_peiou}}" readonly>
                        </td>
                        <td style="text-align: center" colspan="3">
                            <input type="text" class="form-control" id="" name="diyufanwei_peiou" value="{{$res->diyufanwei_peiou}}" readonly>
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
                            <input type="text" class="form-control" id="" name="zhiyeyaoqiu_peiou" value="{{$res->zhiyeyaoqiu_peiou}}" readonly>
                        </td>
                        <td style="text-align: center" colspan="2">
                            <input type="text" class="form-control" id="" name="shentifanwei_peiou" value="{{$res->shentifanwei_peiou}}" readonly>
                        </td>
                        <td style="text-align: center" colspan="3">
                            <input type="text" class="form-control" id="" name="tizhongfanwei_peiou" value="{{$res->tizhongfanwei_peiou}}" readonly>
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: center">
                            婚姻状态
                        </td>
                        <td colspan="6">
                            <select style="padding-left: 8px" name="hunyinzhuangtai_peiou" class="form-control">
                                <option value="">{{$res->hunyinzhuangtai_peiou}}</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: center">
                            收入情况
                        </td>
                        <td colspan="6">
                            <select style="padding-left: 8px" name="shouruqingkuang_peiou" class="form-control">
                                <option value="">{{$res->shouruqingkuang_peiou}}</option>
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
                            <input type="text" class="form-control" id="" name="caimaoyaoqiu_peiou" value="{{$res->caimaoyaoqiu_peiou}}" readonly>
                        </td>
                        <td style="text-align: center" colspan="4">
                            <input type="text" class="form-control" id="" name="xingquaihao_peiou" value="{{$res->xingquaihao_peiou}}" readonly>
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </form>





    <script>


    </script>


@stop
