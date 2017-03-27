@extends('layouts.plane')
@section('body')

    <div class="box box-info">
        <!--第一年审人信息-->
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">以下是第一年审人信息<span style="color: red;border: solid red 1px;">{{$model['cust_type']}}类</span></h3>
                <button type="button" class="btn btn-box-tool" data-widget="collapse" style="float: right">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
            <div class="box-body">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <td>
                            <div class="col-xs-8">
                                <input type="text" disabled class="form-control" value={{$model['cust_name']}}>
                            </div>
                        </td>
                        <td>
                            <div class="col-xs-8">
                                <input type="text" disabled class="form-control" value={{$model['cust_review_num']}}年审>
                            </div>
                        </td>
                        <td>
                            <div class="col-xs-8">
                                <input type="text" disabled class="form-control" value={{$model['cust_project']}}>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <div class="col-xs-8">
                                <input type="text" disabled class="form-control" value={{$model['cust_id']}}>
                            </div>
                        </td>
                        <td>
                            <div class="col-xs-8">
                                <input type="text" disabled class="form-control" value={{$model['cust_phone_num']}}备用>
                            </div>
                        </td>
                        <td>
                            <div class="col-xs-8">
                                <input type="text" disabled class="form-control" value={{$model['cust_confirm_type']}}>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <div class="col-xs-8">
                                <input type="text" disabled class="form-control" value={{$model['cust_si_id']}}>
                            </div>
                        </td>
                        <td>
                            <div class="col-xs-8">
                                <input type="text" disabled class="form-control" value={{$model['cust_address']}}>
                            </div>
                        </td>
                        <td>
                            <div class="col-xs-8">
                                <input type="text" disabled class="form-control" value={{$model['cust_si_type']}}>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="box box-info">
        <!--添加第二年审人-->
        <form id="add_second_form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="first_id" value="{{ $first_id }}">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">以下是第二年审人信息<span style="color: red;border: solid red 1px;">{{$model['cust_type']}}类</span></h3>
                    <input type="hidden" name="cust_type_hidden" value={{$model['cust_type']}}>
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" style="float: right">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
                <div class="box-body">
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <td>
                                <div class="col-xs-8">
                                    <input type="text" class="form-control" name="cust_name" placeholder="姓名">
                                </div>
                            </td>
                            <td>
                                <div class="col-xs-8">
                                    <input type="text" readonly class="form-control" name="cust_review_num" value={{$model['cust_review_num']}}>
                                </div>
                            </td>
                            <td>
                                <div class="col-xs-8">
                                    <select style="padding-left: 8px" name="cust_project" class="form-control">
                                        @foreach($staff_project as $v)
                                            <option>{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div class="col-xs-8">
                                    <input type="text" class="form-control" name="cust_id" placeholder="身份证号">
                                </div>
                            </td>
                            <td>
                                <div class="col-xs-8">
                                    <input type="text" class="form-control" name="cust_phone_num" placeholder="备用手机">
                                </div>
                            </td>
                            <td>
                                <div class="col-xs-8">
                                    <select style="padding-left: 8px" name="cust_confirm_type" class="form-control">
                                        @foreach($confirm_type as $v)
                                            <option>{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div class="col-xs-8">
                                    <input type="text" class="form-control" name="cust_si_id" placeholder="社保编号">
                                </div>
                            </td>
                            <td>
                                <div class="col-xs-8">
                                    <input type="text" class="form-control" name="cust_address" placeholder="地址信息">
                                </div>
                            </td>
                            <td>
                                <div class="col-xs-8">
                                    <select style="padding-left: 8px" name="cust_si_type" class="form-control">
                                        @foreach($staff_si_type as $v)
                                            <option>{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td align="center" colspan="3">
                                <a style="width: 100px;" onclick="add_second_cust();" class="btn btn-block btn-primary btn-sm">登记</a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    </div>

@stop