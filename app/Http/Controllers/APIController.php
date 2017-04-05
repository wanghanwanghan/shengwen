<?php

namespace App\Http\Controllers;

use App\Http\Model\CustModel;
use App\Http\Model\LogModel;
use App\Http\Model\VocalPrintModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;

class APIController extends Controller
{
    //ivr查询数据的接口
    public function select_something()
    {
        switch ($_GET['key'])
        {
            case 'phone':

                if (!$this->check_something($_GET['cond'],'phonenumber',null))
                {
                    return ['error'=>'1','msg'=>'wrong number'];
                }else
                {
                    $get=[
                        'cust_num',
                        'cust_name',
                        'cust_confirm_type',
                        'cust_review_flag',
                        'cust_type'
                    ];

                    $res=CustModel::where(['cust_review_num'=>$_GET['cond']])->get($get)->toArray();

                    if (empty($res))
                    {
                        return ['error'=>'0','msg'=>'we dont have the number in the database'];
                    }else
                    {
                        return ['error'=>'0','msg'=>'you got it','data'=>$res];
                    }
                }

                break;

            case 'review';





                break;

        }
    }

    //web到ivr的接口
    public function web_ivr_api()
    {
        switch (Input::get('type'))
        {
            //web给ivr发送用户注册请求
            case 'register':

                //通过客户编号查询年审号码
                $info=CustModel::find(Input::get('key'));
                $cust_name=$info->cust_name;
                $cust_review_num=$info->cust_review_num;
                $cust_confirm_type=$info->cust_confirm_type;

                $data=[
                    'pid'=>Input::get('key'),//用户的主键号
                    'name'=>$cust_name,//用户的姓名
                    'phone'=>$cust_review_num,//年审手机号
                    'confirm_type'=>$cust_confirm_type,//认证类型，文本无关，文本相关，动态口令
                    'confirm_text'=>''//用户要说的话
                ];

                $res=$this->mycurl('http://localhost:7510/register',$data);

                //判断发送是否成功
                if ($res['error']=='1')
                {
                    $name=Session::get('user');
                    $obj=$this->mymongo();
                    $obj->ivrlog->test1->insert([
                        'who'=>$name[0]['staff_account'],
                        'action'=>'web给ivr发送登记请求',
                        'result'=>$res['error'],
                        'message'=>$res['msg'],
                        'time'=>time()
                    ]);

                    return ['error'=>'1','msg'=>'发送注册请求失败'];
                }else
                {
                    $name=Session::get('user');
                    $obj=$this->mymongo();
                    $obj->ivrlog->test1->insert([
                        'who'=>$name[0]['staff_account'],
                        'action'=>'web给ivr发送登记请求',
                        'result'=>$res['error'],
                        'message'=>$cust_name.'登记请求已发送',
                        'time'=>time()
                    ]);

                    return ['error'=>'0','msg'=>'发送注册请求成功'];
                }

                break;

            //web给ivr发送用户验证请求
            case 'verify'://web给ivr发送用户验证请求，系统主动验证

                return ['error'=>'0','msg'=>'wanghan'];

                break;

            //web给ivr发送轮播认证请求
            case 'loop_call':





                break;
        }
    }

    //ivr返回用户注册的结果
    public function ivr_return_1()
    {
        //接收参数
        $pid=$_GET['pid'] ? $_GET['pid'] : '';//客户主键
        $url=$_GET['url'] ? $_GET['url'] : '';//客户录音文件
        $model_url=$_GET['model_url'] ? $_GET['model_url'] : '';//客户声纹模型

        //找到这个客户
        $model=CustModel::find($pid);

        if ($pid!='' && $url!='' && $model_url!='')
        {
            //修改数据库为已注册
            $model->update(['cust_register_flag'=>'1']);

            //把该客户的声纹url存起来
            VocalPrintModel::create(['vp_id'=>$pid,'vp_ivr_url'=>$url,'vp_model_url'=>$model_url]);

            //通知mongo
            $obj=$this->mymongo();
            $obj->ivrlog->test1->insert([
                'who'=>'ivr',
                'action'=>'ivr返回用户登记结果',
                'result'=>'0',
                'message'=>$model->cust_name.'登记成功',
                'time'=>time()
            ]);

            return ['error'=>'0'];
        }else
        {
            //通知mongo
            $obj=$this->mymongo();
            $obj->ivrlog->test1->insert([
                'who'=>'ivr',
                'action'=>'ivr返回用户登记结果',
                'result'=>'1',
                'message'=>$model->cust_name.'登记失败',
                'time'=>time()
            ]);

            return ['error'=>'1'];
        }
    }

    //ivr返回验证的结果
    public function ivr_return_2()
    {

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

    public function ceshi_test()
    {









    }






}
