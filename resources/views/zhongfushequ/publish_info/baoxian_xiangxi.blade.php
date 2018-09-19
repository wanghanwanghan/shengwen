@extends('layouts.dashboard')
@section('page_heading','欢迎登陆中服社区')
@section('page_heading_small','保险详细页')
@section('section')

    <form id="">
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
                            希望购买保险类别
                        </td>
                        <td>
                            <select style="padding-left: 8px" name="xiwanggoumaibaoxianleibie" class="form-control">
                                <option value="">{{$res->xiwanggoumaibaoxianleibie}}</option>
                            </select>
                        </td>
                        <td align="center">
                            希望购买保险公司
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="xiwanggoumaibaoxiangongsi" placeholder="希望购买保险公司" value="{{$res->xiwanggoumaibaoxiangongsi}}" readonly>
                        </td>
                        <td align="center">
                            其它保险要求
                        </td>
                        <td colspan="3">
                            <input type="text" class="form-control" id="" name="qitabaoxianyaoqiu" placeholder="其它保险要求" value="{{$res->qitabaoxianyaoqiu}}" readonly>
                        </td>
                    </tr>

                    <tr>
                        <td align="center">
                            备注
                        </td>
                        <td colspan="5">
                            <input type="text" class="form-control" id="" name="beizhu" placeholder="备注" value="{{$res->beizhu}}" readonly>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </form>

@stop
