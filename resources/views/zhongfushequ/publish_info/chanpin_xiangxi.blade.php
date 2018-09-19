@extends('layouts.dashboard')
@section('page_heading','欢迎登陆中服社区')
@section('page_heading_small','产品详细页')
@section('section')

    <form id="">
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
                                <option value="">{{$res->chanpinleixing}}</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td align="center">
                            需求人
                        </td>
                        <td>
                            <input type="text" class="form-control" id="" name="xingming" placeholder="姓名" value="{{$res->xingming}}" readonly>
                        </td>
                        <td align="center">
                            家庭住址
                        </td>
                        <td colspan="3">
                            <input type="text" class="form-control" id="" name="jiatingzhuzhi" placeholder="家庭住址" value="{{$res->jiatingzhuzhi}}" readonly>
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
