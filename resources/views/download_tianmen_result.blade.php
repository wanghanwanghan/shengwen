@extends('layouts.dashboard')
@section('page_heading','数据统计')
@section('page_heading_small','下载管理页')
@section('section')

    <div class="box">
        <div class="box-body no-padding">
            <table class="table table-condensed">
                <tbody>
                <tr>
                    <th style="text-align: center;">员工姓名</th>
                    <th style="text-align: center;">截至时间</th>
                    <th style="text-align: center;">归属地区</th>
                    <th style="text-align: center;">参保类型</th>
                    <th style="text-align: center;">客户状态</th>
                    <th style="text-align: center;">导出进度</th>
                    <th style="text-align: center;">文件下载</th>
                </tr>

                <div id="finish">

                </div>



                <div id="unfinish">

                </div>


                <tr>
                    <td style="text-align: center;">{{func_in_helpers_Get_data_in_session('staff_name')}}</td>
                    <td style="text-align: center;">2018-01-01至2018-01-08</td>
                    <td style="text-align: center;">河北省涞源县涞源镇福寿禄扶手村</td>
                    <td style="text-align: center;">企业养老</td>
                    <td style="text-align: center;">已采集</td>
                    <td style="text-align: center;">
                        <span class="badge bg-green">55%</span>
                    </td>
                    <td style="text-align: center;">请等待</td>
                </tr>











                </tbody>
            </table>
        </div>
    </div>










    <div id="down_confirm_result_laypage" class="pagination pagination-sm no-margin pull-right">
    </div>






    <script>

    </script>


@stop
