@extends('layouts.dashboard')
@section('page_heading','超级管理员功能')
@section('page_heading_small','导入待采集客户信息')
@section('section')

    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">请认真填写</h3>
                    </div>
                    @include('layouts.msg')
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" action="{{url('/import')}}" method="post" enctype="multipart/form-data">

                        {{csrf_field()}}
                        <div class="box-body">
                            <div class="form-group">
                                <label>选择导入的区域</label>
                                <select style="padding-left: 8px" name="cust_project" class="form-control">
                                    @foreach($staff_project as $v)
                                        <option>{{$v}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>选择导入文件（文件后缀是.xls，不是手动改，而是用另存为.xls）</label>
                                <input type="file" name="myfile">

                                <p class="help-block">如果对导入文件的格式或者内容有疑问，请联系管理员</p>
                            </div>

                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="check"> 是否检查没问题，确定要导入？
                                </label>
                            </div>

                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">导入</button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>







    <script>

    </script>


@stop
