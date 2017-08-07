@extends('layouts.dashboard')
@section('page_heading','超级管理员功能')
@section('page_heading_small','导入待采集客户信息')
@section('section')

    <div class="col-sm-12">
        <div class="col">
            <div class="col-sm-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">查询地区名称（这个功能的作用只是为了正确填写导入文件中的地区名称）</h3>
                    </div>
                    <form role="form" id="select_china_all_position_form">
                        <div class="box-body">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <p class="help-block">输入要查询的省名称（可模糊查询）</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="province_name" placeholder="省">
                                        </div>
                                        <div class="col-sm-8">
                                            <select style="padding-left: 8px" name="s_province_name" class="form-control">
                                                <option>如果查询到数据，这里会显示</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <p class="help-block">输入要查询的市名称（可模糊查询）</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="city_name" placeholder="市">
                                        </div>
                                        <div class="col-sm-8">
                                            <select style="padding-left: 8px" name="s_city_name" class="form-control">
                                                <option>如果查询到数据，这里会显示</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <p class="help-block">输入要查询的县名称（可模糊查询）</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="county_name" placeholder="县">
                                        </div>
                                        <div class="col-sm-8">
                                            <select style="padding-left: 8px" name="s_county_name" class="form-control">
                                                <option>如果查询到数据，这里会显示</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <p class="help-block">输入要查询的镇名称（可模糊查询）</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="town_name" placeholder="镇">
                                        </div>
                                        <div class="col-sm-8">
                                            <select style="padding-left: 8px" name="s_town_name" class="form-control">
                                                <option>如果查询到数据，这里会显示</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <p class="help-block">输入要查询的村名称（可模糊查询）</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" name="village_name" placeholder="村">
                                        </div>
                                        <div class="col-sm-8">
                                            <select style="padding-left: 8px" name="s_village_name" class="form-control">
                                                <option>如果查询到数据，这里会显示</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <p class="help-block">全地区名称（查询条件有县、镇、村才显示，因为别的级别太高，数据太多）</p>
                                        </div>
                                        <div class="col-sm-12">
                                            <select style="padding-left: 8px" name="all_path_name" class="form-control">
                                                <option>这里会显示匹配到的全地区名称，省-市-县-镇-村，方便导入数据时候填写</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="box-footer">
                            <a onclick="select_china_all_position();" class="btn btn-primary">查询</a>
                            <a onclick="daochudiqu();" class="btn btn-primary">导出地区</a>
                            <span id="excel_file_download"></span>
                            <input type="hidden" id="daochudiqu_lable" value="xxx">
                        </div>

                    </form>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="col-sm-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">导入社保数据的表单</h3>
                    </div>
                    @include('layouts.msg')
                    <form role="form" action="{{url('/import1')}}" method="post" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="box-body">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <p class="help-block"><a href="{{asset('public/import_customer.xls')}}" download="import_customer.xls">下载模板</a>后，填写相关信息</p>
                                            <p class="help-block">选择导入文件（文件后缀是.xls，不是手动改，而是用另存为.xls）</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <input type="file" name="myfile">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <p class="help-block">如果对导入文件的格式或者内容有疑问，请联系管理员</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="checkbox">
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label>
                                                <p class="help-block"><input type="checkbox" name="check"> 是否检查没问题，确定要导入？</p>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">导入</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">导入地区数据的表单</h3>
                    </div>
                    @foreach (['danger2','warning2','success2','info2'] as $msg)
                        @if(session()->has($msg))
                            <div class="flash-message">
                                <p style="text-align: center" class="alert alert-{{ substr($msg,0,-1) }}">
                                    {{ session()->get($msg) }}
                                </p>
                            </div>
                        @endif
                    @endforeach
                    <form role="form" action="{{url('/import2')}}" method="post" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="box-body">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <p class="help-block"><a href="{{asset('public/import_position.xls')}}" download="import_position.xls">下载模板</a>后，填写相关信息</p>
                                            <p class="help-block">选择导入文件（文件后缀是.xls，不是手动改，而是用另存为.xls）</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <input type="file" name="myfile">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <p class="help-block">如果对导入文件的格式或者内容有疑问，请联系管理员</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="checkbox">
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label>
                                                <p class="help-block"><input type="checkbox" name="check"> 是否检查没问题，确定要导入？</p>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">导入</button>
                            <span style="float: right">如需查找<span style="color: red">详细</span>区域划分码，请上<a target="_blank" href="http://www.stats.gov.cn/tjsj/tjbz/">国家统计局</a>官网</span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>

        $(function () {

            var url ='/data/ajax';
            var data={
                _token :$("input[name=_token]").val(),
                type   :'get_china_all_position'
            };

            $.post(url,data,function (response) {

                if(response.error=='0')
                {
                    layer.msg(response.msg);
                }else
                {
                    layer.msg(response.msg);
                }

            },'json');

        })

    </script>


@stop
