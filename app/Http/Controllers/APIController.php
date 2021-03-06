<?php

namespace App\Http\Controllers;

use App\Http\Model\ConfirmTypeModel;
use App\Http\Model\CustBelongTI;
use App\Http\Model\CustConfirmModel;
use App\Http\Model\CustFVModel;
use App\Http\Model\CustModel;
use App\Http\Model\LogModel;
use App\Http\Model\ProjectModel;
use App\Http\Model\SiTypeModel;
use App\Http\Model\StaffModel;
use App\Http\Model\TextIndependentModel;
use App\Http\Model\VocalPrintModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;
use League\Flysystem\Exception;

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
                        return ['error'=>'1','msg'=>'数据库中没有这个电话'];
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

            case 'is_register':

                try
                {
                    $res=CustModel::findOrFail($_GET['cond']);

                    if ($res->cust_register_flag=='1')
                    {
                        return 'Y';
                    }else
                    {
                        return 'N';
                    }
                }
                catch(ModelNotFoundException $e)
                {
                    return ['error'=>'1','msg'=>'该客户不存在'];
                }

                break;

            case 'is_death':

                try
                {
                    $res=CustModel::findOrFail($_GET['cond']);

                    if ($res->cust_death_flag=='1')
                    {
                        return 'Y';
                    }else
                    {
                        return 'N';
                    }
                }
                catch(ModelNotFoundException $e)
                {
                    return ['error'=>'1','msg'=>'该客户不存在'];
                }

                break;

            case 'is_local_number':

                //传入一个电话号码，如果是本地的，返回true，如果不是，返回false
                //电话号码先到数据库中查找，如果查不到，调用接口
                return $this->is_local_phone($_GET['cond']);

                break;

            case 'vpr_initiative_process':

                //主动登记
                if (!$this->check_something(trim($_GET['phonenum']),'phonenumber',null))
                {
                    return ['error'=>'1','msg'=>'手机号码输入不正确'];
                }

                $phonenum=trim($_GET['phonenum']);

                //电话在不在系统里
                $res=CustModel::where('cust_review_num',$phonenum)->get();

                if (count($res)=='0')
                {
                    return ['error'=>'1','msg'=>'没客户'];

                }elseif (count($res)=='1' || count($res)=='2')
                {
                    $data=$this->custReg($res);

                    return $data;

                }else
                {
                    return ['error'=>'1','msg'=>'一大堆客户？'];
                }

                break;

            case 'getRelation':

                $cond=trim($_GET['cond']);

                if (empty($cond))
                {
                    return ['msg'=>'error'];
                }else
                {
                    return Redis::get($cond);
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

    //setRelation
    public function setRelation()
    {
        //客户用协办员的手机号打电话，需要建立一个由：客户手机号，客户身份证号，协办员账号组成的关系
        $phone=isset($_GET['phone']) ? $_GET['phone'] : '';
        $idcard=isset($_GET['idcard']) ? $_GET['idcard'] : '';
        $staff=isset($_GET['staff']) ? $_GET['staff'] : '';

        if (trim($phone)=='')
        {
            return ['state'=>'error','msg'=>'phone can not be empty'];
        }
        $res=CustModel::where('cust_review_num',trim($phone))->first()->toArray();
        if (!empty($res))
        {
            return ['state'=>'error','msg'=>'phone has already existed'];
        }

        if (trim($idcard)=='')
        {
            return ['state'=>'error','msg'=>'idcard can not be empty'];
        }
        $res=CustModel::where('cust_id',trim($idcard))->first()->toArray();
        if (!empty($res))
        {
            return ['state'=>'error','msg'=>'idcard has already existed'];
        }

        if (trim($staff)=='')
        {
            return ['state'=>'error','msg'=>'staff can not be empty'];
        }

        $this->redis_set(trim($staff),trim($phone).'_'.trim($idcard),20);

        return ['state'=>'pass','msg'=>'complete'];
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

                if (empty($tmp))
                {
                    return ['error'=>'1','msg'=>'没有轮播记录'];
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

                if (empty($tmp))
                {
                    return ['error'=>'1','msg'=>'未取得数据或数据是空'];
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

                    if ($row['score']=='')
                    {
                        $row['score']='空';
                    }

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

            case 'get_fv_register_mongo_data':

                //用户传入的页
                $now_page=Input::get('page');

                //每页显示几条数据
                $limit=15;

                //从第几条开始显示
                $offset=($now_page-1)*$limit;

                $obj=$this->mymongo();

                //查询数据
                $res=$obj->Finger->CustTemplate->find([],['_id'=>'1'])->sort(['time'=>-1])->limit($limit)->skip($offset);

                //总页数
                $cnt=$obj->Finger->CustTemplate->find()->count();
                $cnt_page=intval(ceil($cnt/$limit));

                foreach ($res as $row)
                {
                    //把所有mongo数据取出来
                    $tmp[]=$row;
                }

                if (empty($tmp))
                {
                    return ['error'=>'1','msg'=>'未取得数据或数据是空'];
                }

                //重新整理并发送给前端显示
                foreach ($tmp as $row1)
                {
                    try
                    {
                        $my_tmp1=CustFVModel::findOrFail($row1['_id']);
                        $my_tmp2[]=[
                            $my_tmp1->cust_name,
                            $my_tmp1->cust_id,
                            $my_tmp1->cust_phone_num,
                            $my_tmp1->cust_phone_bku,
                            $my_tmp1->cust_last_confirm_date,
                            (string)$my_tmp1->created_at
                        ];
                    }
                    catch(ModelNotFoundException $e)
                    {

                    }
                }

                $data=$my_tmp2;

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

                if (empty($tmp))
                {
                    return ['error'=>'1','msg'=>'未取得数据或数据是空'];
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

                    if ($row['score']=='')
                    {
                        $row['score']='空';
                    }

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

            case 'get_fv_confirm_mongo_data':

                //用户传入的页
                $now_page=Input::get('page');

                //每页显示几条数据
                $limit=15;

                //从第几条开始显示
                $offset=($now_page-1)*$limit;

                $obj=$this->mymongo();

                //查询数据
                $res=$obj->Finger->ConfirmRes->find()->sort(['sort'=>-1])->limit($limit)->skip($offset);

                //总页数
                $cnt=$obj->Finger->ConfirmRes->find()->count();
                $cnt_page=intval(ceil($cnt/$limit));

                foreach ($res as $row)
                {
                    //把所有mongo数据取出来
                    $tmp[]=$row;
                }

                if (empty($tmp))
                {
                    return ['error'=>'1','msg'=>'未取得数据或数据是空'];
                }

                //重新整理并发送给前端显示
                foreach ($tmp as $row1)
                {
                    try
                    {
                        $staffname=StaffModel::findOrFail($row1['sno']);

                        $my_tmp1=CustFVModel::findOrFail($row1['id_in_mysql']);

                        $fv=$row1['res_of_fv']=='true'?'<font color="green">认证通过</font>':'<font color="red">认证失败</font>';
                        $fp=$row1['res_of_fp']=='true'?'<font color="green">认证通过</font>':'<font color="red">认证失败</font>';

                        $my_tmp2[]=[
                            $staffname->staff_name,
                            $my_tmp1->cust_name,
                            $my_tmp1->cust_id,
                            $fv,
                            $fp,
                            date('Y-m-d H:i:s',$row1['sort'])
                        ];
                    }
                    catch(ModelNotFoundException $e)
                    {

                    }
                }

                $data=$my_tmp2;

                return ['error'=>'0','data'=>$data,'pages'=>$cnt_page,'count_data'=>$cnt];

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

                //几秒内连续点击，不进行登记
                if (Redis::get($cust_review_num.'_register')!='')
                {
                    return ['error'=>'1','msg'=>'正在处理，请稍后'];
                }else
                {
                    $this->redis_set($cust_review_num.'_register','123',9);
                }

                //查询客户的认证类型
                switch (ConfirmTypeModel::find($cust_confirm_type)->confirm_name)
                {
                    case '文本无关':

                        $totle=TextIndependentModel::count();

                        $arr=[];
                        for ($i=1;$i<=Config::get('confirm_type.repeat');$i++)
                        {
                            $new=rand(1,$totle);

                            if (in_array($new,$arr))
                            {
                                $i--;
                            }else
                            {
                                //这里存的是mysql表中，文本无关数字的pid
                                $arr[]=$new;
                            }
                        }

                        CustBelongTI::updateOrCreate(['cust_id'=>Input::get('key')],['ti_pid'=>implode(',',$arr)]);

                        $res=TextIndependentModel::whereIn('ti_pid',$arr)->get();

                        foreach ($res as $row)
                        {
                            $confirm_text[]=$row->ti_text;
                        }

                        break;

                    case '文本相关':

                        $confirm_text=Config::get('confirm_type.text');

                        break;

                    case '动态口令':

                        $confirm_text=$this->myrand();

                        break;
                }

//                $data=[
//                    'pid'=>Input::get('key'),//用户的主键号
//                    'name'=>$cust_name,//用户的姓名
//                    'phone'=>$cust_review_num,//年审手机号
//                    'confirm_type'=>(string)$cust_confirm_type,//认证类型，文本无关，文本相关，动态口令
//                    'confirm_text'=>$confirm_text//用户要说的话
//                ];

                $res=CustModel::where('cust_review_num',$cust_review_num)->get();

                switch (count($res))
                {
                    case '0':

                        //没查到客户

                        return ['error'=>'1','msg'=>'未知客户'];

                        break;

                    case '1':

                        //一个年审人

                        $data=[
                            'cust_type'=>$res[0]->cust_type,
                            'confirm_type'=>$res[0]->cust_confirm_type,
                            'authorization'=>'authorized',
                            'phone_number'=>$cust_review_num,
                            'primary'=>[
                                'pid'=>$res[0]->cust_num,
                                'name'=>$res[0]->cust_name,
                                'idcard'=>$res[0]->cust_id,
                                'status'=>$res[0]->cust_register_flag=='1' ? 'registered' : 'unregistered',
                                'confirm_text'=>$confirm_text
                            ]
                        ];

                        break;

                    case '2':

                        //两个年审人

                        $data=[
                            'cust_type'=>$res[0]->cust_type,
                            'confirm_type'=>$res[0]->cust_confirm_type,
                            'authorization'=>'authorized',
                            'phone_number'=>$cust_review_num,
                            'primary'=>[
                                'pid'=>$res[0]->cust_num,
                                'name'=>$res[0]->cust_name,
                                'idcard'=>$res[0]->cust_id,
                                'status'=>$res[0]->cust_register_flag=='1' ? 'registered' : 'unregistered',
                                'confirm_text'=>$confirm_text
                            ],
                            'secondary'=>[
                                'pid'=>$res[1]->cust_num,
                                'name'=>$res[1]->cust_name,
                                'idcard'=>$res[1]->cust_id,
                                'status'=>$res[1]->cust_register_flag=='1' ? 'registered' : 'unregistered',
                                'confirm_text'=>$confirm_text
                            ]
                        ];

                        //获取点击的是哪个年审人
                        $whitch=Input::get('key');

                        if (data_get($data,'primary.pid')==Input::get('key'))
                        {
                            //点击的是第一年审人
                            //如果第二年审人是已注册，那么不用管
                            //如果是未注册，那么需要得到一个三组数字，并且存入mysql
                            if (data_get($data,'secondary.status')=='unregistered')
                            {
                                $totle=TextIndependentModel::count();

                                $arr=[];
                                for ($i=1;$i<=Config::get('confirm_type.repeat');$i++)
                                {
                                    $new=rand(1,$totle);

                                    if (in_array($new,$arr))
                                    {
                                        $i--;
                                    }else
                                    {
                                        //这里存的是mysql表中，文本无关数字的pid
                                        $arr[]=$new;
                                    }
                                }

                                CustBelongTI::updateOrCreate(['cust_id'=>data_get($data,'secondary.pid')],['ti_pid'=>implode(',',$arr)]);

                                $res=TextIndependentModel::whereIn('ti_pid',$arr)->get();

                                foreach ($res as $row)
                                {
                                    $confirm_text1[]=$row->ti_text;
                                }

                                array_set($data,'secondary.confirm_text',$confirm_text1);
                            }

                        }else
                        {
                            //点击的是第二年审人
                            //如果第一年审人是已注册，那么不用管
                            //如果是未注册，那么需要得到一个三组数字，并且存入mysql
                            if (data_get($data,'primary.status')=='unregistered')
                            {
                                $totle=TextIndependentModel::count();

                                $arr=[];
                                for ($i=1;$i<=Config::get('confirm_type.repeat');$i++)
                                {
                                    $new=rand(1,$totle);

                                    if (in_array($new,$arr))
                                    {
                                        $i--;
                                    }else
                                    {
                                        //这里存的是mysql表中，文本无关数字的pid
                                        $arr[]=$new;
                                    }
                                }

                                CustBelongTI::updateOrCreate(['cust_id'=>data_get($data,'primary.pid')],['ti_pid'=>implode(',',$arr)]);

                                $res=TextIndependentModel::whereIn('ti_pid',$arr)->get();

                                foreach ($res as $row)
                                {
                                    $confirm_text1[]=$row->ti_text;
                                }

                                array_set($data,'primary.confirm_text',$confirm_text1);
                            }
                        }

                        break;
                }

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
                        'score'=>'',
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
                        'score'=>'',
                        'time'=>time()
                    ]);

                    return ['error'=>'0','msg'=>'发送注册请求成功'];
                }

                break;

            //web给ivr发送用户验证请求
            case 'verify':

                //通过客户编号查询年审号码
                $info=CustModel::find(Input::get('key'));
                $cust_name=$info->cust_name;
                $cust_review_num=$info->cust_review_num;
                $cust_confirm_type=$info->cust_confirm_type;

                //查询客户的认证类型
                switch (ConfirmTypeModel::find($cust_confirm_type)->confirm_name)
                {
                    case '文本无关':

                        $confirm_text=$this->myrand();

                        break;

                    case '文本相关':

                        $confirm_text=Config::get('confirm_type.text');

                        break;

                    case '动态口令':

                        $confirm_text=$this->myrand('verify');

                        break;
                }

//                $data=[
//                    'pid'=>Input::get('key'),//用户的主键号
//                    'name'=>$cust_name,//用户的姓名
//                    'phone'=>$cust_review_num,//年审手机号
//                    'confirm_type'=>(string)$cust_confirm_type,//认证类型，文本无关，文本相关，动态口令
//                    'confirm_text'=>$confirm_text//用户要说的话
//                ];

                $data=[
                    'cust_type'=>$info->cust_type,
                    'confirm_type'=>$info->cust_confirm_type,
                    'authorization'=>'authorized',
                    'phone_number'=>$cust_review_num,
                    'primary'=>[
                        'pid'=>$info->cust_num,
                        'name'=>$info->cust_name,
                        'idcard'=>$info->cust_id,
                        'status'=>$info->cust_register_flag=='1' ? 'registered' : 'unregistered',
                        'confirm_text'=>$confirm_text
                    ]
                ];

                $res=CustBelongTI::where('cust_id',$info->cust_num)->get();

                //$confirm_text替换第0个为曾经登记过的
                $key=explode(',',$res[0]->ti_pid);

                $num=rand(0,count($key)-1);

                $res=TextIndependentModel::find($key[$num]);

                $confirm_text[0]=$res->ti_text;

                array_set($data,'primary.confirm_text',$confirm_text);

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
                        'score'=>'',
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
                        'score'=>'',
                        'time'=>time()
                    ]);

                    return ['error'=>'0','msg'=>'发送验证请求成功'];
                }

                break;

            //web给ivr发送轮播认证请求
            case 'loop_call':

                //当前时间
                $now=time();

                //多少秒之内不能发送第二次
                if (Redis::get('last_time_of_loop')==null)
                {
                    Redis::set('last_time_of_loop',$now);
                }else
                {
                    //小于多少秒
                    if ($now-Redis::get('last_time_of_loop')<Config::get('constant.until_time'))
                    {
                        return ['error'=>'1','msg'=>Config::get('constant.until_time').'秒之内不能发第二次轮播了'];
                    }
                }

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

                //$res是给ivr发送的电话列表，去掉重复项
                $res=CustModel::where($cond)->distinct()->get(['cust_review_num']);
                $phone_array=null;

                //$totle是redis里记录的这次轮播的人数
                $totle=CustModel::where($cond)->count();

                foreach ($res as $row)
                {
                    $phone_array[]=$row->cust_review_num;
                }

                $rand_num=$this->myrand('verify');

                $data=[
                    'phone_array'=>$phone_array,//电话数组
                    'text_array'=>[Config::get('confirm_type.text'),$rand_num],//文本相关，动态口令
                    'cust_type'=>(string)$cond['cust_type'],//客户类型
                    'time'=>[$start,$stop],//认证时间段
                    'unix_time'=>(string)$now//当前时间
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
                        'time'=>time(),
                        'finishANDtotal'=>''
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
                        'time'=>time(),
                        'finishANDtotal'=>$now//这个时间戳也是redis的键值
                    ]);

                    //设置redis的键值对
                    Redis::set('last_time_of_loop',$now);
                    //轮播总用户数
                    Redis::set('loop_totle_'.$now,$totle);
                    //未完成用户数
                    Redis::set('loop_unfinished_'.$now,$totle);
                    //完成用户数
                    Redis::set('loop_finish_'.$now,0);
                    //未认证通过用户数
                    Redis::set('loop_unpass_'.$now,0);
                    //认证通过用户数
                    Redis::set('loop_pass_'.$now,0);

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
        $score=isset($_GET['score']) ? $_GET['score'] : '';//客户分数

        //找到这个客户
        $model=CustModel::find($pid);

        if ($pid!='' && $url!='' && $model_url!='')
        {
            //修改数据库为已注册
            $model->update(['cust_register_flag'=>'1']);

            //把该客户的声纹url存起来
            $id=VocalPrintModel::create(['vp_id'=>$pid,'vp_action'=>'登记','vp_ivr_url'=>$url,'vp_model_url'=>$model_url]);

            //拿到这次返回结果插入数据库中的主键
            //通知mongo
            $obj=$this->mymongo();
            $obj->ivrlog->test1->insert([
                'who'=>'ivr',
                'action'=>'ivr返回用户登记结果',
                'result'=>'0',
                'message'=>$model->cust_name.'登记成功',
                'mysqlPID'=>$id->vp_pid,
                'score'=>'',
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
                'score'=>'',
                'time'=>time()
            ]);

            return ['error'=>'1'];
        }elseif ($url=='')
        {
            //通知mongo
            $obj=$this->mymongo();
            $obj->ivrlog->test1->insert([
                'who'=>'ivr',
                'action'=>'ivr返回用户登记结果',
                'result'=>'1',
                'message'=>$model->cust_name.'登记失败，呼叫失败',
                'mysqlPID'=>'',
                'score'=>'',
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
        $score=isset($_GET['score']) ? number_format($_GET['score'],2) : '';//客户分数

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
                'score'=>$score,
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
                'score'=>$score,
                'time'=>time()
            ]);

            return ['error'=>'1'];
        }elseif ($url=='')
        {
            //通知mongo
            $obj=$this->mymongo();
            $obj->ivrlog->test1->insert([
                'who'=>'ivr',
                'action'=>'ivr返回用户验证结果',
                'result'=>'1',
                'message'=>$model->cust_name.'验证失败，呼叫失败',
                'mysqlPID'=>'',
                'score'=>$score,
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
        $score=isset($_GET['score']) ? number_format($_GET['score'],2) : '';//客户分数

        //找到这个客户
        $model=CustModel::find($pid);

        if ($pid!='' && $url!='' && $res=='Y')
        {
            //把该客户的声纹url存起来
            $id=VocalPrintModel::create(['vp_id'=>$pid,'vp_action'=>'轮播','vp_ivr_url'=>$url,'vp_model_url'=>'']);

            //加入认证表中
            CustConfirmModel::create(['confirm_pid'=>$pid,'confirm_res'=>$res,'confirm_btw'=>'','belong_to'=>'0']);

            //通知mongo
            $obj=$this->mymongo();
            $obj->ivrlog->loopreturn->insert([
                'who'=>'ivr',
                'action'=>'ivr返回用户轮播结果',
                'result'=>'0',
                'message'=>$model->cust_name.'认证成功',
                'mysqlPID'=>$id->vp_pid,
                'score'=>$score,
                'time'=>time()
            ]);

            return ['error'=>'0'];
        }elseif ($pid!='' && $url!='' && $res=='N')
        {
            //把该客户的声纹url存起来
            $id=VocalPrintModel::create(['vp_id'=>$pid,'vp_action'=>'轮播','vp_ivr_url'=>$url,'vp_model_url'=>'']);

            //加入认证表中
            CustConfirmModel::create(['confirm_pid'=>$pid,'confirm_res'=>$res,'confirm_btw'=>'','belong_to'=>'0']);

            //通知mongo
            $obj=$this->mymongo();
            $obj->ivrlog->loopreturn->insert([
                'who'=>'ivr',
                'action'=>'ivr返回用户轮播结果',
                'result'=>'1',
                'message'=>$model->cust_name.'认证失败，模型不匹配',
                'mysqlPID'=>$id->vp_pid,
                'score'=>$score,
                'time'=>time()
            ]);

            return ['error'=>'1'];
        }elseif ($pid!='' && $url=='' && $res=='error_1')
        {
            //加入认证表中
            CustConfirmModel::create(['confirm_pid'=>$pid,'confirm_res'=>'N','confirm_btw'=>'没有登记','belong_to'=>'0']);

            //通知mongo
            $obj=$this->mymongo();
            $obj->ivrlog->loopreturn->insert([
                'who'=>'ivr',
                'action'=>'ivr返回用户轮播结果',
                'result'=>'1',
                'message'=>$model->cust_name.'还没有进行登记',
                'mysqlPID'=>'',
                'score'=>$score,
                'time'=>time()
            ]);

            return ['error'=>'1'];
        }elseif ($url=='')
        {
            //加入认证表中
            CustConfirmModel::create(['confirm_pid'=>$pid,'confirm_res'=>'N','confirm_btw'=>'呼叫失败','belong_to'=>'0']);

            //通知mongo，这个情况是该客户还没有进行登记
            $obj=$this->mymongo();
            $obj->ivrlog->loopreturn->insert([
                'who'=>'ivr',
                'action'=>'ivr返回用户轮播结果',
                'result'=>'1',
                'message'=>$model->cust_name.'认证失败，呼叫失败',
                'mysqlPID'=>'',
                'score'=>$score,
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
        $score=isset($_GET['score']) ? number_format($_GET['score'],2) : '';//客户分数

        //找到这个客户
        $model=CustModel::find($pid);

        if ($pid!='' && $url!='' && $res=='Y')
        {
            //把该客户的声纹url存起来
            $id=VocalPrintModel::create(['vp_id'=>$pid,'vp_action'=>'主动','vp_ivr_url'=>$url,'vp_model_url'=>'']);

            //加入认证表中
            CustConfirmModel::create(['confirm_pid'=>$pid,'confirm_res'=>$res,'confirm_btw'=>'','belong_to'=>'0']);

            //通知mongo
            $obj=$this->mymongo();
            $obj->ivrlog->loopreturn->insert([
                'who'=>'ivr',
                'action'=>'ivr返回用户主动认证',
                'result'=>'0',
                'message'=>$model->cust_name.'认证成功',
                'mysqlPID'=>$id->vp_pid,
                'score'=>$score,
                'time'=>time()
            ]);

            return ['error'=>'0'];
        }elseif ($pid!='' && $url!='' && $res=='N')
        {
            //把该客户的声纹url存起来
            $id=VocalPrintModel::create(['vp_id'=>$pid,'vp_action'=>'主动','vp_ivr_url'=>$url,'vp_model_url'=>'']);

            //加入认证表中
            CustConfirmModel::create(['confirm_pid'=>$pid,'confirm_res'=>$res,'confirm_btw'=>'','belong_to'=>'0']);

            //通知mongo
            $obj=$this->mymongo();
            $obj->ivrlog->loopreturn->insert([
                'who'=>'ivr',
                'action'=>'ivr返回用户主动认证',
                'result'=>'1',
                'message'=>$model->cust_name.'认证失败，模型不匹配',
                'mysqlPID'=>$id->vp_pid,
                'score'=>$score,
                'time'=>time()
            ]);

            return ['error'=>'1'];
        }else
        {
            return ['error'=>'1','msg'=>'参数不正确'];
        }
    }
}
