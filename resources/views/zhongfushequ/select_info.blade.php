@extends('layouts.dashboard')
@section('page_heading','欢迎登陆中服社区')
@section('page_heading_small','信息查询页')
@section('section')

    {{--<link rel="stylesheet" href="{{asset('public/css/mycss.css')}}">--}}

    {{csrf_field()}}
    <div class="nav-tabs-custom" style="width: 1000px">
        <ul class="nav nav-tabs">
            <li class=""><a href="#tab_07" data-toggle="tab" aria-expanded="true">基础信息</a></li>
            <li class="active"><a href="#tab_01" data-toggle="tab" aria-expanded="true">务工信息</a></li>
            <li class=""><a href="#tab_02" data-toggle="tab" aria-expanded="false">生意信息</a></li>
            <li class=""><a href="#tab_03" data-toggle="tab" aria-expanded="false">保险信息</a></li>
            <li class=""><a href="#tab_04" data-toggle="tab" aria-expanded="false">产品信息</a></li>
            <li class=""><a href="#tab_05" data-toggle="tab" aria-expanded="false">家庭装修信息</a></li>
            <li class=""><a href="#tab_06" data-toggle="tab" aria-expanded="false">婚介信息</a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="tab_01" style="height: 700px;">

                <form id="select_cond_01">

                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-2" align="center">
                                年龄范围
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" id="" name="nianling" placeholder="如20-40">
                            </div>
                            <div class="col-sm-2" align="center">
                                务工行业
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" id="" name="xiwangwugonghangye" placeholder="">
                            </div>
                            <div class="col-sm-2" align="center">
                                务工工种
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" id="" name="xiwangwugonggongzhong" placeholder="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-2" align="center">
                                务工地点
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" id="" name="xiwangwugongdidian" placeholder="">
                            </div>
                            <div class="col-sm-2" align="center">
                                薪资范围
                            </div>
                            <div class="col-sm-2">
                                <select style="padding-left: 8px" name="xiwangxinzifanwei" class="form-control">
                                    <option value="2000-4000">2000-4000</option>
                                    <option value="4000-8000">4000-8000</option>
                                    <option value="8000-20000">8000-20000</option>
                                    <option value="20000以上">20000以上</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12" align="center">
                                <a style="width: 100px;" onclick="select_wugong();" class="btn btn-block btn-primary btn-sm">查询</a>
                            </div>
                        </div>
                    </div>

                </form>

                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-2" align="center">
                            <b>姓名</b>
                        </div>
                        <div class="col-sm-2" align="center">
                            <b>性别</b>
                        </div>
                        <div class="col-sm-2" align="center">
                            <b>年龄</b>
                        </div>
                        <div class="col-sm-2" align="center">
                            <b>手机号</b>
                        </div>
                        <div class="col-sm-2" align="center">
                            <b>微信</b>
                        </div>
                        <div class="col-sm-2" align="center">
                            <b>详细信息</b>
                        </div>
                    </div>
                    <div id="append_01">
                    </div>
                    <div class="col-sm-12" align="center">
                        <div id="laypage_01" class="pagination pagination-sm no-margin pull-right">
                        </div>
                    </div>
                </div>

            </div>
            <div class="tab-pane" id="tab_02" style="height: 700px;">

                <form id="select_cond_02">

                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-2" align="center">
                                年龄范围
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" id="" name="nianling" placeholder="如20-40">
                            </div>
                            <div class="col-sm-2" align="center">
                                生意所属行业
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" id="" name="shengyisuoshuhangye" placeholder="">
                            </div>
                            <div class="col-sm-2" align="center">
                                生意所属类别
                            </div>
                            <div class="col-sm-2">
                                <select style="padding-left: 8px" name="shengyisuoshuleibie" class="form-control">
                                    <option value="合作需求">合作需求</option>
                                    <option value="资金需求">资金需求</option>
                                    <option value="人才需求">人才需求</option>
                                    <option value="其他">其他</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12" align="center">
                                <a style="width: 100px;" onclick="select_shengyi();" class="btn btn-block btn-primary btn-sm">查询</a>
                            </div>
                        </div>
                    </div>

                </form>

                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-2" align="center">
                            <b>姓名</b>
                        </div>
                        <div class="col-sm-2" align="center">
                            <b>性别</b>
                        </div>
                        <div class="col-sm-2" align="center">
                            <b>年龄</b>
                        </div>
                        <div class="col-sm-2" align="center">
                            <b>手机号</b>
                        </div>
                        <div class="col-sm-2" align="center">
                            <b>微信</b>
                        </div>
                        <div class="col-sm-2" align="center">
                            <b>详细信息</b>
                        </div>
                    </div>
                    <div id="append_02">
                    </div>
                    <div class="col-sm-12" align="center">
                        <div id="laypage_02" class="pagination pagination-sm no-margin pull-right">
                        </div>
                    </div>
                </div>

            </div>
            <div class="tab-pane" id="tab_03" style="height: 700px;">

                <form id="select_cond_03">

                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-2" align="center">
                                希望购买保险公司
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="" name="xiwanggoumaibaoxiangongsi" placeholder="">
                            </div>
                            <div class="col-sm-2" align="center">
                                希望购买保险类别
                            </div>
                            <div class="col-sm-3">
                                <select style="padding-left: 8px" name="xiwanggoumaibaoxianleibie" class="form-control">
                                    <option value="疾病">疾病</option>
                                    <option value="意外">意外</option>
                                    <option value="投资">投资</option>
                                    <option value="养老">养老</option>
                                    <option value="教育">教育</option>
                                    <option value="其他">其他</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12" align="center">
                                <a style="width: 100px;" onclick="select_baoxian();" class="btn btn-block btn-primary btn-sm">查询</a>
                            </div>
                        </div>
                    </div>

                </form>

                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-2" align="center">
                            <b>姓名</b>
                        </div>
                        <div class="col-sm-2" align="center">
                            <b>性别</b>
                        </div>
                        <div class="col-sm-2" align="center">
                            <b>年龄</b>
                        </div>
                        <div class="col-sm-2" align="center">
                            <b>手机号</b>
                        </div>
                        <div class="col-sm-2" align="center">
                            <b>微信</b>
                        </div>
                        <div class="col-sm-2" align="center">
                            <b>详细信息</b>
                        </div>
                    </div>
                    <div id="append_03">
                    </div>
                    <div class="col-sm-12" align="center">
                        <div id="laypage_03" class="pagination pagination-sm no-margin pull-right">
                        </div>
                    </div>
                </div>

            </div>
            <div class="tab-pane" id="tab_04" style="height: 700px;">

                <form id="select_cond_04">

                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-2" align="center">
                                产品类型
                            </div>
                            <div class="col-sm-10">
                                <select style="padding-left: 8px" name="chanpinleixing" class="form-control">
                                    <option value="净水器">净水器</option>
                                    <option value="电动车(两轮)">电动车（两轮）</option>
                                    <option value="电动车(三轮)">电动车（三轮）</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12" align="center">
                                <a style="width: 100px;" onclick="select_chanpin();" class="btn btn-block btn-primary btn-sm">查询</a>
                            </div>
                        </div>
                    </div>

                </form>

                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-2" align="center">
                            <b>姓名</b>
                        </div>
                        <div class="col-sm-2" align="center">
                            <b>手机号</b>
                        </div>
                        <div class="col-sm-2" align="center">
                            <b>微信</b>
                        </div>
                        <div class="col-sm-2" align="center">
                            <b>QQ</b>
                        </div>
                        <div class="col-sm-2" align="center">
                            <b>备注</b>
                        </div>
                        <div class="col-sm-2" align="center">
                            <b>详细信息</b>
                        </div>
                    </div>
                    <div id="append_04">
                    </div>
                    <div class="col-sm-12" align="center">
                        <div id="laypage_04" class="pagination pagination-sm no-margin pull-right">
                        </div>
                    </div>
                </div>

            </div>
            <div class="tab-pane" id="tab_05" style="height: 700px;">

                <form id="select_cond_05">

                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-2" align="center">
                                装修所在位置
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" id="" name="zhuangxiusuozaiweizhi" placeholder="">
                            </div>
                            <div class="col-sm-2" align="center">
                                装修类型
                            </div>
                            <div class="col-sm-2">
                                <select style="padding-left: 8px" name="zhuangxiuleixing" class="form-control">
                                    <option value="新房装修">新房装修</option>
                                    <option value="二手房翻新">二手房翻新</option>
                                    <option value="局部装修">局部装修</option>
                                    <option value="其他">其他</option>
                                </select>
                            </div>
                            <div class="col-sm-2" align="center">
                                是否希望分期
                            </div>
                            <div class="col-sm-2">
                                <select style="padding-left: 8px" name="shifouxiwangfenqi" class="form-control">
                                    <option value="是">是</option>
                                    <option value="否">否</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12" align="center">
                                <a style="width: 100px;" onclick="select_jiatingzhuangxiu();" class="btn btn-block btn-primary btn-sm">查询</a>
                            </div>
                        </div>
                    </div>

                </form>

                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-2" align="center">
                            <b>姓名</b>
                        </div>
                        <div class="col-sm-2" align="center">
                            <b>手机号</b>
                        </div>
                        <div class="col-sm-2" align="center">
                            <b>微信</b>
                        </div>
                        <div class="col-sm-2" align="center">
                            <b>QQ</b>
                        </div>
                        <div class="col-sm-2" align="center">
                            <b>备注</b>
                        </div>
                        <div class="col-sm-2" align="center">
                            <b>详细信息</b>
                        </div>
                    </div>
                    <div id="append_05">
                    </div>
                    <div class="col-sm-12" align="center">
                        <div id="laypage_05" class="pagination pagination-sm no-margin pull-right">
                        </div>
                    </div>
                </div>

            </div>
            <div class="tab-pane" id="tab_06" style="height: 700px;">

                <form id="select_cond_06">

                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-2" align="center">
                                出生年月范围
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" id="" name="chushengnianyue" placeholder="如19990101-19991231" style="font-size: 10px">
                            </div>
                            <div class="col-sm-2" align="center">
                                性别
                            </div>
                            <div class="col-sm-2">
                                <select style="padding-left: 8px" name="xingbie" class="form-control">
                                    <option value="男">男</option>
                                    <option value="女">女</option>
                                </select>
                            </div>
                            <div class="col-sm-2" align="center">
                                学历
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" id="" name="xueli">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-2" align="center">
                                婚姻状况
                            </div>
                            <div class="col-sm-2">
                                <select style="padding-left: 8px" name="hunyinzhuangtai" class="form-control">
                                    <option value="">略</option>
                                    <option value="未婚">未婚</option>
                                    <option value="离异未育">离异未育</option>
                                    <option value="离异不带孩">离异不带孩</option>
                                    <option value="离异带孩">离异带孩</option>
                                    <option value="丧偶带孩">丧偶带孩</option>
                                    <option value="丧偶不带孩">丧偶不带孩</option>
                                </select>
                            </div>
                            <div class="col-sm-2" align="center">
                                收入情况
                            </div>
                            <div class="col-sm-2">
                                <select style="padding-left: 8px" name="shouruqingkuang" class="form-control">
                                    <option value="">略</option>
                                    <option value="2000-4000">2000-4000</option>
                                    <option value="4000-8000">4000-8000</option>
                                    <option value="8000-20000">8000-20000</option>
                                    <option value="20000以上">20000以上</option>
                                </select>
                            </div>
                            <div class="col-sm-2" align="center">
                                配偶年龄段
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" id="" name="nianlingduan_peiou" placeholder="如30-40">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-2" align="center">
                                配偶住房要求
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" id="" name="zhufangyaoqiu_peiou">
                            </div>
                            <div class="col-sm-2" align="center">
                                配偶婚姻状况
                            </div>
                            <div class="col-sm-2">
                                <select style="padding-left: 8px" name="hunyinzhuangtai_peiou" class="form-control">
                                    <option value="">略</option>
                                    <option value="未婚">未婚</option>
                                    <option value="离异未育">离异未育</option>
                                    <option value="离异不带孩">离异不带孩</option>
                                    <option value="离异带孩">离异带孩</option>
                                    <option value="丧偶带孩">丧偶带孩</option>
                                    <option value="丧偶不带孩">丧偶不带孩</option>
                                </select>
                            </div>
                            <div class="col-sm-2" align="center">
                                配有收入情况
                            </div>
                            <div class="col-sm-2">
                                <select style="padding-left: 8px" name="shouruqingkuang_peiou" class="form-control">
                                    <option value="">略</option>
                                    <option value="2000-4000">2000-4000</option>
                                    <option value="4000-8000">4000-8000</option>
                                    <option value="8000-20000">8000-20000</option>
                                    <option value="20000以上">20000以上</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12" align="center">
                                <a style="width: 100px;" onclick="select_hunjie();" class="btn btn-block btn-primary btn-sm">查询</a>
                            </div>
                        </div>
                    </div>

                </form>

                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-2" align="center">
                            <b>姓名</b>
                        </div>
                        <div class="col-sm-2" align="center">
                            <b>性别</b>
                        </div>
                        <div class="col-sm-2" align="center">
                            <b>身高</b>
                        </div>
                        <div class="col-sm-2" align="center">
                            <b>体重</b>
                        </div>
                        <div class="col-sm-2" align="center">
                            <b>出生年月</b>
                        </div>
                        <div class="col-sm-2" align="center">
                            <b>详细信息</b>
                        </div>
                    </div>
                    <div id="append_06">
                    </div>
                    <div class="col-sm-12" align="center">
                        <div id="laypage_06" class="pagination pagination-sm no-margin pull-right">
                        </div>
                    </div>
                </div>

            </div>
            <div class="tab-pane" id="tab_07" style="height: 700px;">

                <form id="select_cond_07">

                    <div class="col-sm-12">

                        <div class="row">
                            <div class="col-sm-2" align="center">
                                角色类型
                            </div>
                            <div class="col-sm-2">
                                <select style="padding-left: 8px" name="jueseleixing" class="form-control">
                                    <option value="认证客户">认证客户</option>
                                    <option value="配偶">配偶</option>
                                    <option value="子女">子女</option>
                                    <option value="父母">父母</option>
                                    <option value="其他">其他</option>
                                </select>
                            </div>
                            <div class="col-sm-2" align="center">
                                从事行业
                            </div>
                            <div class="col-sm-2">
                                <select style="padding-left: 8px" name="congshihangye" class="form-control">
                                    <option value="工作单位">工作单位</option>
                                    <option value="自由职业">自由职业</option>
                                    <option value="个体经营">个体经营</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12" align="center">
                                <a style="width: 100px;" onclick="select_jichu();" class="btn btn-block btn-primary btn-sm">查询</a>
                            </div>
                        </div>

                    </div>

                </form>

                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-2" align="center">
                            <b>姓名</b>
                        </div>
                        <div class="col-sm-2" align="center">
                            <b>角色类型</b>
                        </div>
                        <div class="col-sm-2" align="center">
                            <b>性别</b>
                        </div>
                        <div class="col-sm-2" align="center">
                            <b>联系电话</b>
                        </div>
                        <div class="col-sm-2" align="center">
                            <b>从事行业</b>
                        </div>
                        <div class="col-sm-2" align="center">
                            <b>详细信息</b>
                        </div>
                    </div>
                    <div id="append_07">
                    </div>
                    <div class="col-sm-12" align="center">
                        <div id="laypage_07" class="pagination pagination-sm no-margin pull-right">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <script>

        function select_jichu() {

            var url='/publishinfo/ajax';
            var dat={
                _token :$("input[name=_token]").val(),
                type   :'select_info_07',
                key    :$("#select_cond_07").serializeArray()
            };

            $.post(url,dat,function (response) {

                $("#append_07").children().remove();

                if (response.error=='0')
                {
                    $.each(response.data,function (k,v) {

                        var myrow=$("<div class='row'></div>");

                        myrow.append("<div class='col-sm-2' align='center'>"+v.xingming+"</div>");
                        myrow.append("<div class='col-sm-2' align='center'>"+v.jueseleixing+"</div>");
                        myrow.append("<div class='col-sm-2' align='center'>"+v.xingbie+"</div>");
                        myrow.append("<div class='col-sm-2' align='center'>"+v.lianxidianhua+"</div>");
                        myrow.append("<div class='col-sm-2' align='center'>"+v.congshihangye+"</div>");
                        myrow.append("<div class='col-sm-2' align='center'><a href=/show/jichu/"+v.id+">单击查看</a></div>");

                        $("#append_07").append(myrow);

                    });

                    layer.msg(response.msg);
                }else
                {
                    layer.msg(response.msg);
                }

            },'json');

        }

        function select_hunjie() {

            var url='/publishinfo/ajax';
            var dat={
                _token :$("input[name=_token]").val(),
                type   :'select_info_06',
                key    :$("#select_cond_06").serializeArray()
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

        function select_wugong() {

            var url='/publishinfo/ajax';
            var dat={
                _token :$("input[name=_token]").val(),
                type   :'select_info_01',
                key    :$("#select_cond_01").serializeArray()
            };

            $.post(url,dat,function (response) {

                $("#append_01").children().remove();

                if (response.error=='0')
                {
                    $.each(response.data,function (k,v) {

                        var myrow=$("<div class='row'></div>");

                        myrow.append("<div class='col-sm-2' align='center'>"+v.xingming+"</div>");
                        myrow.append("<div class='col-sm-2' align='center'>"+v.xingbie+"</div>");
                        myrow.append("<div class='col-sm-2' align='center'>"+v.nianling+"</div>");
                        myrow.append("<div class='col-sm-2' align='center'>"+v.shoujihao+"</div>");
                        myrow.append("<div class='col-sm-2' align='center'>"+v.weixin+"</div>");
                        myrow.append("<div class='col-sm-2' align='center'><a href=/show/wugong/"+v.id+">单击查看</a></div>");

                        $("#append_01").append(myrow);

                    });

                    layer.msg(response.msg);
                }else
                {
                    layer.msg(response.msg);
                }

            },'json');

        }

        function select_shengyi() {

            var url='/publishinfo/ajax';
            var dat={
                _token :$("input[name=_token]").val(),
                type   :'select_info_02',
                key    :$("#select_cond_02").serializeArray()
            };

            $.post(url,dat,function (response) {

                $("#append_02").children().remove();

                if (response.error=='0')
                {
                    $.each(response.data,function (k,v) {

                        var myrow=$("<div class='row'></div>");

                        myrow.append("<div class='col-sm-2' align='center'>"+v.xingming+"</div>");
                        myrow.append("<div class='col-sm-2' align='center'>"+v.xingbie+"</div>");
                        myrow.append("<div class='col-sm-2' align='center'>"+v.nianling+"</div>");
                        myrow.append("<div class='col-sm-2' align='center'>"+v.shoujihao+"</div>");
                        myrow.append("<div class='col-sm-2' align='center'>"+v.weixin+"</div>");
                        myrow.append("<div class='col-sm-2' align='center'><a href=/show/shengyi/"+v.id+">单击查看</a></div>");

                        $("#append_02").append(myrow);

                    });

                    layer.msg(response.msg);
                }else
                {
                    layer.msg(response.msg);
                }

            },'json');

        }

        function select_baoxian() {

            var url='/publishinfo/ajax';
            var dat={
                _token :$("input[name=_token]").val(),
                type   :'select_info_03',
                key    :$("#select_cond_03").serializeArray()
            };

            $.post(url,dat,function (response) {

                $("#append_03").children().remove();

                if (response.error=='0')
                {
                    $.each(response.data,function (k,v) {

                        var myrow=$("<div class='row'></div>");

                        myrow.append("<div class='col-sm-2' align='center'>"+v.xingming+"</div>");
                        myrow.append("<div class='col-sm-2' align='center'>"+v.xingbie+"</div>");
                        myrow.append("<div class='col-sm-2' align='center'>"+v.nianling+"</div>");
                        myrow.append("<div class='col-sm-2' align='center'>"+v.shoujihao+"</div>");
                        myrow.append("<div class='col-sm-2' align='center'>"+v.weixin+"</div>");
                        myrow.append("<div class='col-sm-2' align='center'><a href=/show/baoxian/"+v.id+">单击查看</a></div>");

                        $("#append_03").append(myrow);

                    });

                    layer.msg(response.msg);
                }else
                {
                    layer.msg(response.msg);
                }

            },'json');

        }

        function select_chanpin() {

            var url='/publishinfo/ajax';
            var dat={
                _token :$("input[name=_token]").val(),
                type   :'select_info_04',
                key    :$("#select_cond_04").serializeArray()
            };

            $.post(url,dat,function (response) {

                $("#append_04").children().remove();

                if (response.error=='0')
                {
                    $.each(response.data,function (k,v) {

                        var myrow=$("<div class='row'></div>");

                        myrow.append("<div class='col-sm-2' align='center'>"+v.xingming+"</div>");
                        myrow.append("<div class='col-sm-2' align='center'>"+v.shoujihao+"</div>");
                        myrow.append("<div class='col-sm-2' align='center'>"+v.weixin+"</div>");
                        myrow.append("<div class='col-sm-2' align='center'>"+v.QQ+"</div>");
                        myrow.append("<div class='col-sm-2' align='center'>"+v.beizhu+"</div>");
                        myrow.append("<div class='col-sm-2' align='center'><a href=/show/chanpin/"+v.id+">单击查看</a></div>");

                        $("#append_04").append(myrow);

                    });

                    layer.msg(response.msg);
                }else
                {
                    layer.msg(response.msg);
                }

            },'json');

        }

        function select_jiatingzhuangxiu() {

            var url='/publishinfo/ajax';
            var dat={
                _token :$("input[name=_token]").val(),
                type   :'select_info_05',
                key    :$("#select_cond_05").serializeArray()
            };

            $.post(url,dat,function (response) {

                $("#append_05").children().remove();

                if (response.error=='0')
                {
                    $.each(response.data,function (k,v) {

                        var myrow=$("<div class='row'></div>");

                        myrow.append("<div class='col-sm-2' align='center'>"+v.xingming+"</div>");
                        myrow.append("<div class='col-sm-2' align='center'>"+v.shoujihao+"</div>");
                        myrow.append("<div class='col-sm-2' align='center'>"+v.weixin+"</div>");
                        myrow.append("<div class='col-sm-2' align='center'>"+v.QQ+"</div>");
                        myrow.append("<div class='col-sm-2' align='center'>"+v.beizhu+"</div>");
                        myrow.append("<div class='col-sm-2' align='center'><a href=/show/jiatingzhuangxiu/"+v.id+">单击查看</a></div>");

                        $("#append_05").append(myrow);

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
