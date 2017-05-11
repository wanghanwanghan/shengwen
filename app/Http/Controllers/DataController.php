<?php

namespace App\Http\Controllers;

use App\Http\Model\ConfirmTypeModel;
use App\Http\Model\CustConfirmModel;
use App\Http\Model\CustDeleteModel;
use App\Http\Model\CustModel;
use App\Http\Model\LevelModel;
use App\Http\Model\ProjectModel;
use App\Http\Model\SendMailModel;
use App\Http\Model\SiTypeModel;
use App\Http\Model\StaffModel;
use App\Http\Model\VocalPrintModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;

class DataController extends Controller
{
    public function ajax()
    {
        switch (Input::get('type'))
        {
            case 'get_si_type':

                $model=SiTypeModel::get(['si_id','si_name'])->toArray();

                $model=$this->change_arr_key($model,['si_id'=>'id','si_name'=>'name']);

                array_unshift($model,['id'=>'0','name'=>'设置为最顶级地区']);

                return ['error'=>'0','msg'=>'成功','data'=>$model];

                break;

            case 'get_project':

                $model=ProjectModel::get(['project_id','project_name','project_parent'])->toArray();

                $model=$this->infinite($model,'project');

                $model=$this->change_arr_key($model,['project_id'=>'id','project_name'=>'name']);

                array_unshift($model,['id'=>'0','name'=>'设置为最顶级地区']);

                return ['error'=>'0','msg'=>'成功','data'=>$model];

                break;

            case 'get_level':

                $model=LevelModel::get(['level_id','level_name','level_parent'])->toArray();

                $model=$this->infinite($model,'level');

                $model=$this->change_arr_key($model,['level_id'=>'id','level_name'=>'name']);

                array_unshift($model,['id'=>'0','name'=>'设置为最顶级权限']);

                return ['error'=>'0','msg'=>'成功','data'=>$model];

                break;

            case 'add_project':

                $data=Input::get('key');

                foreach ($data as $row)
                {
                    if ($row['name']=='project_name')
                    {
                        if ($row['value']=='')
                        {
                            return ['error'=>'1','msg'=>'地区名称不能为空'];
                        }else
                        {
                            if (!$this->check_chinese_word($row['value']))
                            {
                                return ['error'=>'1','msg'=>'地区名称必须为中文'];
                            }else
                            {
                                $proj=$row['value'];
                            }
                        }
                    }elseif ($row['name']=='project_parent')
                    {
                        if ($row['value']=='')
                        {
                            return ['error'=>'1','msg'=>'必须选择一个属地'];
                        }else
                        {
                            ProjectModel::create(['project_name'=>$proj,'project_parent'=>$row['value']]);

                            $this->system_log('添加属地',$proj);

                            return ['error'=>'0','msg'=>'添加成功'];
                        }
                    }
                }

                return ['error'=>'1','msg'=>'未知错误'];

                break;

            case 'add_level':

                $data=Input::get('key');

                foreach ($data as $row)
                {
                    if ($row['name']=='level_name')
                    {
                        if ($row['value']=='')
                        {
                            return ['error'=>'1','msg'=>'权限名称不能为空'];
                        }else
                        {
                            if (!$this->check_chinese_word($row['value']))
                            {
                                return ['error'=>'1','msg'=>'权限名称必须为中文'];
                            }else
                            {
                                $level=$row['value'];
                            }
                        }
                    }elseif ($row['name']=='level_parent')
                    {
                        if ($row['value']=='')
                        {
                            return ['error'=>'1','msg'=>'必须选择一个权限'];
                        }else
                        {
                            LevelModel::create(['level_name'=>$level,'level_parent'=>$row['value']]);

                            $this->system_log('添加权限',$level);

                            return ['error'=>'0','msg'=>'添加成功'];
                        }
                    }
                }

                return ['error'=>'1','msg'=>'未知错误'];

                break;

            case 'add_si_type':

                $data=Input::get('key');

                foreach ($data as $row)
                {
                    if ($row['value']=='')
                    {
                        return ['error'=>'1','msg'=>'参保类型不能为空'];
                    }elseif (!$this->check_chinese_word($row['value']))
                    {
                        return ['error'=>'1','msg'=>'参保类型必须为中文'];
                    }else
                    {
                        if (count(SiTypeModel::where(['si_name'=>$row['value']])->get())!='0')
                        {
                            return ['error'=>'1','msg'=>'参保类型已经存在'];
                        }else
                        {
                            SiTypeModel::create(['si_name'=>$row['value']]);

                            $this->system_log('添加参保类型',$row['value']);

                            return ['error'=>'0','msg'=>'添加成功'];
                        }
                    }
                }

                return ['error'=>'1','msg'=>'未知错误'];

                break;

            case 'add_confirm_type':

                $data=Input::get('key');

                foreach ($data as $row)
                {
                    if ($row['value']=='')
                    {
                        return ['error'=>'1','msg'=>'认证类型不能为空'];
                    }elseif (!$this->check_chinese_word($row['value']))
                    {
                        return ['error'=>'1','msg'=>'认证类型必须为中文'];
                    }else
                    {
                        if (count(ConfirmTypeModel::where(['confirm_name'=>$row['value']])->get())!='0')
                        {
                            return ['error'=>'1','msg'=>'认证类型已经存在'];
                        }else
                        {
                            ConfirmTypeModel::create(['confirm_name'=>$row['value']]);

                            $this->system_log('添加认证类型',$row['value']);

                            return ['error'=>'0','msg'=>'添加成功'];
                        }
                    }
                }

                return ['error'=>'1','msg'=>'未知错误'];

                break;

            case 'add_staff':

                $data=Input::get('key');

                foreach ($data as $row)
                {
                    if ($row['name']=='staff_account')
                    {
                        if ($row['value']=='')
                        {
                            return ['error'=>'1','msg'=>'账号不能为空'];
                        }

                        $res=count(StaffModel::where(['staff_account'=>$row['value']])->get()->toArray());

                        if (!empty($res))
                        {
                            return ['error'=>'1','msg'=>'账号已经存在了'];
                        }

                        $staff_info['staff_account']=$row['value'];
                    }

                    if ($row['name']=='staff_password')
                    {
                        if ($row['value']=='')
                        {
                            return ['error'=>'1','msg'=>'密码不能为空'];
                        }else
                        {
                            $staff_password=$row['value'];
                        }
                    }

                    if ($row['name']=='staff_confirm_password')
                    {
                        if ($row['value']=='')
                        {
                            return ['error'=>'1','msg'=>'确认密码不能为空'];
                        }else
                        {
                            $staff_confirm_password=$row['value'];
                        }
                    }

                    if ($row['name']=='staff_id')
                    {
                        if ($row['value']=='')
                        {
                            return ['error'=>'1','msg'=>'身份证号不能为空'];
                        }else
                        {
                            if (!$this->validation_filter_id_card($row['value']))
                            {
                                return ['error'=>'1','msg'=>'请输入正确的身份证号码'];
                            }else
                            {
                                $staff_info['staff_id']=$row['value'];
                            }
                        }
                    }

                    if ($row['name']=='staff_name')
                    {
                        if ($row['value']=='')
                        {
                            return ['error'=>'1','msg'=>'员工姓名不能为空'];
                        }else
                        {
                            if (!$this->check_chinese_word($row['value']))
                            {
                                return ['error'=>'1','msg'=>'员工姓名必须是中文'];
                            }else
                            {
                                $staff_info['staff_name']=$row['value'];
                            }
                        }
                    }

                    if ($row['name']=='staff_project')
                    {
                        $row['value']=json_decode($row['value'],true);

                        if (empty($row['value']))
                        {
                            return ['error'=>'1','msg'=>'请设置所属地区'];
                        }else
                        {
                            $staff_info['staff_project']=$this->arr2str($row['value']);
                        }
                    }

                    if ($row['name']=='staff_si_type')
                    {
                        $row['value']=json_decode($row['value'],true);

                        if (empty($row['value']))
                        {
                            return ['error'=>'1','msg'=>'请设置参保类型'];
                        }else
                        {
                            $staff_info['staff_si_type']=$this->arr2str($row['value']);
                        }
                    }

                    if ($row['name']=='staff_level')
                    {
                        $row['value']=json_decode($row['value'],true);

                        if (empty($row['value']))
                        {
                            return ['error'=>'1','msg'=>'请设置权限信息'];
                        }else
                        {
                            $staff_info['staff_level']=$this->arr2str($row['value']);
                        }
                    }
                }

                if ($staff_confirm_password!=$staff_password)
                {
                    return ['error'=>'1','msg'=>'两次输入的密码不一样'];
                }else
                {
                    $staff_info['staff_password']=substr(md5($staff_password),0,24);
                }

                StaffModel::create($staff_info);

                $this->system_log('添加新员工',$staff_info['staff_account']);

                return ['error'=>'0','msg'=>'添加成功'];

                break;

            case 'staff_login':

                $data=Input::get('key');

                $res=array_map(function($row){

                    if ($row['name']=='staff_account')
                    {
                        return $row['value'];
                    }else
                    {
                        return substr(md5($row['value']),0,24);
                    }

                },$data);

                $user_info=StaffModel::where(['staff_account'=>$res[0],'staff_password'=>$res[1]])->get()->toArray();

                if (count($user_info))
                {
                    Session::put('user',$user_info);
                    return ['error'=>'0','msg'=>'成功'];
                }else
                {
                    return ['error'=>'1','msg'=>'请输入正确的用户名和密码'];
                }

                break;

            case 'add_cust':

                $cust_info=null;

                foreach (Input::get('key') as $row)
                {
                    //用户姓名
                    if ($row['name']=='cust_name')
                    {
                        if (!$this->check_chinese_word($row['value']))
                        {
                            return ['error'=>'1','msg'=>'姓名必须是中文'];
                        }

                        $cust_info['cust_name']=$row['value'];
                    }

                    //身份证号
                    if ($row['name']=='cust_id')
                    {
                        if (!$this->is_idcard($row['value']))
                        {
                            return ['error'=>'1','msg'=>'身份证输入不正确'];
                        }

                        //转换成大写
                        $row['value']=strtoupper($row['value']);

                        //验证一下数据库中是否有相同的项
                        if (count(CustModel::where(['cust_id'=>$row['value']])->get()->toArray())!='0')
                        {
                            return ['error'=>'1','msg'=>'此身份证号已经存在，不能添加了'];
                        }

                        $cust_info['cust_id']=$row['value'];
                    }

                    //社保编号
                    if ($row['name']=='cust_si_id')
                    {
                        //不验证了

                        $cust_info['cust_si_id']=trim($row['value']);
                    }

                    //手机号码（年审号）
                    if ($row['name']=='cust_review_num')
                    {
                        if (!$this->check_something($row['value'],'phonenumber',null))
                        {
                            return ['error'=>'1','msg'=>'手机号码输入不正确'];
                        }

                        //验证一下数据库中是否有相同的项
                        if (count(CustModel::where(['cust_review_num'=>$row['value']])->get()->toArray())>='2')
                        {
                            return ['error'=>'1','msg'=>'此手机号（年审号）已经存在，不能添加了'];
                        }else
                        {
                            //添加A类的时候查找一下B类有没有这个电话，如果有，则添加失败
                            if (Input::get('cust_type')=='A')
                            {
                                $cust_type='B';
                            }else
                            {
                                $cust_type='A';
                            }
                            if (!empty(count(CustModel::where(['cust_review_num'=>$row['value'],'cust_type'=>$cust_type])->get()->toArray())))
                            {
                                return ['error'=>'1','msg'=>'此手机号（年审号）不属于当前客户类型，不能添加了'];
                            }

                            //检查一下是否已经添加过相同的第一年审人了
                            if (Input::get('cust_review_flag')=='1')
                            {
                                if (!empty(count(CustModel::where(['cust_review_num'=>$row['value'],'cust_review_flag'=>Input::get('cust_review_flag')])->get()->toArray())))
                                {
                                    return ['error'=>'1','msg'=>'此手机号（年审号）已经添加过第一年审人，不能添加了'];
                                }
                            }
                        }

                        $cust_info['cust_review_num']=$row['value'];
                    }

                    //备用手机号
                    if ($row['name']=='cust_phone_num')
                    {
                        //不验证了

                        $cust_info['cust_phone_num']=trim($row['value']);
                    }

                    //地址
                    if ($row['name']=='cust_address')
                    {
                        //不验证了

                        $cust_info['cust_address']=trim($row['value']);
                    }

                    //所属区域
                    if ($row['name']=='cust_project')
                    {
                        $res=ProjectModel::where(['project_name'=>$row['value']])->pluck('project_id')->toArray();

                        $cust_info['cust_project']=$res[0];
                    }

                    //确认方式
                    if ($row['name']=='cust_confirm_type')
                    {
                        $res=ConfirmTypeModel::where(['confirm_name'=>$row['value']])->pluck('confirm_id')->toArray();

                        $cust_info['cust_confirm_type']=$res[0];
                    }

                    //参保类型
                    if ($row['name']=='cust_si_type')
                    {
                        $res=SiTypeModel::where(['si_name'=>$row['value']])->pluck('si_id')->toArray();

                        $cust_info['cust_si_type']=$res[0];
                    }
                }

                //默认为A类用户
                $cust_info['cust_type']=Input::get('cust_type');

                //从这里添加的默认为第一年审人
                $cust_info['cust_review_flag']=Input::get('cust_review_flag');

                //从这里添加的默认为未注册
                $cust_info['cust_register_flag']=Input::get('cust_register_flag');

                //默认为用户未死亡
                $cust_info['cust_death_flag']='0';

                if (Input::get('cust_relation_flag')=='0')
                {
                    //从这里添加的默认为还没有添加第二年审人
                    $cust_info['cust_relation_flag']=Input::get('cust_relation_flag');
                    $need_update='0';
                }else
                {
                    //如果进来的数据是第二年审人的话，cust_relation_flag是第一年审人的id
                    //所以执行完了create后，还要update一下
                    $cust_info['cust_relation_flag']='0';
                    $need_update='1';
                }

                if ($need_update)
                {
                    $model=CustModel::create($cust_info);

                    //把第一年审人和第二年审人关联起来
                    $first=CustModel::find(Input::get('cust_relation_flag'));
                    $first->update(['cust_relation_flag'=>$model->cust_num]);

                }else
                {
                    CustModel::create($cust_info);
                }


                $this->system_log('添加新用户','姓名:'.$cust_info['cust_name'].'年审号:'.$cust_info['cust_review_num']);

                return ['error'=>'0','msg'=>'登记成功'];

                break;

            case 'refresh_A':

                //用户传入的页
                $now_page=Input::get('page');

                //每页显示几条数据
                $limit=5;

                //从第几条开始显示
                $offset=($now_page-1)*$limit;

                //查询当天的数据
                $time=date('Y-m-d');

                //查询的字段
                $get=
                    [
                        'cust_num',
                        'cust_project',
                        'cust_si_type',
                        'cust_name',
                        'cust_review_num',
                        'cust_register_flag',
                        'cust_relation_flag',
                        'cust_death_flag'
                    ];

                //得到这个用户可以看见的地区和参保类型
                foreach (Session::get('user') as $row)
                {
                    $proj=explode(',',$row['staff_project']);
                    $type=explode(',',$row['staff_si_type']);
                }

                //总页数
                $cnt_page=intval(ceil(CustModel::where('created_at','like',$time.'%')
                        ->where(['cust_review_flag'=>'1','cust_type'=>'A'])
                        ->whereIn('cust_project',$proj)->whereIn('cust_si_type',$type)->count()/$limit));

                //第二个where条件只要第一年审人的数据
                $model=CustModel::where('created_at','like',$time.'%')
                    ->where(['cust_review_flag'=>'1','cust_type'=>'A'])
                    ->whereIn('cust_project',$proj)->whereIn('cust_si_type',$type)
                    ->orderBy('cust_num','desc')->offset($offset)->limit($limit)->get($get)->toArray();

                //把查询到的数据中，数字转换成中文
                foreach ($model as &$row)
                {
                    $row['cust_project']=ProjectModel::where(['project_id'=>$row['cust_project']])->first()->project_name;
                    $row['cust_si_type']=SiTypeModel::where(['si_id'=>$row['cust_si_type']])->first()->si_name;

                    //查看第一年审人有没有被标记为去世
                    if ($row['cust_death_flag']=='1')
                    {
                        $row['cust_register_flag']='3';
                    }

                    unset($row['cust_death_flag']);

                    //添加上第二年审人信息，如果有的话
                    if ($row['cust_relation_flag']!='0')
                    {
                        $row['cust_relation_flag']=CustModel::where(['cust_num'=>$row['cust_relation_flag']])
                            ->get(['cust_num','cust_name','cust_register_flag','cust_death_flag'])
                            ->toArray();

                        //查看第二年审人有没有被标记为去世
                        foreach ($row['cust_relation_flag'] as &$val)
                        {
                            if ($val['cust_death_flag']=='1')
                            {
                                $val['cust_register_flag']='3';
                            }

                            unset($val['cust_death_flag']);
                        }
                    }
                }

                //dd($model);

                return ['error'=>'0','msg'=>'数据读取成功','pages'=>$cnt_page,'data'=>$model];

                break;

            case 'refresh_B':

                //用户传入的页
                $now_page=Input::get('page');

                //每页显示几条数据
                $limit=5;

                //从第几条开始显示
                $offset=($now_page-1)*$limit;

                //查询当天的数据
                $time=date('Y-m-d');

                //查询的字段
                $get=
                    [
                        'cust_num',
                        'cust_project',
                        'cust_si_type',
                        'cust_name',
                        'cust_review_num',
                        'cust_register_flag',
                        'cust_relation_flag',
                        'cust_death_flag'
                    ];

                //得到这个用户可以看见的地区和参保类型
                foreach (Session::get('user') as $row)
                {
                    $proj=explode(',',$row['staff_project']);
                    $type=explode(',',$row['staff_si_type']);
                }

                //总页数
                $cnt_page=intval(ceil(CustModel::where('created_at','like',$time.'%')
                        ->where(['cust_review_flag'=>'1','cust_type'=>'B'])
                        ->whereIn('cust_project',$proj)->whereIn('cust_si_type',$type)->count()/$limit));

                //第二个where条件只要第一年审人的数据
                $model=CustModel::where('created_at','like',$time.'%')
                    ->where(['cust_review_flag'=>'1','cust_type'=>'B'])
                    ->whereIn('cust_project',$proj)->whereIn('cust_si_type',$type)
                    ->orderBy('cust_num','desc')->offset($offset)->limit($limit)->get($get)->toArray();

                //把查询到的数据中，数字转换成中文
                foreach ($model as &$row)
                {
                    $row['cust_project']=ProjectModel::where(['project_id'=>$row['cust_project']])->first()->project_name;
                    $row['cust_si_type']=SiTypeModel::where(['si_id'=>$row['cust_si_type']])->first()->si_name;

                    //查看第一年审人有没有被标记为去世
                    if ($row['cust_death_flag']=='1')
                    {
                        $row['cust_register_flag']='3';
                    }

                    unset($row['cust_death_flag']);

                    //添加上第二年审人信息，如果有的话
                    if ($row['cust_relation_flag']!='0')
                    {
                        $row['cust_relation_flag']=CustModel::where(['cust_num'=>$row['cust_relation_flag']])
                            ->get(['cust_num','cust_name','cust_register_flag','cust_death_flag'])
                            ->toArray();

                        //查看第二年审人有没有被标记为去世
                        foreach ($row['cust_relation_flag'] as &$val)
                        {
                            if ($val['cust_death_flag']=='1')
                            {
                                $val['cust_register_flag']='3';
                            }

                            unset($val['cust_death_flag']);
                        }
                    }
                }

                return ['error'=>'0','msg'=>'数据读取成功','pages'=>$cnt_page,'data'=>$model];

                break;

            case 'select_data_A':

                //用户传入的页
                $now_page=Input::get('page');

                //每页显示几条数据
                $limit=5;

                //从第几条开始显示
                $offset=($now_page-1)*$limit;

                $select_info=[];

                foreach (Input::get('key') as $row)
                {
                    if ($row['name']=='cust_review_num')
                    {
                        if (trim($row['value'])!='')
                        {
                            $select_info['cust_review_num']=trim($row['value']);
                            $select_info['cust_type']='A';
                        }else
                        {
                            return ['error'=>'1','msg'=>'必须输入年审号码'];
                        }
                    }
                }

                //查询出的字段
                $get=
                    [
                        'cust_num',
                        'cust_project',
                        'cust_si_type',
                        'cust_name',
                        'cust_review_num',
                        'cust_register_flag',
                        'cust_relation_flag',
                        'cust_review_flag',
                        'cust_death_flag'
                    ];

                //判断数组中是否存在指定键，因为用户不可以查询范围外的数据
                foreach (Session::get('user') as $row)
                {
                    $proj=explode(',',$row['staff_project']);
                    $type=explode(',',$row['staff_si_type']);
                }
                $model=CustModel::where($select_info)->whereIn('cust_project',$proj)
                    ->whereIn('cust_si_type',$type)->orderBy('cust_num','desc')->offset($offset)->limit($limit)
                    ->get($get)->toArray();

                //总页数
                $cnt_page=intval(ceil(CustModel::where($select_info)->whereIn('cust_project',$proj)
                        ->whereIn('cust_si_type',$type)->count()/$limit));

                //把查询到的数据中，数字转换成中文
                foreach ($model as &$row)
                {
                    $row['cust_project']=ProjectModel::where(['project_id'=>$row['cust_project']])->first()->project_name;
                    $row['cust_si_type']=SiTypeModel::where(['si_id'=>$row['cust_si_type']])->first()->si_name;

                    //查看有没有被标记为去世
                    if ($row['cust_death_flag']=='1')
                    {
                        $row['cust_register_flag']='3';
                    }

                    unset($row['cust_death_flag']);

                    //如果是该行数据是第一年审人，则暂存起来
                    if ($row['cust_review_flag']=='1')
                    {
                        unset($row['cust_review_flag']);
                        $tmp_one[]=$row;
                    }else
                    {
                        $tmp_two[0]['cust_num']=$row['cust_num'];
                        $tmp_two[0]['cust_name']=$row['cust_name'];
                        $tmp_two[0]['cust_register_flag']=$row['cust_register_flag'];
                    }
                }

                //组合数组，给前台页面显示
                if (isset($tmp_two))
                {
                    $tmp_one[0]['cust_relation_flag']=$tmp_two;
                }

                $model=$tmp_one;

                //dd($model);

                return ['error'=>'0','msg'=>'查询成功','data'=>$model,'pages'=>$cnt_page];

                break;

            case 'select_data_B':

                //用户传入的页
                $now_page=Input::get('page');

                //每页显示几条数据
                $limit=5;

                //从第几条开始显示
                $offset=($now_page-1)*$limit;

                $select_info=[];

                foreach (Input::get('key') as $row)
                {
                    if ($row['name']=='cust_review_num')
                    {
                        if (trim($row['value'])!='')
                        {
                            $select_info['cust_review_num']=trim($row['value']);
                            $select_info['cust_type']='B';
                        }else
                        {
                            return ['error'=>'1','msg'=>'必须输入年审号码'];
                        }
                    }
                }

                //查询出的字段
                $get=
                    [
                        'cust_num',
                        'cust_project',
                        'cust_si_type',
                        'cust_name',
                        'cust_review_num',
                        'cust_register_flag',
                        'cust_relation_flag',
                        'cust_review_flag',
                        'cust_death_flag'
                    ];

                //判断数组中是否存在指定键，因为用户不可以查询范围外的数据
                foreach (Session::get('user') as $row)
                {
                    $proj=explode(',',$row['staff_project']);
                    $type=explode(',',$row['staff_si_type']);
                }
                $model=CustModel::where($select_info)->whereIn('cust_project',$proj)
                    ->whereIn('cust_si_type',$type)->orderBy('cust_num','desc')->offset($offset)->limit($limit)
                    ->get($get)->toArray();

                //总页数
                $cnt_page=intval(ceil(CustModel::where($select_info)->whereIn('cust_project',$proj)
                        ->whereIn('cust_si_type',$type)->count()/$limit));

                //把查询到的数据中，数字转换成中文
                foreach ($model as &$row)
                {
                    $row['cust_project']=ProjectModel::where(['project_id'=>$row['cust_project']])->first()->project_name;
                    $row['cust_si_type']=SiTypeModel::where(['si_id'=>$row['cust_si_type']])->first()->si_name;

                    //查看有没有被标记为去世
                    if ($row['cust_death_flag']=='1')
                    {
                        $row['cust_register_flag']='3';
                    }

                    unset($row['cust_death_flag']);

                    //如果是该行数据是第一年审人，则暂存起来
                    if ($row['cust_review_flag']=='1')
                    {
                        unset($row['cust_review_flag']);
                        $tmp_one[]=$row;
                    }else
                    {
                        $tmp_two[0]['cust_num']=$row['cust_num'];
                        $tmp_two[0]['cust_name']=$row['cust_name'];
                        $tmp_two[0]['cust_register_flag']=$row['cust_register_flag'];
                    }
                }

                //组合数组，给前台页面显示
                if (isset($tmp_two))
                {
                    $tmp_one[0]['cust_relation_flag']=$tmp_two;
                }

                $model=$tmp_one;

                return ['error'=>'0','msg'=>'查询成功','data'=>$model,'pages'=>$cnt_page];

                break;

            case 'statistics_change':

                //用户传入的页
                $now_page=Input::get('page');

                //每页显示几条数据
                $limit=12;

                //从第几条开始显示
                $offset=($now_page-1)*$limit;

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
                            $start=$row['value'].' 00:00:00';
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
                            $stop=$row['value'].' 23:59:59';
                        }
                    }

