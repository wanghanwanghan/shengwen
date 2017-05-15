@extends('layouts.plane')
@section('body')

    {{csrf_field()}}
    <div class="box">
        <div class="box-header">
        </div>
        <!-- /.box-header -->
        <div class="box-body no-padding">
            <table class="table table-condensed">
                <tbody>
                <tr>
                </tr>
                <tr>
                    <td>1.</td>
                    <td>轮播总用户数</td>
                    <td>
                        {{$loop_totle}}
                    </td>
                </tr>
                <tr>
                    <td>2.</td>
                    <td>未完成用户数</td>
                    <td>
                        {{$loop_unfinished}}
                    </td>
                </tr>
                <tr>
                    <td>3.</td>
                    <td>完成用户数</td>
                    <td>
                        {{$loop_finish}}
                    </td>
                </tr>
                <tr>
                    <td>4.</td>
                    <td>未认证通过用户数</td>
                    <td>
                        {{$loop_unpass}}
                    </td>
                </tr>
                <tr>
                    <td>5.</td>
                    <td>认证通过用户数</td>
                    <td>
                        {{$loop_pass}}
                    </td>
                </tr>
                </tbody></table>
        </div>
        <!-- /.box-body -->
    </div>

@stop