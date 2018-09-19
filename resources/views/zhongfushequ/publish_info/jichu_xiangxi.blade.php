@extends('layouts.dashboard')
@section('page_heading','欢迎登陆中服社区')
@section('page_heading_small','基础信息详细页')
@section('section')

    <form id="jichu" style="width: 1000px">
        <div class="box">
            <div class="box-body">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <td>
                            <div>
                                <input type="text" class="form-control" id="" name="xingming" placeholder="姓名" value="{{$res->xingming}}" readonly>
                            </div>
                        </td>
                        <td>
                            <div>
                                <select style="padding-left: 8px" name="jueseleixing" class="form-control">
                                    <option value="">{{$res->jueseleixing}}</option>
                                </select>
                            </div>
                        </td>
                        <td>
                            <div>
                                <input type="text" class="form-control" id="" name="xingbie" placeholder="性别" value="{{$res->xingbie}}" readonly>
                            </div>
                        </td>
                        <td>
                            <div>
                                <input type="text" class="form-control" id="" name="chushengnianyue" placeholder="出生年月-如19990101" value="{{$res->chushengnianyue}}" readonly>
                            </div>
                        </td>
                        <td>
                            <div>
                                <input type="text" class="form-control" id="" name="lianxidianhua" placeholder="联系电话" value="{{$res->lianxidianhua}}" readonly>
                            </div>
                        </td>
                    <tr>
                    <tr>
                        <td>
                            <div>
                                <select style="padding-left: 8px" name="congshihangye" class="form-control">
                                    <option value="">{{$res->congshihangye}}</option>
                                </select>
                            </div>
                        </td>
                        <td colspan="2">
                            <div>
                                <input type="text" class="form-control" id="" name="juticongshi" placeholder="具体从事" value="{{$res->juticongshi}}" readonly>
                            </div>
                        </td>
                        <td colspan="2">
                            <div>
                                <input type="text" class="form-control" id="" name="zhuzhi" placeholder="住址" value="{{$res->zhuzhi}}" readonly>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </form>

@stop
