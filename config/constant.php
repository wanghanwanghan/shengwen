<?php

return [

    //自定义常量

    //百度语音识别账号密码
    'APP_ID'=>'9394757',
    'API_KEY'=>'eDQD692rXYXa3Sj1CrNHk1ZZ',
    'SECRET_KEY'=>'bc6f23033ebad89a413f0fc9726fc835',

    //ivr返回的录音文件存放路径
    'voice_path'=>'VoiceAndModel/Voice/',

    //ivr返回的录音模型文件存放路径
    'model_path'=>'VoiceAndModel/Model/',

    //删除的录音文件
    'voice_remove_path'=>'VoiceAndModel/Tmp/',

    //轮播认证次数
    'loop_time'=>'1',

    //相隔多少秒之内不能发起第二次轮播
    'until_time'=>'300',

    //用于比较手机号码归属地，此处填写服务器所在的省市
    'province'=>'湖北',
    'city'=>'黄石',

    //用户天门特殊版本切换
    //0=>通用
    //1=>天门
    'app_edition'=>'1',

    //对比指纹指静脉的分值
    'fingerprintscore'=>'70',
    'fingervenascore'=>'70'





];
