@extends('layouts.dashboard')
@section('page_heading','欢迎登陆社会养老保险领取资格认证平台')
@section('page_heading_small','首页')
@section('section')

    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-12">
                <p class="help-block">如果页面不能正确显示，或者按钮不能使用，请下载谷歌浏览器，或者火狐浏览器</p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-2">
                <a target="_blank" href="https://www.baidu.com/link?url=Xj4VxpPM5yY5nI-6L3Yo9zZRl7aJJLz0ZAW4jRozaChcV6piSrcdSu4BnYX23t-iQjYgsCJViM1twgOQ6QCXAkuiLJtxdJHL9lTPOr0SfWO&wd=&eqid=c709cdeb00009d8b000000035959b1e3">
                    下载谷歌浏览器
                </a>
            </div>
            <div class="col-sm-2">
                <a target="_blank" href="https://www.baidu.com/link?url=K51o4x1l_9KsUbWQgXRmLXAG8TfOIOGeAaJbdB0IzAatZv1gioeo7jW8txeEnblqRgKvP04RhdN7BIAmVkSbxekIFcZbuQoDVMT-rtuEEsS&wd=&eqid=d5e82c6c0000a3ad000000035959b244">
                    下载火狐浏览器
                </a>
            </div>
        </div>
        <br><br>
        <div class="row">
            <div class="col-sm-12">
                <p class="help-block">系统所需驱动程序</p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-2">
                <a target="_blank" href="{{asset('public/ID_Reader/ZKIDROnline.zip')}}">
                    下载身份证读卡器驱动
                </a>
            </div>
            <div class="col-sm-2">
                <a target="_blank" href="{{asset('public/FV_Reader/zkbioonline.zip')}}">
                    下载指静脉驱动
                </a>
            </div>
        </div>
    </div>

@stop