                    //判断客户类型
                    if ($row['name']=='cust_type')
                    {
                        if ($row['value']=='0')
                        {
                            //说明是要查询A类和B类用户
                        }elseif ($row['value']=='A')
                        {
                            $select_info['cust_type']='A';
                        }else
                        {
                            $select_info['cust_type']='B';
                        }
                    }

                    //取得所属地区
                    if ($row['name']=='cust_project')
                    {
                        $select_info['cust_project']=$row['value'];
                    }

                    //取得参保类型
                    if ($row['name']=='cust_si_type')
                    {
                        $select_info['cust_si_type']=$row['value'];
                    }

                    //取得注册信息
                    if ($row['name']=='cust_register_flag')
                    {
                        $select_info['cust_register_flag']=$row['value'];
                    }
                }

                $get=[
                    'cust_project',
                    'cust_si_type',
                    'cust_name',
                    'cust_id',
                    'cust_si_id',
                    'cust_review_num',
                    'cust_phone_num'
                ];

                $data=CustModel::where($select_info)->wherebetween('created_at',[$start,$stop])
                    ->offset($offset)->limit($limit)->get($get)->toArray();

                //总页数
                $cnt=CustModel::where($select_info)->wherebetween('created_at',[$start,$stop])->count();
                $cnt_page=intval(ceil($cnt/$limit));

                //查出来的数据改成中文
                foreach ($data as &$row)
                {
                    $row['cust_project']=ProjectModel::find($row['cust_project'])->project_name;
                    $row['cust_si_type']=SiTypeModel::find($row['cust_si_type'])->si_name;
                }

                return ['error'=>'0','msg'=>'查询成功','data'=>$data,'pages'=>$cnt_page,'count_data'=>$cnt];

                break;

            case 'analysis_change':

                foreach (Input::get('key') as $row)
                {
                    if ($row['name']=='project_name')
                    {
                        $proj=$row['value'];
                    }

                    //判断一下是否已经选择了日期
                    if ($row['name']=='year_and_month')
                    {
                        if ($row['value']=='')
                        {
                            return ['error'=>'1','msg'=>'必须要选择一个日期'];
                        }else
                        {
                            //得到年-月
                            $yearAndmonth=substr($row['value'],0,strlen($row['value'])-3);

                            //得到当前年的当前月有多少天
                            $unixTime=strtotime($row['value']);
                            $day=date('t',$unixTime);

                            //从数据库中查询符合条件的数据
                            $data=CustModel::where(['cust_project'=>$proj])->where('created_at','like',$yearAndmonth.'%')
                                ->orderBy('created_at','asc')
                                ->groupBy('cust_review_num')
                                ->get(['created_at','cust_review_num'])
                                ->toArray();

                            //只要数组中的创建时间
                            foreach ($data as $row)
                            {
                                $tmp[]=$row['created_at'];
                            }

                            $data=isset($tmp) ? $tmp : null;

                            if (empty($data))
                            {
                                return ['error'=>'1','msg'=>'没有匹配到数据'];
                            }else
                            {
                                //上面已经得到当前月的所有数据了
                                foreach ($data as $row)
                                {
                                    //只保留年月日
                                    $time[]=substr($row,0,10);
                                }

                                //制造返回给前端页面的数组
                                foreach (array_count_values($time) as $k=>$v)
                                {
                                    $morris_data[]=['y'=>$k,'mytarget'=>$v];
                                }

                                //得到当前日期的前缀
                                $prefix=date('Y-m-',$unixTime);

                                //补齐丢失的日期
                                for ($i=1;$i<=$day;$i++)
                                {
                                    if (strlen($i)=='1')
                                    {
                                        if (!array_key_exists($prefix.'0'.$i,array_count_values($time)))
                                        {
                                            $morris_data[]=['y'=>$prefix.'0'.$i,'mytarget'=>'0'];
                                        }
                                    }else
                                    {
                                        if (!array_key_exists($prefix.$i,array_count_values($time)))
                                        {
                                            $morris_data[]=['y'=>$prefix.$i,'mytarget'=>'0'];
                                        }
                                    }
                                }

                                return ['error'=>'0','msg'=>'成功','data'=>$morris_data,'data_total'=>array_sum(array_count_values($time))];
                            }
                        }
                    }
                }

                break;

            case 'send_mail_get_proj':

                $data=ProjectModel::pluck('project_name','project_id')->toArray();

                return ['error'=>'0','msg'=>'加载完毕','data'=>$data];

                break;

            case 'send_mail_get_si_type':

                $data=SiTypeModel::pluck('si_name','si_id')->toArray();

                return ['error'=>'0','msg'=>'加载完毕','data'=>$data];

                break;

            case 'send_mail':

                foreach ($data=Input::get('key') as $row)
                {
                    if ($row['name']=='optionsRadios')
                    {
                        //给全体员工发信息
                        $mail_info['mail_type']=$row['value'];
                    }

                    if ($row['name']=='proj' || $row['name']=='si_type' || $row['name']=='staff')
                    {
                        if (trim($row['value']==''))
                        {
                            return ['error'=>'1','msg'=>'请输入员工账号'];
                        }
                        //信息的目标
                        $mail_info['mail_target']=$row['value'];
                    }

                    if ($row['name']=='allstaff')
                    {
                        //信息的目标
                        $mail_info['mail_target']='allstaff';
                    }

                    if ($row['name']=='mail_content')
                    {
                        if (trim($row['value'])=='')
                        {
                            return ['error'=>'1','msg'=>'邮件内容不能是空'];
                        }
                        //信息的内容
                        $mail_info['mail_content']=trim($row['value']);
                    }
                }

                //发送邮件
                SendMailModel::create($mail_info);

                $this->system_log('发送邮件','超级管理员发送了一封站内邮件');

                return ['error'=>'0','msg'=>'发送完毕'];

                break;

            case 'get_mail':

                $get=[
                    'mail_id',
                    'mail_type',
                    'mail_target',
                    'mail_content',
                    'created_at'
                ];

                $user=Session::get('user');

                //取得全体员工的邮件
                $AllMail=SendMailModel::where(['mail_type'=>'1'])->get($get)->toArray();

                //取得属地员工的邮件
                $ProjMail=SendMailModel::where(['mail_type'=>'2'])
                    ->whereIn('mail_target',explode(",",$user[0]['staff_project']))
                    ->get($get)->toArray();

                //取得参保类型员工的邮件
                $SiTypeMail=SendMailModel::where(['mail_type'=>'3'])
                    ->whereIn('mail_target',explode(",",$user[0]['staff_si_type']))
                    ->get($get)->toArray();

                //取得指定员工账号的邮件
                $StaffMail=SendMailModel::where(['mail_type'=>'4','mail_target'=>$user[0]['staff_account']])
                    ->get($get)->toArray();

                return ['error'=>'0','msg'=>'刷新邮件完毕',
                    'AllMail'=>$AllMail,'ProjMail'=>$ProjMail,'SiTypeMail'=>$SiTypeMail,'StaffMail'=>$StaffMail];

                break;

            case 'service_care_change':

                //用户传入的页
                $now_page=Input::get('page');

                //每页显示几条数据
                $limit=12;

                //从第几条开始显示
                $offset=($now_page-1)*$limit;

                //查询条件
                $condition=null;

                if (Input::get('tip')=='0')
                {
                    //遍历出所有的查询条件
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
                                $start=$row['value'].' 00:00:00';
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
                                $stop=$row['value'].' 23:59:59';
                            }
                        }

                        if ($row['name']=='cust_project')
                        {
                            $condition['cust_project']=$row['value'];
                        }

                        if ($row['name']=='cust_si_type')
                        {
                            $condition['cust_si_type']=$row['value'];
                        }

                        if ($row['name']=='confirm_res')
                        {
                            if ($row['value']=='0')
                            {
                                $YorN=['Y','N'];
                            }else
                            {
                                $YorN=[$row['value']];
                            }
                        }

                        if ($row['name']=='cust_type')
                        {
                            if ($row['value']=='0')
                            {
                                $AorB=['A','B'];
                            }else
                            {
                                $AorB=[$row['value']];
                            }
                        }
                    }

                    //查询想要的数据
                    $get=[
                        'customer_info.cust_name',
                        'customer_info.cust_id',
                        'customer_info.cust_review_num',
                        'customer_info.cust_phone_num',
                        'customer_info.cust_type',
                        'customer_confirm.created_at',
                        'customer_confirm.confirm_res',
                        'customer_confirm.confirm_num',
                        'customer_confirm.confirm_btw'
                    ];

                    //查询数据
                    if (count($YorN)=='1' && $YorN[0]=='N')
                    {
                        //仅仅是查询未通过的
                        $sql="select confirm_num,confirm_pid,confirm_res,confirm_btw,created_at,COUNT(confirm_pid) as num 
FROM (select * from zbxl_customer_confirm as t1 where created_at between ? and ? GROUP BY confirm_pid,confirm_res) as t2 
GROUP BY confirm_pid HAVING (num<? AND confirm_res=?) limit ? offset ?";

                        $res1=\DB::select($sql,[$start,$stop,'2','N',$limit,$offset]);

                        //对象转数组
                        $res2=$this->obj2arr($res1);

                        //给每个数组里插入客户相关信息
                        foreach ($res2 as &$row)
                        {
                            $data=CustModel::find($row['confirm_pid']);
                            $row['cust_name']=$data->cust_name;
                            $row['cust_id']=$data->cust_id;
                            $row['cust_review_num']=$data->cust_review_num;
                            $row['cust_phone_num']=$data->cust_phone_num;
                            $row['cust_type']=$data->cust_type;
                        }
                        unset($row);

                        //为了符合前台页面显示，数组里的数据顺序需要改一下
                        $data1=null;
                        foreach ($res2 as $row)
                        {
                            $data2=null;

                            $data2['cust_name']=$row['cust_name'];
                            $data2['cust_id']=$row['cust_id'];
                            $data2['cust_review_num']=$row['cust_review_num'];
                            $data2['cust_phone_num']=$row['cust_phone_num'];
                            $data2['cust_type']=$row['cust_type'];
                            $data2['created_at']=$row['created_at'];
                            $data2['confirm_res']=$row['confirm_res'];
                            $data2['confirm_num']=$row['confirm_num'];
                            $data2['confirm_btw']=$row['confirm_btw'];

                            $data1[]=$data2;
                        }

                        //总页数
                        $sql="select confirm_num,confirm_pid,confirm_res,created_at,COUNT(confirm_pid) as num 
FROM (select * from zbxl_customer_confirm as t1 where created_at between ? and ? GROUP BY confirm_pid,confirm_res) as t2 
GROUP BY confirm_pid HAVING (num<? AND confirm_res=?)";

                        $res3=count(\DB::select($sql,[$start,$stop,'2','N']));
                        $cnt_page=intval(ceil($res3/$limit));

                        return ['error'=>'0','msg'=>'查询成功','data'=>$data1,'pages'=>$cnt_page,'count_data'=>$res3];
                    }else
                    {
                        //查询数据
                        $res=\DB::table('customer_info')
                            ->leftJoin('customer_confirm','customer_info.cust_num','=','customer_confirm.confirm_pid')
                            ->where($condition)
                            ->whereIn('customer_confirm.confirm_res',$YorN)
                            ->whereIn('customer_info.cust_type',$AorB)
                            ->orderBy('customer_confirm.confirm_pid','desc')
                            ->orderBy('customer_confirm.created_at','desc')
                            ->wherebetween('customer_confirm.created_at',[$start,$stop])
                            ->offset($offset)->limit($limit)
                            ->get($get);

                        //查询总页数
                        $cnt=\DB::table('customer_info')
                            ->leftJoin('customer_confirm','customer_info.cust_num','=','customer_confirm.confirm_pid')
                            ->where($condition)
                            ->whereIn('customer_confirm.confirm_res',$YorN)
                            ->whereIn('customer_info.cust_type',$AorB)
                            ->wherebetween('customer_confirm.created_at',[$start,$stop])
                            ->count();
                        $cnt_page=intval(ceil($cnt/$limit));

                        return ['error'=>'0','msg'=>'查询成功','data'=>$res,'pages'=>$cnt_page,'count_data'=>$cnt];
                    }

                }else
                {
                    $cond=null;

                    foreach (Input::get('key') as $row)
                    {
                        if ($row['name']=='cond')
                        {
                            $cond=trim($row['value']);
                        }
                    }

                    //查询想要的数据
                    $get=[
                        'customer_info.cust_name',
                        'customer_info.cust_id',
                        'customer_info.cust_review_num',
                        'customer_info.cust_phone_num',
                        'customer_info.cust_type',
                        'customer_confirm.created_at',
                        'customer_confirm.confirm_res',
                        'customer_confirm.confirm_num',
                        'customer_confirm.confirm_btw'
                    ];

                    //判断用户输入的是手机号还是身份证号
                    if ($this->is_idcard($cond))
                    {
                        //输入的是身份证
                        //查询数据
                        $res=\DB::table('customer_info')
                            ->leftJoin('customer_confirm','customer_info.cust_num','=','customer_confirm.confirm_pid')
                            ->where(['cust_id'=>$cond])
                            ->where('customer_confirm.created_at','like',date('Y',time()).'%')
                            ->orderBy('customer_confirm.created_at','desc')
                            ->offset($offset)->limit($limit)
                            ->get($get);

                        if (count($res)=='0')
                        {
                            return ['error'=>'1','msg'=>'此身份证不存在'];
                        }

                        //查询总页数
                        $cnt=\DB::table('customer_info')
                            ->leftJoin('customer_confirm','customer_info.cust_num','=','customer_confirm.confirm_pid')
                            ->where(['cust_id'=>$cond])
                            ->where('customer_confirm.created_at','like',date('Y',time()).'%')
                            ->count();
                        $cnt_page=intval(ceil($cnt/$limit));

                        return ['error'=>'0','msg'=>'查询成功','data'=>$res,'pages'=>$cnt_page,'count_data'=>$cnt];

                    }elseif ($this->check_something($cond,'phonenumber',null))
                    {
                        //输入的是手机号
                        //查询数据
                        $res=\DB::table('customer_info')
                            ->leftJoin('customer_confirm','customer_info.cust_num','=','customer_confirm.confirm_pid')
                            ->where(['cust_review_num'=>$cond])
                            ->where('customer_confirm.created_at','like',date('Y',time()).'%')
                            ->orderBy('customer_confirm.created_at','desc')
                            ->offset($offset)->limit($limit)
                            ->get($get);

                        if (count($res)=='0')
                        {
                            return ['error'=>'1','msg'=>'此手机号不存在'];
                        }

                        //查询总页数
                        $cnt=\DB::table('customer_info')
                            ->leftJoin('customer_confirm','customer_info.cust_num','=','customer_confirm.confirm_pid')
                            ->where(['cust_review_num'=>$cond])
                            ->where('customer_confirm.created_at','like',date('Y',time()).'%')
                            ->count();
                        $cnt_page=intval(ceil($cnt/$limit));

                        return ['error'=>'0','msg'=>'查询成功','data'=>$res,'pages'=>$cnt_page,'count_data'=>$cnt];
                    }else
                    {
                        //既不是身份证也不是手机号
                        return ['error'=>'1','msg'=>'既不是身份证也不是手机号'];
                    }

                }

                break;

            case 'modify_btw':

                $pid=null;
                $cond=null;
                $YorN=null;

                foreach (Input::get('key') as $row)
                {
                    if ($row['name']=='btw')
                    {
                        $cond=trim($row['value']);
                    }

                    if ($row['name']=='btw_id')
                    {
                        $pid=$row['value'];
                    }

                    if ($row['name']=='mycheck')
                    {
                        $YorN='Y';
                    }
                }

                if (!$pid==null)
                {
                    $data=CustConfirmModel::find($pid);

                    $data->confirm_btw=$cond;

                    //判断是否需要改成通过认证
                    if ($YorN=='Y')
                    {
                        $data->confirm_res='Y';
                        $data->save();

                        $this->system_log('修改认证表的认证结果','把主键是'.$pid.'的认证结果改成了<Y>');
                    }else
                    {
                        $data->save();
                    }

                    $this->system_log('修改认证表备注','把主键是'.$pid.'的备注改成了<'.$cond.'>');

                    return ['error'=>'0','msg'=>'修改成功'];

                }else
                {
                    return ['error'=>'1','msg'=>'查询数据失败'];
                }

                break;

            case 'modify_info':

                $cond=trim(Input::get('cond1'));
                $cust_review_flag=Input::get('cond2');

                if ($cond=='')
                {
                    //是空
                    return ['error'=>'1','msg'=>'查询条件不能是空'];
                }elseif ($this->check_something($cond,'phonenumber',null))
                {
                    //是手机号
                    $phone=$cond;
                }elseif ($this->is_idcard($cond))
                {
                    //是身份证号
                    $id=$cond;
                }else
                {
                    //非空，但什么也不是
                    return ['error'=>'1','msg'=>'既不是年审号也不是身份证号'];
                }

                //判断到底拿到了哪个值
                if (isset($phone))
                {
                    $where=['cust_review_num'=>$phone,'cust_review_flag'=>$cust_review_flag];
                }else
                {
                    $where=['cust_id'=>$id,'cust_review_flag'=>$cust_review_flag];
                }

                //开始查询
                $res=CustModel::where($where)->get()->toArray();

                if (empty($res))
                {
                    return ['error'=>'1','msg'=>'查无结果'];
                }else
                {
                    $nbsp='&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp';
                    $data['客户姓名']='<a id=modify_cust_name>'.$res[0]['cust_name'].'</a>';
                    $data['身份证号']='<a id=modify_cust_id>'.$res[0]['cust_id'].'</a>';
                    $data['社保编号']='<a id=modify_cust_si_id>'.$res[0]['cust_si_id'].'</a>';
                    $data['年审号码']='<a id=modify_cust_review_num>'.$res[0]['cust_review_num'].'</a>';
                    $data['备用号码']='<a id=modify_cust_phone_num>'.$res[0]['cust_phone_num'].'</a>';
                    $data['客户地址']='<a id=modify_cust_address>'.$res[0]['cust_address'].'</a>';
                    $data['所属区域']='<a id=modify_cust_project>'.ProjectModel::find($res[0]['cust_project'])->project_name.'</a>';
                    $data['参保类型']='<a id=modify_cust_si_type>'.SiTypeModel::find($res[0]['cust_si_type'])->si_name.'</a>';
                    $data['认证类型']='<a id=modify_cust_confirm_type>'.ConfirmTypeModel::find($res[0]['cust_confirm_type'])->confirm_name.'</a>';
                    $data['客户类别']='<a id=modify_cust_type>'.$res[0]['cust_type'].'类客户'.'</a>';
                    $data['创建时间']='<a id=modify_created_at>'.$res[0]['created_at'].'</a>';
                    $data['年审人号']='<a id=modify_cust_review_flag>'.'第'.$res[0]['cust_review_flag'].'年审人'.'</a>';
                    $data['唯一主键']='<a id=modify_pid value='.$res[0]['cust_num'].'>'.$res[0]['cust_num'].'</a>';
                    if ($res[0]['cust_death_flag']=='1')
                    {
                        $data['更多操作']='<a class="btn btn-danger" id=cust_delete_btn>删除该客户</a>'.$nbsp.'<a class="btn btn-info" id=cust_restore_btn>恢复认证状态</a>';
                    }else
                    {
                        $data['更多操作']='<a class="btn btn-danger" id=cust_delete_btn>删除该客户</a>'.$nbsp.'<a class="btn btn-warning" id=cust_death_btn>设成去世状态</a>';
                    }

                    return ['error'=>'0','msg'=>'查询成功','data'=>$data];
                }

                break;

            case 'modify_cust_name':

                $name=Input::get('key');
                $pid=Input::get('pid');

                $res=CustModel::find($pid);
                $this->system_log('修改客户姓名','主键:'.$pid.'修改内容:'.$res->cust_name.'=>'.$name);
                $res->cust_name=$name;
                $res->save();

                return ['error'=>'0','msg'=>'修改成功'];

                break;

            case 'modify_cust_id':

                $id=Input::get('key');
                $pid=Input::get('pid');

                if (!$this->is_idcard($id))
                {
                    return ['error'=>'1','msg'=>'身份证输入不正确'];
                }

                $res=CustModel::where(['cust_id'=>$id])->get()->toArray();

                if (!empty($res))
                {
                    return ['error'=>'1','msg'=>'身份证已存在，修改失败'];
                }

                $res=CustModel::find($pid);
                $this->system_log('修改身份证','主键:'.$pid.'修改内容:'.$res->cust_id.'=>'.$id);
                $res->cust_id=$id;
                $res->save();

                return ['error'=>'0','msg'=>'修改成功'];

                break;

            case 'modify_cust_si_id':

                $id=Input::get('key');
                $pid=Input::get('pid');

                if ($id=='')
                {
                    $res=CustModel::find($pid);
                    $this->system_log('修改社保编号','主键:'.$pid.'修改内容:'.$res->cust_si_id.'=>'.$id);
                    $res->cust_si_id=$id;
                    $res->save();

                    return ['error'=>'0','msg'=>'修改成功'];
                }else
                {
                    $res=CustModel::where(['cust_si_id'=>$id])->get()->toArray();

                    if (!empty($res))
                    {
                        return ['error'=>'1','msg'=>'社保编号已存在，修改失败'];
                    }

                    $res=CustModel::find($pid);
                    $this->system_log('修改社保编号','主键:'.$pid.'修改内容:'.$res->cust_si_id.'=>'.$id);
                    $res->cust_si_id=$id;
                    $res->save();

                    return ['error'=>'0','msg'=>'修改成功'];
                }

                break;

            case 'modify_cust_review_num':

                $phone=Input::get('key');
                $pid=Input::get('pid');

                if (!$this->check_something($phone,'phonenumber',null))
                {
                    return ['error'=>'1','msg'=>'手机号码输入不正确'];
                }

                $res=CustModel::where(['cust_review_num'=>$phone])->get()->toArray();

                if (!empty($res))
                {
                    return ['error'=>'1','msg'=>'手机号码已存在，修改失败'];
                }else
                {
                    $res=CustModel::find($pid);
                    $this->system_log('修改年审号码','主键:'.$pid.'修改内容:'.$res->cust_review_num.'=>'.$phone);
                    $res->cust_review_num=$phone;
                    $res->save();

                    $this->voice_file_ModifyOrDelete($pid,'modify',['phone'=>$phone]);

                    return ['error'=>'0','msg'=>'修改成功'];
                }

                break;

            case 'modify_cust_phone_num':

                $phone=Input::get('key');
                $pid=Input::get('pid');

                $res=CustModel::find($pid);
                $this->system_log('修改备用号码','主键:'.$pid.'修改内容:'.$res->cust_phone_num.'=>'.$phone);
                $res->cust_phone_num=$phone;
                $res->save();

                return ['error'=>'0','msg'=>'修改成功'];

                break;

            case 'modify_cust_address':

                $phone=Input::get('key');
                $pid=Input::get('pid');

                $res=CustModel::find($pid);
                $this->system_log('修改客户地址','主键:'.$pid.'修改内容:'.$res->cust_address.'=>'.$phone);
                $res->cust_address=$phone;
                $res->save();

                return ['error'=>'0','msg'=>'修改成功'];

                break;

            case 'modify_get_data':

                $proj=ProjectModel::get(['project_id','project_name'])->toArray();
                $si=SiTypeModel::get(['si_id','si_name'])->toArray();
                $confirm=ConfirmTypeModel::get(['confirm_id','confirm_name'])->toArray();

                $tmp=$this->change_arr_key($proj,['project_id'=>'value']);
                $proj=$this->change_arr_key($tmp,['project_name'=>'text']);

                $tmp=$this->change_arr_key($si,['si_id'=>'value']);
                $si=$this->change_arr_key($tmp,['si_name'=>'text']);

                $tmp=$this->change_arr_key($confirm,['confirm_id'=>'value']);
                $confirm=$this->change_arr_key($tmp,['confirm_name'=>'text']);

                return ['proj'=>$proj,'si'=>$si,'confirm'=>$confirm];

                break;

            case 'modify_cust_project':

                $proj=Input::get('key');
                $pid=Input::get('pid');

                $res=CustModel::find($pid);
                $this->system_log('修改客户属地','主键:'.$pid.'修改内容:'.$res->cust_project.'=>'.$proj);
                $res->cust_project=$proj;
                $res->save();

                return ['error'=>'0','msg'=>'修改成功'];

                break;

            case 'modify_cust_si_type':

                $si=Input::get('key');
                $pid=Input::get('pid');

                $res=CustModel::find($pid);
                $this->system_log('修改参保类型','主键:'.$pid.'修改内容:'.$res->cust_si_type.'=>'.$si);
                $res->cust_si_type=$si;
                $res->save();

                return ['error'=>'0','msg'=>'修改成功'];

                break;

            case 'modify_cust_confirm_type':

                $confirm=Input::get('key');
                $pid=Input::get('pid');

                $res=CustModel::find($pid);
                $this->system_log('修改认证类型','主键:'.$pid.'修改内容:'.$res->cust_confirm_type.'=>'.$confirm);
                $res->cust_confirm_type=$confirm;
                $res->save();

                return ['error'=>'0','msg'=>'修改成功'];

                break;

            case 'modify_cust_delete':

                $pid=Input::get('pid');

                $res=CustModel::find($pid);

                //第一年审人不能直接删除，必须先删除第二年审人
                if ($res->cust_relation_flag!='0')
                {
                    try
                    {
                        //查询是否存在第二年审人
                        CustModel::findOrFail($res->cust_relation_flag);

                        return ['error'=>'1','msg'=>'不可以直接删除第一年审人'];
                    }catch (ModelNotFoundException $exception)
                    {

                    }
                }else
                {
                    //如果等于0，说明是删除的第二年审人，或者，还没有添加第二年审人的第一年审人
                    //可以直接删除
                    //需要把第一年审人的cust_relation_flag改为0
                    $a=CustModel::where(['cust_relation_flag'=>$pid])->first();

                    if ($a!=null)
                    {
                        $a->cust_relation_flag='0';
                        $a->save();
                    }
                }

                $this->voice_file_ModifyOrDelete($pid,'delete');

                $this->system_log('删除客户信息','主键:'.$pid);

                CustDeleteModel::create($res->toArray());

                $res->delete();

                VocalPrintModel::where(['vp_id'=>$pid])->delete();

                return ['error'=>'0','msg'=>'删除成功'];

                break;

            case 'modify_cust_death':

                $pid=Input::get('pid');

                $res=CustModel::find($pid);

                $res->cust_death_flag='1';
                $res->save();

                $this->system_log('设置客户为去世状态','主键:'.$pid);

                return ['error'=>'0','msg'=>'设置成功'];

                break;

            case 'modify_cust_restore':

                $pid=Input::get('pid');

                $res=CustModel::find($pid);

                $res->cust_death_flag='0';
                $res->save();

                $this->system_log('设置客户为认证状态','主键:'.$pid);

                return ['error'=>'0','msg'=>'设置成功'];

                break;

            case 'edit_staff':

                //遍历参数
                foreach (Input::get('key') as $row)
                {
                    if ($row['name']=='staff_account')
                    {
                        $staff_info['staff_account']=$row['value'];
                    }
                    if ($row['name']=='staff_password')
                    {
                        if (!trim($row['value'])=='')
                        {
                            $staff_info['staff_password']=substr(md5(trim($row['value'])),0,24);
                        }
                    }
                    if ($row['name']=='staff_project')
                    {
                        $staff_project=$this->arr2str(json_decode($row['value'],true));
                        if (empty($staff_project))
                        {
                            return ['error'=>'1','msg'=>'选择所属区域'];
                        }else
                        {
                            $staff_info['staff_project']=$staff_project;
                        }
                    }
                    if ($row['name']=='staff_si_type')
                    {
                        $staff_si_type=$this->arr2str(json_decode($row['value'],true));
                        if (empty($staff_si_type))
                        {
                            return ['error'=>'1','msg'=>'选择参保类型'];
                        }else
                        {
                            $staff_info['staff_si_type']=$staff_si_type;
                        }
                    }
                    if ($row['name']=='staff_level')
                    {
                        $staff_level=$this->arr2str(json_decode($row['value'],true));
                        if (empty($staff_level))
                        {
                            return ['error'=>'1','msg'=>'选择员工权限'];
                        }else
                        {
                            $staff_info['staff_level']=$staff_level;
                        }
                    }
                }

                //确认用户是否存在
                $res=StaffModel::where(['staff_account'=>$staff_info['staff_account']])->get();

                if (empty($res->toArray()))
                {
                    return ['error'=>'1','msg'=>'没有找到该员工账号'];
                }else
                {
                    StaffModel::where(['staff_account'=>$staff_info['staff_account']])
                        ->update($staff_info);

                    $this->system_log('修改员工信息','员工账号是:'.$staff_info['staff_account']);

                    return ['error'=>'0','msg'=>'修改完成'];
                }

                break;

            case 'delete_cust_voice':

                $res=CustModel::find(Input::get('key'));

                $res->cust_register_flag='0';
                $res->save();

                return ['error'=>'0','msg'=>'可以重新注册了'];

                break;

            case 'get_config':

                return ['error'=>'0','DynamicPassword'=>Config::get('confirm_type.repeat'),
                    'TextDependent'=>Config::get('confirm_type.text')];

                break;

            case 'set_config':

                foreach (Input::get('key') as $row)
                {
                    if ($row['name']=='DynamicPassword')
                    {
                        Config::set('confirm_type.repeat',$row['value']);
                        dd(Config::get('confirm_type.repeat'));
                    }

                }


                return ['error'=>'0'];

                break;


        }
    }

}
