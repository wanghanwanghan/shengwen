@extends('layouts.dashboard')
@section('page_heading','欢迎登陆中服社区')
@section('page_heading_small','务工详细页')
@section('section')

    <form id="">
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
                            <input type="text" class="form-control" id="" name="xingming" placeholder="姓名" value="{{$res->xingming}}" readonly>
                        </td>
                        <td align="center">
                            性别
                        </td>
                        <td>
                            <select style="padding-left: 8px" name="xingbie" class="form-control">
                                <option value="">{{$res->xingbie}}</option>
                            </select>
                        </td>
                        <td align="center">
                            年龄
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="nianling" placeholder="年龄" value="{{$res->nianling}}" readonly>
                        </td>
                    </tr>

                    <tr>
                        <td align="center">
                            手机号
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="shoujihao" placeholder="手机号" value="{{$res->shoujihao}}" readonly>
                        </td>
                        <td align="center">
                            微信
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="weixin" placeholder="微信" value="{{$res->weixin}}" readonly>
                        </td>
                        <td align="center">
                            QQ
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="QQ" placeholder="QQ" value="{{$res->QQ}}" readonly>
                        </td>
                    </tr>

                    <tr>
                        <td align="center">
                            家庭住址
                        </td>
                        <td colspan="5">
                            <input type="text" class="form-control" id="" name="jiatingzhuzhi" placeholder="家庭住址" value="{{$res->jiatingzhuzhi}}" readonly>
                        </td>
                    </tr>

                    <tr>
                        <td align="center">
                            希望务工行业
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="xiwangwugonghangye" placeholder="希望务工行业" value="{{$res->xiwangwugonghangye}}" readonly>
                        </td>
                        <td align="center">
                            希望结算方式
                        </td>
                        <td>
                            <select style="padding-left: 8px" name="xiwangjiesuanfangshi" class="form-control">
                                <option value="">{{$res->xiwangjiesuanfangshi}}</option>
                            </select>
                        </td>
                        <td align="center">
                            希望薪资范围
                        </td>
                        <td>
                            <select style="padding-left: 8px" name="xiwangxinzifanwei" class="form-control">
                                <option value="">{{$res->xiwangxinzifanwei}}</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td align="center">
                            希望务工工种
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="xiwangwugonggongzhong" placeholder="希望务工工种" value="{{$res->xiwangwugonggongzhong}}" readonly>
                        </td>
                        <td align="center">
                            希望务工地点
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="xiwangwugongdidian" placeholder="希望务工地点" value="{{$res->xiwangwugongdidian}}" readonly>
                        </td>
                        <td align="center">
                            备注
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="beizhu" placeholder="备注" value="{{$res->beizhu}}" readonly>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </form>

@stop
