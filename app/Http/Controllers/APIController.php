<?php

namespace App\Http\Controllers;

use App\Http\Model\ConfirmTypeModel;
use App\Http\Model\CustConfirmModel;
use App\Http\Model\CustModel;
use App\Http\Model\LogModel;
use App\Http\Model\ProjectModel;
use App\Http\Model\SiTypeModel;
use App\Http\Model\VocalPrintModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
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
            case 'pid':

                $get=[
                    'cust_num',
                    'cust_name',
                    'cust_confirm_type',
                    'cust_review_flag',
                    'cust_type'
                ];

                $res=CustModel::where(['cust_num'=>$_GET['cond']])->get($get)->toArray();

                if (empty($res))
                {
                    return ['error'=>'0','msg'=>'数据库中没有这个电话'];
                }else
                {
                    return ['error'=>'0','msg'=>'查询成功','data'=>$res];
                }

                break;

            case 'phone':

                if (!$this->check_something($_GET['cond'],'phonenumber',null))
                {
                    return ['error'=>'1','msg'=>'电话号码格式错误'];
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
                        return ['error'=>'0','msg'=>'数据库中没有这个电话'];
                    }else
                    {
                        return ['error'=>'0','msg'=>'查询成功','data'=>$res];
                    }
                }

                break;

            case 'review':

                $cond=isset($_GET['cond']) ? $_GET['cond'] : '';//客户主键
                $start_time=isset($_GET['start_time']) ? $_GET['start_time'].' 00:00:00' : '';//开始时间
                $stop_time=isset($_GET['stop_time']) ? $_GET['stop_time'].' 23:59:59' : '';//结束时间

                if ($cond!='' && $start_time!='' && $stop_time!='')
                {
                    $res=CustConfirmModel::where(['confirm_pid'=>$cond,'confirm_res'=>'Y'])
                        ->wherebetween('created_at',[$start_time,$stop_time])->get()->toArray();

                    if (empty($res))
                    {
                        return 'N';//还没有通过
                    }else
                    {
                        return 'Y';//已经通过了
                    }

                }else
                {
                    return ['error'=>'1','msg'=>'参数错误'];
                }

                break;

            case 'idcard':

                $res=CustModel::find($_GET['cond']);

                if (empty($res))
                {
                    return ['error'=>'0','msg'=>'数据库中没有这个身份证号码'];
                }else
                {
                    return ['error'=>'0','msg'=>'查询成功','data'=>$res->cust_id];
                }

                break;







        }
    }

    //ivr验证动态口令
    public function check_dynamicpassword()
    {
        //动态口令实际长度
        $dp_sys=isset($_GET['str1']) ? $_GET['str1'] : '';

        //客户录的动态口令
        $dp_usr=isset($_GET['str2']) ? $_GET['str2'] : '';

        if ($dp_sys=='' || $dp_usr=='')
        {
            return ['error'=>'1','msg'=>'错误，参数有空值'];
        }

        //一个一个对比
        for ($i=0;$i<=strlen($dp_sys)-1;$i++)
        {
            if ($dp_sys[$i]!=$dp_usr[$i])
            {
                return ['error'=>'1','msg'=>'该组动态口令有不匹配的'];
            }
        }

        return ['error'=>'0','msg'=>'对比成功，完全一致'];
    }

    //ajax处理
    public function ajax()
    {
        switch (Input::get('type'))
        {
            case 'get_loop_mongo_data':

                //用户传入的页
                $now_page=Input::get('page');

                //每页显示几条数据
                $limit=12;

                //从第几条开始显示
                $offset=($now_page-1)*$limit;

                $obj=$this->mymongo();

                //查询数据
                $res=$obj->ivrlog->loop->find()->sort(['time'=>-1])->limit($limit)->skip($offset);

                //总页数
                $cnt=$obj->ivrlog->loop->find()->count();
                $cnt_page=intval(ceil($cnt/$limit));

                foreach ($res as $row)
                {
                    //把所有mongo数据取出来
                    $tmp[]=$row;
                }

                //重新整理并发送给前端显示
                foreach ($tmp as &$row)
                {
                    unset($row['_id']);

                    $row['time']=date("Y-m-d H:i:s",$row['time']);

                    if ($row['result']=='0')
                    {
                        $row['result']='正常';
                    }else
                    {
                        $row['result']='异常';
                    }
                }

                $data=$tmp;

                return ['error'=>'0','data'=>$data,'pages'=>$cnt_page,'count_data'=>$cnt];

                break;

            case 'get_register_verify_mongo_data':

                //用户传入的页
                $now_page=Input::get('page');

                //每页显示几条数据
                $limit=15;

                //从第几条开始显示
                $offset=($now_page-1)*$limit;

                $obj=$this->mymongo();

                //查询数据
                $res=$obj->ivrlog->test1->find()->sort(['time'=>-1])->limit($limit)->skip($offset);

                //总页数
                $cnt=$obj->ivrlog->test1->find()->count();
                $cnt_page=intval(ceil($cnt/$limit));

                foreach ($res as $row)
                {
                    //把所有mongo数据取出来
                    $tmp[]=$row;
                }

                //重新整理并发送给前端显示
                foreach ($tmp as &$row)
                {
                    unset($row['_id']);

                    if ($row['mysqlPID']!='')
                    {
                        try
                        {
                            $vocal=VocalPrintModel::findOrFail($row['mysqlPID']);
                            $row['mysqlPID']='<a target=_BLANK href=/'.Config::get('constant.voice_path').$vocal->vp_ivr_url.'>'.'语音'.'</a>';
                        }
                        catch(ModelNotFoundException $e)
                        {
                            $row['mysqlPID']='<a href=#>'.'客户被删除'.'</a>';
                        }
                    }else
                    {
                        $row['mysqlPID']='空';
                    }

                    $row['time']=date("Y-m-d H:i:s",$row['time']);

                    if ($row['result']=='0')
                    {
                        $row['result']='正常';
                    }else
                    {
                        $row['result']='异常';
                    }
                }

                $data=$tmp;

                return ['error'=>'0','data'=>$data,'pages'=>$cnt_page,'count_data'=>$cnt];

                break;

            case 'get_loop_return_mongo_data':

                //用户传入的页
                $now_page=Input::get('page');

                //每页显示几条数据
                $limit=15;

                //从第几条开始显示
                $offset=($now_page-1)*$limit;

                $obj=$this->mymongo();

                //查询数据
                $res=$obj->ivrlog->loopreturn->find()->sort(['time'=>-1])->limit($limit)->skip($offset);

                //总页数
                $cnt=$obj->ivrlog->loopreturn->find()->count();
                $cnt_page=intval(ceil($cnt/$limit));

                foreach ($res as $row)
                {
                    //把所有mongo数据取出来
                    $tmp[]=$row;
                }

                //重新整理并发送给前端显示
                foreach ($tmp as &$row)
                {
                    unset($row['_id']);

                    if ($row['mysqlPID']!='')
                    {
                        try
                        {
                            $vocal=VocalPrintModel::findOrFail($row['mysqlPID']);
                            $row['mysqlPID']='<a target=_BLANK href=/'.Config::get('constant.voice_path').$vocal->vp_ivr_url.'>'.'语音'.'</a>';
                        }
                        catch(ModelNotFoundException $e)
                        {
                            $row['mysqlPID']='<a href=#>'.'客户被删除'.'</a>';
                        }
                    }else
                    {
                        $row['mysqlPID']='空';
                    }

                    $row['time']=date("Y-m-d H:i:s",$row['time']);

                    if ($row['result']=='0')
                    {
                        $row['result']='正常';
                    }else
                    {
                        $row['result']='异常';
                    }
                }

                $data=$tmp;

                return ['error'=>'0','data'=>$data,'pages'=>$cnt_page,'count_data'=>$cnt];

                break;

            case '':



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

                //查询客户的认证类型
                switch (ConfirmTypeModel::find($cust_confirm_type)->confirm_name)
                {
                    case '文本无关':

                        $confirm_text='';

                        break;

                    case '文本相关':

                        $confirm_text=Config::get('confirm_type.text');

                        break;

                    case '动态口令':

                        for ($i=1;$i<=Config::get('confirm_type.repeat');$i++)
                        {
                            $confirm_text[]=rand(100000,999999);
                        }

                        break;
                }

                $data=[
                    'pid'=>Input::get('key'),//用户的主键号
                    'name'=>$cust_name,//用户的姓名
                    'phone'=>$cust_review_num,//年审手机号
                    'confirm_type'=>(string)$cust_confirm_type,//认证类型，文本无关，文本相关，动态口令
                    'confirm_text'=>$confirm_text//用户要说的话
                ];

                $res=$this->mycurl('http://127.0.0.1:7510/register',$data);

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
                        'mysqlPID'=>'',
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
                        'mysqlPID'=>'',
                        'time'=>time()
                    ]);

                    return ['error'=>'0','msg'=>'发送注册请求成功'];
                }

                break;

            //web给ivr发送用户验证请求
            case 'verify'://web给ivr发送用户验证请求，系统主动验证

                //通过客户编号查询年审号码
                $info=CustModel::find(Input::get('key'));
                $cust_name=$info->cust_name;
                $cust_review_num=$info->cust_review_num;
                $cust_confirm_type=$info->cust_confirm_type;

                //查询客户的认证类型
                switch (ConfirmTypeModel::find($cust_confirm_type)->confirm_name)
                {
                    case '文本无关':

                        $confirm_text='';

                        break;

                    case '文本相关':

                        $confirm_text=Config::get('confirm_type.text');

                        break;

                    case '动态口令':

                        for ($i=1;$i<=Config::get('confirm_type.repeat');$i++)
                        {
                            $confirm_text[]=rand(100000,999999);
                        }

                        break;
                }

                $data=[
                    'pid'=>Input::get('key'),//用户的主键号
                    'name'=>$cust_name,//用户的姓名
                    'phone'=>$cust_review_num,//年审手机号
                    'confirm_type'=>(string)$cust_confirm_type,//认证类型，文本无关，文本相关，动态口令
                    'confirm_text'=>$confirm_text//用户要说的话
                ];

                $res=$this->mycurl('http://127.0.0.1:7510/verify',$data);

                //判断发送是否成功
                if ($res['error']=='1')
                {
                    $name=Session::get('user');
                    $obj=$this->mymongo();
                    $obj->ivrlog->test1->insert([
                        'who'=>$name[0]['staff_account'],
                        'action'=>'web给ivr发送验证请求',
                        'result'=>$res['error'],
                        'message'=>$res['msg'],
                        'mysqlPID'=>'',
                        'time'=>time()
                    ]);

                    return ['error'=>'1','msg'=>'发送验证请求失败'];
                }else
                {
                    $name=Session::get('user');
                    $obj=$this->mymongo();
                    $obj->ivrlog->test1->insert([
                        'who'=>$name[0]['staff_account'],
                        'action'=>'web给ivr发送验证请求',
                        'result'=>$res['error'],
                        'message'=>$cust_name.'验证请求已发送',
                        'mysqlPID'=>'',
                        'time'=>time()
                    ]);

                    return ['error'=>'0','msg'=>'发送验证请求成功'];
                }

                break;

            //web给ivr发送轮播认证请求
            case 'loop_call':

                foreach (Input::get('key') as $row)
                {
                    //判断开始时间是不是空
                    if ($row['name']=='star_date')
                    {
                        if ($row['value']=='')
                        {
                            return ['error'=>'1','msg'=>'开始时间不能为空'];
                        }else
                        {
                            $start=$row['value'];
                        }
                    }

                    //判断结束时间是不是空
                    if ($row['name']=='stop_date')
                    {
                        if ($row['value']=='')
                        {
                            return ['error'=>'1','msg'=>'结束时间不能为空'];
                        }else
                        {
                            $stop=$row['value'];
                        }
                    }

                    if ($row['name']=='cust_project')
                    {
                        $cond['cust_project']=$row['value'];
                    }

                    if ($row['name']=='cust_si_type')
                    {
                        $cond['cust_si_type']=$row['value'];
                    }

                    if ($row['name']=='cust_type')
                    {
                        $cond['cust_type']=$row['value'];
                    }
                }

                $res=CustModel::where($cond)->distinct()->get(['cust_review_num']);
                $phone_array=null;

                foreach ($res as $row)
                {
                    //Redis::lpush('start_loop',$row->cust_review_num);
                    $phone_array[]=$row->cust_review_num;
                }

                for ($i=1;$i<=Config::get('confirm_type.repeat');$i++)
                {
                    $rand_num[]=rand(100000,999999);
                }

                $data=[
                    'phone_array'=>$phone_array,//电话数组
                    'text_array'=>[Config::get('confirm_type.text'),$rand_num],//文本相关，动态口令
                    'cust_type'=>(string)$cond['cust_type'],//客户类型
                    'time'=>[$start,$stop]//认证时间段
                ];

                //发送请求
                $res=$this->mycurl('http://127.0.0.1:7510/loop',$data);

                //拼接mongo的log用
                $tar=ProjectModel::find($cond['cust_project'])->project_name;
                $si =SiTypeModel::find($cond['cust_si_type'])->si_name;

                //判断发送是否成功
                if ($res['error']=='1')
                {
                    $name=Session::get('user');
                    $obj=$this->mymongo();
                    $obj->ivrlog->loop->insert([
                        'who'=>$name[0]['staff_account'],
                        'action'=>'发送轮播请求',
                        'proj'=>$tar,
                        'si'=>$si,
                        'ctype'=>$cond['cust_type'],
                        'until'=>$start.'~'.$stop,
                        'result'=>$res['error'],
                        'message'=>$res['msg'],
                        'time'=>time()
                    ]);

                    return ['error'=>'1','msg'=>'发送轮播请求失败'];
                }else
                {
                    $name=Session::get('user');
                    $obj=$this->mymongo();
                    $obj->ivrlog->loop->insert([
                        'who'=>$name[0]['staff_account'],
                        'action'=>'发送轮播请求',
                        'proj'=>$tar,
                        'si'=>$si,
                        'ctype'=>$cond['cust_type'],
                        'until'=>$start.'~'.$stop,
                        'result'=>$res['error'],
                        'message'=>'轮播请求已发送',
                        'time'=>time()
                    ]);

                    return ['error'=>'0','msg'=>'发送轮播请求成功'];
                }

                break;
        }
    }

    //ivr返回用户注册的结果
    public function ivr_return_1()
    {
        //接收参数
        $pid=isset($_GET['pid']) ? $_GET['pid'] : '';//客户主键
        $url=isset($_GET['url']) ? $_GET['url'] : '';//客户录音文件
        $model_url=isset($_GET['model_url']) ? $_GET['model_url'] : '';//客户声纹模型

        //找到这个客户
        $model=CustModel::find($pid);

        if ($pid!='' && $url!='' && $model_url!='')
        {
            //修改数据库为已注册
            $model->update(['cust_register_flag'=>'1']);

            //把该客户的声纹url存起来
            $id=VocalPrintModel::create(['vp_id'=>$pid,'vp_action'=>'登记','vp_ivr_url'=>$url,'vp_model_url'=>$model_url]);

            //拿到这次返回结果插入数据库中的主键
            //dd($id->vp_pid);

            //通知mongo
            $obj=$this->mymongo();
            $obj->ivrlog->test1->insert([
                'who'=>'ivr',
                'action'=>'ivr返回用户登记结果',
                'result'=>'0',
                'message'=>$model->cust_name.'登记成功',
                'mysqlPID'=>$id->vp_pid,
                'time'=>time()
            ]);

            return ['error'=>'0'];
        }elseif($pid!='' && $url!='' && $model_url=='')
        {
            //把该客户的声纹url存起来
            $id=VocalPrintModel::create(['vp_id'=>$pid,'vp_action'=>'登记','vp_ivr_url'=>$url,'vp_model_url'=>'']);

            //通知mongo
            $obj=$this->mymongo();
            $obj->ivrlog->test1->insert([
                'who'=>'ivr',
                'action'=>'ivr返回用户登记结果',
                'result'=>'1',
                'message'=>$model->cust_name.'登记失败',
                'mysqlPID'=>$id->vp_pid,
                'time'=>time()
            ]);

            return ['error'=>'1'];
        }else
        {
            return ['error'=>'1','msg'=>'参数不正确'];
        }
    }

    //ivr返回验证的结果
    public function ivr_return_2()
    {
        //接收参数
        $pid=isset($_GET['pid']) ? $_GET['pid'] : '';//客户主键
        $url=isset($_GET['url']) ? $_GET['url'] : '';//客户录音文件
        $res=isset($_GET['res']) ? $_GET['res'] : '';//客户验证返回结果

        //找到这个客户
        $model=CustModel::find($pid);

        if ($pid!='' && $url!='' && $res=='Y')
        {
            //把该客户的声纹url存起来
            $id=VocalPrintModel::create(['vp_id'=>$pid,'vp_action'=>'验证','vp_ivr_url'=>$url,'vp_model_url'=>'']);

            //通知mongo
            $obj=$this->mymongo();
            $obj->ivrlog->test1->insert([
                'who'=>'ivr',
                'action'=>'ivr返回用户验证结果',
                'result'=>'0',
                'message'=>$model->cust_name.'验证成功',
                'mysqlPID'=>$id->vp_pid,
                'time'=>time()
            ]);

            return ['error'=>'0'];
        }elseif ($pid!='' && $url!='' && $res=='N')
        {
            //把该客户的声纹url存起来
            $id=VocalPrintModel::create(['vp_id'=>$pid,'vp_action'=>'验证','vp_ivr_url'=>$url,'vp_model_url'=>'']);

            //通知mongo
            $obj=$this->mymongo();
            $obj->ivrlog->test1->insert([
                'who'=>'ivr',
                'action'=>'ivr返回用户验证结果',
                'result'=>'1',
                'message'=>$model->cust_name.'验证失败，模型不匹配',
                'mysqlPID'=>$id->vp_pid,
                'time'=>time()
            ]);

            return ['error'=>'1'];
        }else
        {
            return ['error'=>'1','msg'=>'参数不正确'];
        }
    }

    //ivr返回轮播的结果
    public function ivr_return_3()
    {
        //接收参数
        $pid=isset($_GET['pid']) ? $_GET['pid'] : '';//客户主键
        $url=isset($_GET['url']) ? $_GET['url'] : '';//客户录音文件
        $res=isset($_GET['res']) ? $_GET['res'] : '';//客户验证返回结果

        //找到这个客户
        $model=CustModel::find($pid);

        if ($pid!='' && $url!='' && $res=='Y')
        {
            //把该客户的声纹url存起来
            $id=VocalPrintModel::create(['vp_id'=>$pid,'vp_action'=>'轮播','vp_ivr_url'=>$url,'vp_model_url'=>'']);

            //加入认证表中
            CustConfirmModel::create(['confirm_pid'=>$pid,'confirm_res'=>$res,'confirm_btw'=>'']);

            //通知mongo
            $obj=$this->mymongo();
            $obj->ivrlog->loopreturn->insert([
                'who'=>'ivr',
                'action'=>'ivr返回用户轮播结果',
                'result'=>'0',
                'message'=>$model->cust_name.'认证成功',
                'mysqlPID'=>$id->vp_pid,
                'time'=>time()
            ]);

            return ['error'=>'0'];
        }elseif ($pid!='' && $url!='' && $res=='N')
        {
            //把该客户的声纹url存起来
            $id=VocalPrintModel::create(['vp_id'=>$pid,'vp_action'=>'轮播','vp_ivr_url'=>$url,'vp_model_url'=>'']);

            //加入认证表中
            CustConfirmModel::create(['confirm_pid'=>$pid,'confirm_res'=>$res,'confirm_btw'=>'']);

            //通知mongo
            $obj=$this->mymongo();
            $obj->ivrlog->loopreturn->insert([
                'who'=>'ivr',
                'action'=>'ivr返回用户轮播结果',
                'result'=>'1',
                'message'=>$model->cust_name.'认证失败，模型不匹配',
                'mysqlPID'=>$id->vp_pid,
                'time'=>time()
            ]);

            return ['error'=>'1'];
        }else
        {
            return ['error'=>'1','msg'=>'参数不正确'];
        }
    }

    //客户主动认证的结果
    public function ivr_return_4()
    {
        //接收参数
        $pid=isset($_GET['pid']) ? $_GET['pid'] : '';//客户主键
        $url=isset($_GET['url']) ? $_GET['url'] : '';//客户录音文件
        $res=isset($_GET['res']) ? $_GET['res'] : '';//客户验证返回结果

        //找到这个客户
        $model=CustModel::find($pid);

        if ($pid!='' && $url!='' && $res=='Y')
        {
            //把该客户的声纹url存起来
            $id=VocalPrintModel::create(['vp_id'=>$pid,'vp_action'=>'主动','vp_ivr_url'=>$url,'vp_model_url'=>'']);

            //加入认证表中
            CustConfirmModel::create(['confirm_pid'=>$pid,'confirm_res'=>$res,'confirm_btw'=>'']);

            //通知mongo
            $obj=$this->mymongo();
            $obj->ivrlog->loopreturn->insert([
                'who'=>'ivr',
                'action'=>'ivr返回用户主动认证',
                'result'=>'0',
                'message'=>$model->cust_name.'认证成功',
                'mysqlPID'=>$id->vp_pid,
                'time'=>time()
            ]);

            return ['error'=>'0'];
        }elseif ($pid!='' && $url!='' && $res=='N')
        {
            //把该客户的声纹url存起来
            $id=VocalPrintModel::create(['vp_id'=>$pid,'vp_action'=>'主动','vp_ivr_url'=>$url,'vp_model_url'=>'']);

            //加入认证表中
            CustConfirmModel::create(['confirm_pid'=>$pid,'confirm_res'=>$res,'confirm_btw'=>'']);

            //通知mongo
            $obj=$this->mymongo();
            $obj->ivrlog->loopreturn->insert([
                'who'=>'ivr',
                'action'=>'ivr返回用户主动认证',
                'result'=>'1',
                'message'=>$model->cust_name.'认证失败，模型不匹配',
                'mysqlPID'=>$id->vp_pid,
                'time'=>time()
            ]);

            return ['error'=>'1'];
        }else
        {
            return ['error'=>'1','msg'=>'参数不正确'];
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

    //测试用的
    public function ceshi_test()
    {









    }






}
