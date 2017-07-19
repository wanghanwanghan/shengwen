@extends('layouts.dashboard')
@section('page_heading','客服功能')
@section('page_heading_small','导出结果')
@section('section')

    <div class="box">
        <div class="box-header">
            <div class="col-sm-12">

                <div class="row">

                    <form id="">

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">



                    </form>

                </div>

            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-2">
                            <button style="width: 100px;" type="button" onclick="create_excel();" class="btn btn-block btn-primary btn-sm">生成表格</button>
                        </div>
                        <div class="col-sm-2">

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>


    <script>

        $(function () {



        });
        


    </script>


@stop
