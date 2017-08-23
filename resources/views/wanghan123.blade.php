@extends('layouts.dashboard')
@section('page_heading','欢迎234234234证系统')
@section('page_heading_small','用户登23423432记页')
@section('section')


    <form method="post" id="fpVerifyForm" name="fpVerifyForm"
          action="authLoginAction!login.do?fpLogin=fpLogin"
          enctype="multipart/form-data" style="display: none">
        <input type="hidden" id="verifyModel" name="verifyModel" /> <input
                type="hidden" id="verifyTemplate" name="verifyTemplate" />
    </form>

    <div id="fvRegisterDiv" style="display: inline; height: do">
        <a id="fvRegister"
           onclick='submitFVRegister("指静脉", "指静脉数:", "确认保存当前修改吗？", "驱动下载", false)'
           title="请安装指静脉驱动或启动该服务" class="showGray"
           onmouseover="this.className='showGray'">注册</a>
    </div>

    <div id="fvDriverDownload" style="display: inline; margin: 0 0 0 5px;">
        <a id='fvDownloadDriver' href='middleware/zkbioonline.exe'
           title='驱动下载'>驱动下载</a>
    </div>
    <div id="comparison" style="display: none"
         onclick='fpVerification("指纹比对","请安装指纹驱动或启动服务",true,globalContext)'>比对</div>
    <div id="comparisonDiv" class="zhijingmai_box" style="display: none">
        <h2>指纹比对</h2>
        <div class="list">
            <canvas id="canvasComp" width="430" height="320"
                    style="background: {{asset('public/img/base_fpVerify.jpg')}} rgb(243, 245, 240);"></canvas>
            <input type="button" value="关闭" onclick='closeCompa()' />
        </div>
    </div>

    <div id="bg" style="display: none;"></div>
    <div id="zhijingmai_box" class="zhijingmai_box" style="display: none;">
        <h2>指静脉登记</h2>
        <div class="list">
            <canvas id="canvas" width="430" height="450"
                    style="background: rgb(243, 245, 240)"></canvas>
            <input type="hidden" id="whetherModify" name="whetherModify" alt=""
                   value="111" />

            <div
                    style="position: absolute; left: 310px; top: 325px; width: 70px; height: 28px;">
                <button type="button" id="submitButtonId" name="makeSureName"
                        onclick="submitEvent()" class="button-form">确定</button>
                <!-- ${common_edit_ok}:确定 -->
            </div>
            <div
                    style="position: absolute; left: 310px; top: 365px; width: 70px; height: 28px;">
                <button class="button-form" type="button" id="closeButton"
                        name="closeButton" onclick='cancelEvent("确认保存当前修改吗?", "指静脉数:");'>
                    取消</button>
                <!-- ${common_edit_cancel}:取消 -->
            </div>
        </div>
    </div>

    <div>
        <fieldset style="width:130px" id="t">
            <legend>三枚指静脉的id</legend>
            <textarea rows="1" cols="70" id="fvId"></textarea>
        </fieldset>
        <fieldset style="width:530px" id="te">
            <legend>三枚指静脉模板数据</legend>
            <textarea rows="22" cols="70" id="fvTemplate10"></textarea>
        </fieldset>
    </div>

    <script>
        $(function () {
            myfunction();
        })
    </script>



@stop
