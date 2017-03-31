<?php

namespace App\Http\Controllers;

use App\Http\Model\CustModel;
use App\Http\Model\LogModel;
use App\Http\Model\VocalPrintModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redis;

class APIController extends Controller
{
    public function web_ivr_api()
    {
        switch (Input::get('type'))
        {
            case 'register'://web给ivr发送用户注册请求

                //通过客户编号查询年审号码
                $info=CustModel::find(Input::get('key'));
                $cust_review_num=$info->cust_review_num;
                $cust_confirm_type=$info->cust_confirm_type;
                $cust_id=$info->cust_id;
                $cust_name=$info->cust_name;

                $curl=curl_init();//初始化
                curl_setopt($curl,CURLOPT_URL,'http://192.168.0.249/register');//设置请求地址
                curl_setopt($curl,CURLOPT_POST,true);//设置post方式请求
                $data=[
                    'urpid'=>Input::get('key'),//用户的主键号
                    'cname'=>$cust_name,//用户的姓名
                    'usrid'=>$cust_id,//用户的身份证号
                    'phone'=>$cust_review_num,//年审手机号
                    'ctype'=>$cust_confirm_type//认证类型，文本无关，文本相关，动态口令
                ];//提交的数据
                $data=json_encode($data);//转换成json
                curl_setopt($curl,CURLOPT_POSTFIELDS,$data);//提交的数据
                curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);//返回值不直接显示
                $res=curl_exec($curl);//发送请求
                curl_close($curl);//释放

                return ['error'=>'0','msg'=>'发送注册请求成功'];

                break;

            case 'verify'://web给ivr发送用户验证请求，系统主动验证

                //通过客户编号查询年审号码
                $info=CustModel::find(Input::get('key'));
                $cust_review_num=$info->cust_review_num;
                $cust_confirm_type=$info->cust_confirm_type;
                $cust_id=$info->cust_id;
                $cust_name=$info->cust_name;

                $curl=curl_init();//初始化
                curl_setopt($curl,CURLOPT_URL,'http://192.168.0.249/verify');//设置请求地址
                curl_setopt($curl,CURLOPT_POST,true);//设置post方式请求
                $data=[
                    'urpid'=>Input::get('key'),//用户的主键号
                    'cname'=>$cust_name,//用户的姓名
                    'usrid'=>$cust_id,//用户的身份证号
                    'phone'=>$cust_review_num,//年审手机号
                    'ctype'=>$cust_confirm_type//认证类型，文本无关，文本相关，动态口令
                ];//提交的数据
                $data=json_encode($data);//转换成json
                curl_setopt($curl,CURLOPT_POSTFIELDS,$data);//提交的数据
                curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);//返回值不直接显示
                $res=curl_exec($curl);//发送请求
                curl_close($curl);//释放

                return ['error'=>'0','msg'=>'发送验证请求成功'];

                break;

            case 'loop_call':

                $res=request()->url();



                return ['error'=>'0','msg'=>$res];

                break;
        }





    }

    //ivr返回用户注册的结果
    public function ivr_return_1()
    {
        //接收参数
        $pid=$_GET['pid'] ? $_GET['pid'] : '';//客户主键
        $url=$_GET['url'] ? $_GET['url'] : '';//客户声纹url

        if ($pid!='' && $url!='')
        {
            //找到这个客户
            $model=CustModel::find($pid);

            //修改数据库为已注册
            $model->update(['cust_register_flag'=>'1']);

            //把该客户的声纹url存起来
            VocalPrintModel::create(['vp_id'=>$pid,'vp_ivr_url'=>$url]);

            return ['error'=>'0','msg'=>'succeed'];
        }else
        {
            return ['error'=>'1'];
        }
    }

    //ivr返回验证的结果
    public function ivr_return_2()
    {
        //接收参数
        $pid=$_GET['pid'] ? $_GET['pid'] : '';//客户主键
        $url=$_GET['url'] ? $_GET['url'] : '';//客户声纹url

        if ($pid!='' && $url!='')
        {
            //声纹引擎的对比操作

            $res='';

            //声纹引擎的返回，0代表匹配，1代表不匹配，2代表错误
            if ($res=='0')
            {
                return ['error'=>$res,'msg'=>'matching'];
            }elseif ($res=='1')
            {
                return ['error'=>$res,'msg'=>'mismatching'];
            }else
            {
                return ['error'=>$res,'msg'=>'error'];
            }
        }else
        {
            return ['error'=>'1','msg'=>'pid或者url为空'];
        }
    }

    public function web_vocalprint_api()
    {
        switch (Input::get('type'))
        {
            case '123':

                break;

        }

    }

    //百度语音识别
    public function baiducheck()
    {
        define('AUDIO_FILE', public_path('test.wav'));
        $url = "http://vop.baidu.com/server_api";

        //put your params here
        $cuid = "9394757";
        $apiKey = "eDQD692rXYXa3Sj1CrNHk1ZZ";
        $secretKey = "bc6f23033ebad89a413f0fc9726fc835";

        $auth_url = "https://openapi.baidu.com/oauth/2.0/token?grant_type=client_credentials&client_id=".$apiKey."&client_secret=".$secretKey;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $auth_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        $response = curl_exec($ch);
        if(curl_errno($ch))
        {
            print curl_error($ch);
        }
        curl_close($ch);
        $response = json_decode($response, true);
        $token = $response['access_token'];

        $audio = file_get_contents(AUDIO_FILE);
        $base_data = base64_encode($audio);
        $array = array(
            "format" => "wav",
            "rate" => 8000,
            "channel" => 1,
            //"lan" => "zh",
            "token" => $token,
            "cuid"=> $cuid,
            //"url" => "http://www.xxx.com/sample.pcm",
            //"callback" => "http://www.xxx.com/audio/callback",
            "len" => filesize(AUDIO_FILE),
            "speech" => $base_data,
        );
        $json_array = json_encode($array);
        $content_len = "Content-Length: ".strlen($json_array);
        $header = array ($content_len, 'Content-Type: application/json; charset=utf-8');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_array);
        $response = curl_exec($ch);
        if(curl_errno($ch))
        {
            print curl_error($ch);
        }
        curl_close($ch);
        echo $response;
        $response = json_decode($response, true);
        var_dump($response);
    }

    //马上删除
    public function test()
    {
        $res=CustModel::find($_GET['id']);

        if (empty($res))
        {
            dd('没有用户');
        }else
        {
            dd($res->toArray());
        }
    }

}
