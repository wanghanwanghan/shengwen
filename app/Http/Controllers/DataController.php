<?php

namespace App\Http\Controllers;

use App\Http\Model\ChinaAllPositionModel;
use App\Http\Model\ConfirmTypeModel;
use App\Http\Model\CustConfirmModel;
use App\Http\Model\CustDeleteModel;
use App\Http\Model\CustFVModel;
use App\Http\Model\CustModel;
use App\Http\Model\LevelModel;
use App\Http\Model\LogModel;
use App\Http\Model\ProjectModel;
use App\Http\Model\SendMailModel;
use App\Http\Model\SiTypeModel;
use App\Http\Model\SocialInsuranceModel;
use App\Http\Model\StaffLoginPlaceModel;
use App\Http\Model\StaffModel;
use App\Http\Model\VocalPrintModel;
use App\Http\Myclass\FingerRegister;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class DataController extends Controller
{
    public function ajax(Request $request)
    {
        switch (Input::get('type'))
        {
            case 'get_si_type':

                $model=SiTypeModel::get(['si_id','si_name'])->toArray();

                $model=$this->change_arr_key($model,['si_id'=>'id','si_name'=>'name']);

                array_unshift($model,['id'=>'0','name'=>'设置为最顶级地区']);

                return ['error'=>'0','msg'=>'成功','data'=>$model];

                break;

            case 'get_my_si_type':

            //添加新的参保类型页面用的

            $model=SiTypeModel::get(['si_name'])->toArray();

            $model=array_flatten($model);

            return ['error'=>'0','msg'=>'成功','data'=>$model];

            break;

            case 'get_my_confirm_type':

                //添加新的认证类型页面用的

                $model=ConfirmTypeModel::get(['confirm_name'])->toArray();

                $model=array_flatten($model);

                return ['error'=>'0','msg'=>'成功','data'=>$model];

                break;

            case 'get_project':

                //从session中找到登陆用户的主键，查询地区id
                $login_user=Session::get('user');
                $login_user_id=$login_user[0]['staff_num'];
                //如果是超级管理员
                if ($login_user_id=='1')
                {
                    $model=ProjectModel::get(['project_id','project_name','project_parent'])->toArray();

                    $model=$this->infinite($model,'project');

                    $model=$this->change_arr_key($model,['project_id'=>'id','project_name'=>'name']);

                    array_unshift($model,['id'=>'0','name'=>'设置为最顶级地区']);

                    return ['error'=>'0','msg'=>'成功','data'=>$model];
                }
                $login_user_project_id_string=StaffModel::find($login_user_id)->staff_project;
                $id_array=explode(',',$login_user_project_id_string);

                $projArray=[];
                for ($i=0;$i<count($id_array);$i++)
                {
                    //这里面是登陆用户的所有属地节点id
                    //找到每个节点的父和子节点，放到一个数组中，最后整理该数组给前台显示
                    //如果有父子节点重复，就不添加到数组了

                    //该节点的一级一级往上的父节点数组
                    //查询出所有父节点的project_id，project_name，project_parent
                    $father_id_array=$this->select_allproject_parent($id_array[$i]);
                    foreach ($father_id_array as $key=>$value)
                    {
                        $projArray_tmp=ProjectModel::where('project_id',$key)
                            ->get(['project_id','project_name','project_parent'])
                            ->toArray();

                        //如果这个节点已经添加过，就不添加了
                        if (!in_array($projArray_tmp[0],$projArray))
                        {
                            $projArray[]=$projArray_tmp[0];
                        }
                    }

                    //下面查找所有子节点，同样的加入$projArray数组
                    $son_id_array=$this->get_all_children($id_array[$i]);
                    //有可能是空，因为最后一个节点没有子节点
                    if (!empty($son_id_array))
                    {
                        //把每个数组添加到$projArray
                        foreach ($son_id_array as &$row)
                        {
                            unset($row['level']);
                            if (!in_array($row,$projArray))
                            {
                                $projArray[]=$row;
                            }
                        }
                    }
                }

                $model=$this->infinite($projArray,'project');

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
                            $project_id=ProjectModel::create(['project_name'=>$proj,'project_parent'=>$row['value']]);

                            //把path加上
                            if ($row['value']=='0')
                            {
                                //最顶级
                                $project_id->update(['project_path'=>'0']);
                                $project_id->save();
                            }else
                            {
                                //不是最顶级，需要遍历
                                $project_path='';
                                $project_path[]=$row['value'];

                                while (1)
                                {
                                    $p=isset($p) ? $p : $row['value'];

                                    $p_tmp=ProjectModel::find($p)->project_parent;

                                    if ($p_tmp=='0')
                                    {
                                        break;
                                    }else
                                    {
                                        $project_path[]=$p_tmp;
                                        $p=$p_tmp;
                                    }
                                }

                                //顺序要反转一下
                                $project_path=array_reverse($project_path);

                                $project_id->update(['project_path'=>implode('-',$project_path)]);
                                $project_id->save();
                            }

                            //给超级管理员加上
                            $admin=StaffModel::find(1);
                            $admin->staff_project=$admin->staff_project.','.$project_id->project_id;
                            $admin->save();

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
                            $si_id=SiTypeModel::create(['si_name'=>$row['value']]);

                            //给超级管理员加上
                            $admin=StaffModel::find(1);
                            $admin->staff_si_type=$admin->staff_si_type.','.$si_id->si_id;
                            $admin->save();

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
                            foreach ($row['value'] as $id)
                            {
                                if (!$id['id']=='0')
                                {
                                    $p[]=$id;
                                }
                            }
                            $staff_info['staff_project']=$this->arr2str_new($p);
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
                            foreach ($row['value'] as $id)
                            {
                                if (!$id['id']=='0')
                                {
                                    $s[]=$id;
                                }
                            }
                            $staff_info['staff_si_type']=$this->arr2str_new($s);
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
                            foreach ($row['value'] as $id)
                            {
                                if (!$id['id']=='0')
                                {
                                    $l[]=$id;
                                }
                            }
                            $staff_info['staff_level']=$this->arr2str_new($l);
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

                $staff_info['allow_login']='0';

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
                    if ($user_info[0]['allow_login']!='0')
                    {
                        return ['error'=>'1','msg'=>'您已被禁止登陆'];
                    }

                    Session::put('user',$user_info);

                    //记录一下员工是从哪个ip登陆的
                    $res=StaffLoginPlaceModel::where('account',$user_info[0]['staff_account'])
                        ->where('ip',$request->getClientIps())
                        ->get();

                    if (!empty($res->toArray()))
                    {
                        //存在这个账号
                        //修改最后登录时间
                        $model=$res[0];
                        $model->last_time=date('Y-m-d H:i:s',time());
                        $model->save();
                    }else
                    {
                        //不存在这个账号
                        //新建一条数据
                        $create=[
                            'account'=>$user_info[0]['staff_account'],
                            'ip'=>$request->ip(),
                            'last_time'=>date('Y-m-d H:i:s',time())
                        ];
                        StaffLoginPlaceModel::create($create);
                    }

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
                        if ($row['value']=='')
                        {
                            return ['error'=>'1','msg'=>'所属地区已经过期，请重新选择'];
                        }else
                        {
                            //当添加第二年审人的时候，地区传过来的数据是中文，所以需要改成对应的主键
                            if ($this->check_chinese_word($row['value']))
                            {
                                $row['value']=ProjectModel::where('project_name',$row['value'])->first()->project_id;
                            }

                            if (!$this->before_insert_check_projectlevel($row['value']))
                            {
                                return ['error'=>'1','msg'=>'您没有该地区的采集权限'];
                            }

                            $cust_info['cust_project']=$row['value'];
                        }
                       //$res=ProjectModel::where(['project_name'=>$row['value']])->pluck('project_id')->toArray();
                       //$cust_info['cust_project']=$res[0];
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
                    $model=CustModel::create($cust_info);
                }

                //储存用户的身份证头像
                Storage::disk('IDcard')->put($model->cust_id,Input::get('cust_photo'));

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
                    //$proj=explode(',',$row['staff_project']);
                    $type=explode(',',$row['staff_si_type']);
                }

                //得到当前用户的所有地区，因为数据库中只存了一部分
                $my_tmp=$this->before_insert_check_projectlevel(0,1);
                $proj=$my_tmp;

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
                    //$proj=explode(',',$row['staff_project']);
                    $type=explode(',',$row['staff_si_type']);
                }

                $my_tmp=$this->before_insert_check_projectlevel(0,1);
                $proj=$my_tmp;

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

                //取出左右节点的子节点
                foreach ($proj as $row1)
                {
                    $proj_all[]=$row1;

                    //取出project_id
                    foreach ($this->get_all_children($row1) as $row2)
                    {
                        $proj_all[]=$row2['project_id'];
                    }
                }

                $proj=$proj_all;

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
                    if ($row['name']=='cust_project')
                    {
                        if ($row['value']!='')
                        {
                            $proj=$row['value'];
                        }else
                        {
                            return ['error'=>'1','msg'=>'请重新选择地区'];
                        }
                    }

                    //判断一下是否已经选择了日期
                    if ($row['name']=='year_and_month')
                    {
                        if ($row['value']=='')
                        {
                            return ['error'=>'1','msg'=>'必须要选择一个日期'];
                        }else
                        {
                            $date=$row['value'];
                        }
                    }

                    if ($row['name']=='vv_or_fv')
                    {
                        $vv_or_fv=$row['value'];
                    }
                }

                //如果输入的是父类地区，就查询父类地区下所有地区的数据
                $proj_tmp=$this->get_all_children($proj);

                foreach ($proj_tmp as $row)
                {
                    $id_of_proj[]=$row['project_id'];
                }
                $id_of_proj[]=$proj;

                if ($vv_or_fv=='1')
                {
                    //声纹
                    //得到年-月
                    $yearAndmonth=substr($date,0,strlen($date)-3);

                    //得到当前年的当前月有多少天
                    $unixTime=strtotime($date);
                    $day=date('t',$unixTime);

                    //从数据库中查询符合条件的数据
                    $data=CustModel::whereIn('cust_project',$id_of_proj)->where('created_at','like',$yearAndmonth.'%')
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
                }elseif ($vv_or_fv=='2')
                {
                    //指静脉
                    //得到年-月
                    $yearAndmonth=substr($date,0,strlen($date)-3);

                    //得到当前年的当前月有多少天
                    $unixTime=strtotime($date);
                    $day=date('t',$unixTime);

                    //从数据库中查询符合条件的数据
                    $data=CustFVModel::whereIn('cust_project',$id_of_proj)->where('created_at','like',$yearAndmonth.'%')
                        ->orderBy('created_at','asc')
                        ->get(['created_at'])
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
                    }else {
                        //上面已经得到当前月的所有数据了
                        foreach ($data as $row) {
                            //只保留年月日
                            $time[] = substr($row, 0, 10);
                        }

                        //制造返回给前端页面的数组
                        foreach (array_count_values($time) as $k => $v) {
                            $morris_data[] = ['y' => $k, 'mytarget' => $v];
                        }

                        //得到当前日期的前缀
                        $prefix = date('Y-m-', $unixTime);

                        //补齐丢失的日期
                        for ($i = 1; $i <= $day; $i++) {
                            if (strlen($i) == '1') {
                                if (!array_key_exists($prefix . '0' . $i, array_count_values($time))) {
                                    $morris_data[] = ['y' => $prefix . '0' . $i, 'mytarget' => '0'];
                                }
                            } else {
                                if (!array_key_exists($prefix . $i, array_count_values($time))) {
                                    $morris_data[] = ['y' => $prefix . $i, 'mytarget' => '0'];
                                }
                            }
                        }

                        return ['error'=>'0','msg'=>'成功','data'=>$morris_data,'data_total'=>array_sum(array_count_values($time))];
                    }
                }else
                {

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

                //*******************************************第一个页面开始***********************************************
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
                            if ($row['value']=='')
                            {
                                return ['error'=>'1','msg'=>'请选择地区'];
                            }
                            //判断这个地区，该用户是否有权限查看
                            //如果用户有该地区的任意一个父节点权限，就有查看该地区的权限
                            if (!$this->check_project_select_permission($row['value']))
                            {
                                return ['error'=>'1','msg'=>'没有查询该地区的权限'];
                            }

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
                                return ['error'=>'1','msg'=>'请输入查询条件'];
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

                        //如果请求的是指静脉数据
                        if ($row['name']=='vv_or_fv')
                        {
                            if ($row['value']=='vocalvena')
                            {
                                $staff_choice='vocalvena';
                            }
                            if ($row['value']=='all')
                            {
                                $staff_choice='all';
                            }
                            if ($row['value']=='fingervena')
                            {
                                $staff_choice='fingervena';
                                //开始结束时间 $start $stop
                                //地区和参保类型 $condition
                                //已通过未通过 $YorN

                                //把时间后面的时分秒去掉
                                $start_tmp=substr($start,0,10);
                                $stop_tmp=substr($stop,0,10);

                                //查询通过的，未通过的，还是全部的
                                if (count($YorN)=='2')
                                {
                                    //查询全部的
                                    $res=CustFVModel::where($condition)
                                        ->whereBetween('created_at',[$start,$stop])
                                        ->get([
                                            'cust_num',
                                            'cust_name',
                                            'cust_id',
                                            'cust_phone_num',
                                            'cust_phone_bku',
                                            'cust_last_confirm_date',
                                            'cust_btw'
                                        ])->toArray();

                                }elseif (count($YorN)=='1')
                                {
                                    //查询通过，或者未通过的
                                    if ($YorN[0]=='Y')
                                    {
                                        //通过
                                        $res=CustFVModel::where($condition)
                                            ->whereBetween('cust_last_confirm_date',[$start_tmp,$stop_tmp])
                                            ->get([
                                                'cust_num',
                                                'cust_name',
                                                'cust_id',
                                                'cust_phone_num',
                                                'cust_phone_bku',
                                                'cust_last_confirm_date',
                                                'cust_btw'
                                            ])->toArray();

                                    }elseif ($YorN[0]=='N')
                                    {
                                        //没通过
                                        $res=CustFVModel::where($condition)
                                            ->whereNotBetween('cust_last_confirm_date',[$start_tmp,$stop_tmp])
                                            ->get([
                                                'cust_num',
                                                'cust_name',
                                                'cust_id',
                                                'cust_phone_num',
                                                'cust_phone_bku',
                                                'cust_last_confirm_date',
                                                'cust_btw'
                                            ])->toArray();
                                    }else
                                    {

                                    }

                                }else
                                {

                                }

                                //给数组添加几个元素
                                foreach ($res as $row)
                                {
                                    $res2['cust_num']=$row['cust_num'];
                                    $res2['cust_name']=$row['cust_name'];
                                    $res2['cust_id']=$row['cust_id'];
                                    $res2['cust_phone_num']=$row['cust_phone_num'];
                                    $res2['cust_phone_bku']=$row['cust_phone_bku'];
                                    $res2['this_is_fv_cust']='指静脉';
                                    $res2['cust_last_confirm_date']=$row['cust_last_confirm_date'];
                                    $res2['pass_the_confirm']='通过认证';
                                    $res2['cust_btw']=$row['cust_btw'];

                                    $res3[]=$res2;

                                    //导出用的
                                    $redis_content[]=$res2['cust_id'];
                                }

                                if (empty($res))
                                {
                                    return ['error'=>'1','msg'=>'没有匹配的数据'];
                                }

                                //总条数
                                $cnt=count($res);

                                $time=time();
                                $this->redis_set('fv_yes_pass'.$time,json_encode($redis_content),100);

                                return ['error'=>'0','msg'=>'查询成功','data'=>$res3,'pages'=>intval(ceil(count($res)/$limit)),'count_data'=>$cnt,'redis_key'=>'fv_yes_pass'.$time];
                            }
                        }
                    }

                    //这里是如果员工选择要导出声纹和指静脉合并后的谁通过，谁没通过的数据
                    //通过$staff_choice这个变量
                    if ($staff_choice=='all')
                    {
                        //执行一次指静脉的过滤条件
                        //以下代码是复制上面的指静脉的
                        //**********************************************************************************************
                        $start_tmp = substr($start, 0, 10);
                        $stop_tmp = substr($stop, 0, 10);

                        //查询通过的，未通过的，还是全部的
                        if (count($YorN) == '2') {
                            //查询全部的
                            $res = CustFVModel::where($condition)
                                ->whereBetween('created_at', [$start, $stop])
                                ->get([
                                    'cust_num',
                                    'cust_name',
                                    'cust_id',
                                    'cust_phone_num',
                                    'cust_phone_bku',
                                    'cust_last_confirm_date',
                                    'cust_btw'
                                ])->toArray();

                        } elseif (count($YorN) == '1') {
                            //查询通过，或者未通过的
                            if ($YorN[0] == 'Y') {
                                //通过
                                $res = CustFVModel::where($condition)
                                    ->whereBetween('cust_last_confirm_date', [$start_tmp, $stop_tmp])
                                    ->get([
                                        'cust_num',
                                        'cust_name',
                                        'cust_id',
                                        'cust_phone_num',
                                        'cust_phone_bku',
                                        'cust_last_confirm_date',
                                        'cust_btw'
                                    ])->toArray();

                            } elseif ($YorN[0] == 'N') {
                                //没通过
                                $res = CustFVModel::where($condition)
                                    ->whereNotBetween('cust_last_confirm_date', [$start_tmp, $stop_tmp])
                                    ->get([
                                        'cust_num',
                                        'cust_name',
                                        'cust_id',
                                        'cust_phone_num',
                                        'cust_phone_bku',
                                        'cust_last_confirm_date',
                                        'cust_btw'
                                    ])->toArray();
                            } else {

                            }

                        } else {

                        }

                        //给数组添加几个元素
                        foreach ($res as $row) {
                            $res2['cust_num'] = $row['cust_num'];
                            $res2['cust_name'] = $row['cust_name'];
                            $res2['cust_id'] = $row['cust_id'];
                            $res2['cust_phone_num'] = $row['cust_phone_num'];
                            $res2['cust_phone_bku'] = $row['cust_phone_bku'];
                            $res2['this_is_fv_cust'] = '指静脉';
                            $res2['cust_last_confirm_date'] = $row['cust_last_confirm_date'];
                            $res2['pass_the_confirm'] = '通过认证';
                            $res2['cust_btw'] = $row['cust_btw'];

                            $res3[] = $res2;

                            //导出用的
                            $redis_content[] = $res2['cust_id'];
                        }

                        if (empty($res)) {
                            $res3 = [];
                        }

                        //$res3是指静脉符合条件的客户
                        $final_fv_res=$res3;
                        //**********************************************************************************************

                        //以下代码是复制声纹的
                        //**********************************************************************************************
                        $get = [
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
                        if (count($YorN) == '1' && $YorN[0] == 'N') {
                            //仅仅是查询未通过的
                            //判断是不是超级管理员
                            if (Session::get('user')[0]['staff_num'] == '1') {
                                //是超管
                                $sql = "select confirm_num,confirm_pid,confirm_res,confirm_btw,created_at,COUNT(confirm_pid) as num 
FROM (select * from zbxl_customer_confirm as t1 where created_at between ? and ? GROUP BY confirm_pid,confirm_res) as t2 
GROUP BY confirm_pid HAVING (num<? AND confirm_res=?)";

                                $res1 = \DB::select($sql, [$start, $stop, '2', 'N']);
                            } else {
                                //不是超管
                                $sql = "select confirm_num,confirm_pid,confirm_res,confirm_btw,created_at,COUNT(confirm_pid) as num 
FROM (select * from zbxl_customer_confirm as t1 where belong_to=? AND created_at between ? and ? GROUP BY confirm_pid,confirm_res) as t2 
GROUP BY confirm_pid HAVING (num<? AND confirm_res=?)";

                                $mypid = Session::get('user')[0]['staff_num'];
                                $res1 = \DB::select($sql, [$mypid, $start, $stop, '2', 'N']);
                            }

                            //对象转数组
                            $res2 = $this->obj2arr($res1);

                            if (empty($res2)) {
                                //这里是如果声纹没有符合的，看看指静脉有没有符合的
                                if (!empty($final_fv_res)) {
                                    $time = time();
                                    $this->redis_set('only_fv_nopass' . $time, json_encode($redis_content), 100);

                                    return ['error' => '0', 'msg' => '查询成功', 'data' => $final_fv_res, 'pages' => intval(ceil(count($final_fv_res) / $limit)),
                                        'count_data' => count($final_fv_res), 'redis_key' => 'only_fv_nopass' . $time];
                                }

                                return ['error' => '0', 'msg' => '查询成功'];
                            }

                            //给每个数组里插入客户相关信息
                            foreach ($res2 as &$row) {
                                try {
                                    $data = CustModel::findOrFail($row['confirm_pid']);
                                } catch (ModelNotFoundException $e) {
                                    //没有查找到相应客户，说明客户被删除
                                    $row = [];
                                    continue;
                                }

                                if ($data->cust_project == $condition['cust_project'] && $data->cust_si_type == $condition['cust_si_type']) {
                                    //判断要查询的是A还是B用户
                                    if (count($AorB) == '2') {
                                        //全部的用户
                                        $row['cust_name'] = $data->cust_name;
                                        $row['cust_id'] = $data->cust_id;
                                        $row['cust_review_num'] = $data->cust_review_num;
                                        $row['cust_phone_num'] = $data->cust_phone_num;
                                        $row['cust_type'] = $data->cust_type;
                                    } else {
                                        //只是A或者B
                                        if ($data->cust_type == $AorB[0]) {
                                            $row['cust_name'] = $data->cust_name;
                                            $row['cust_id'] = $data->cust_id;
                                            $row['cust_review_num'] = $data->cust_review_num;
                                            $row['cust_phone_num'] = $data->cust_phone_num;
                                            $row['cust_type'] = $data->cust_type;
                                        } else {
                                            //不满族条件直接清除数据
                                            $row = [];
                                        }
                                    }
                                } else {
                                    //不满族条件直接清除数据
                                    $row = [];
                                }
                            }

                            unset($row);
                            //去掉空数组后，这才是满足条件的所有数据
                            $res2 = array_filter($res2);
                            $res2 = array_values($res2);

                            //自制分页
                            $data1 = [];
                            for ($i = $offset; $i <= $limit * Input::get('page') - 1; $i++) {
                                if (!isset($res2[$i])) {
                                    break;
                                }
                                $data2 = null;

                                //为了符合前台页面显示，数组里的数据顺序需要改一下
                                $data2['confirm_num'] = $res2[$i]['confirm_num'];
                                $data2['cust_name'] = $res2[$i]['cust_name'];
                                $data2['cust_id'] = $res2[$i]['cust_id'];
                                $data2['cust_review_num'] = $res2[$i]['cust_review_num'];
                                $data2['cust_phone_num'] = $res2[$i]['cust_phone_num'];
                                $data2['cust_type'] = $res2[$i]['cust_type'];
                                $data2['created_at'] = $res2[$i]['created_at'];
                                $data2['confirm_res'] = $res2[$i]['confirm_res'];
                                $data2['confirm_btw'] = $res2[$i]['confirm_btw'];

                                $data1[] = $data2;
                            }

                            //把所有没通过的客户的pid取出来放到redis里
                            foreach ($res2 as $row) {
                                $redis_value[] = $row['confirm_pid'];
                            }

                            if (!isset($redis_value))
                            {
                                return ['error' => '0', 'msg' => '没有匹配的数据'];
                            }else
                            {
                                //这里是声纹有未通过的，看看指静脉有没有符合的
                                if (!empty($final_fv_res))
                                {
                                    //前台的筛选条件
                                    $cond_of_web_time=[$start,$stop];
                                    $myres=$this->filter_confirm_res($res2,$final_fv_res,'nopass',$cond_of_web_time);

                                    //自制分页
                                    $data1=[];
                                    for ($i=$offset;$i<=$limit*Input::get('page')-1;$i++)
                                    {
                                        if (!isset($myres[0][$i]))
                                        {
                                            break;
                                        }
                                        $data2=null;

                                        //为了符合前台页面显示，数组里的数据顺序需要改一下
                                        if (isset($myres[0][$i]['confirm_num']))
                                        {
                                            $data2['confirm_num']    = $myres[0][$i]['confirm_num'];
                                            $data2['cust_name']      = $myres[0][$i]['cust_name'];
                                            $data2['cust_id']        = $myres[0][$i]['cust_id'];
                                            $data2['cust_review_num']= $myres[0][$i]['cust_review_num'];
                                            $data2['cust_phone_num'] = $myres[0][$i]['cust_phone_num'];
                                            $data2['cust_type']      = $myres[0][$i]['cust_type'];
                                            $data2['created_at']     = $myres[0][$i]['created_at'];
                                            $data2['confirm_res']    = $myres[0][$i]['confirm_res'];
                                            $data2['confirm_btw']    = $myres[0][$i]['confirm_btw'];
                                        }else
                                        {
                                            $data2['confirm_num']    = $myres[0][$i]['cust_num'];
                                            $data2['cust_name']      = $myres[0][$i]['cust_name'];
                                            $data2['cust_id']        = $myres[0][$i]['cust_id'];
                                            $data2['cust_phone_num'] = $myres[0][$i]['cust_phone_num'];
                                            $data2['cust_phone_bku'] = $myres[0][$i]['cust_phone_bku'];
                                            $data2['this_is_fv_cust']= $myres[0][$i]['this_is_fv_cust'];
                                            $data2['cust_last_confirm_date']=$myres[0][$i]['cust_last_confirm_date'];
                                            $data2['pass_the_confirm']=$myres[0][$i]['pass_the_confirm'];
                                            $data2['cust_btw']       =$myres[0][$i]['cust_btw'];
                                        }

                                        $data1[]=$data2;
                                    }

                                    return ['error'=>'0','msg'=>'查询成功','data'=>$data1,'pages'=>intval(ceil(count($myres[0])/$limit)),
                                        'count_data'=>count($myres[0]),'redis_key'=>$myres[1]];
                                }
                            }

                            $redis_key = 'daochu_' . time();
                            $this->redis_set($redis_key, json_encode($redis_value), 60);

                            return ['error'=>'0','msg'=>'查询成功','data'=>$data1,'pages'=>intval(ceil(count($res2)/$limit)),
                                'count_data'=>count($res2),'redis_key'=>$redis_key];
                        } else {
                            //判断是不是超管
                            if (Session::get('user')[0]['staff_num'] == '1') {
                                //是超管
                                $res = \DB::table('customer_info')
                                    ->leftJoin('customer_confirm', 'customer_info.cust_num', '=', 'customer_confirm.confirm_pid')
                                    ->where($condition)
                                    ->whereIn('customer_confirm.confirm_res', $YorN)
                                    ->whereIn('customer_info.cust_type', $AorB)
                                    ->orderBy('customer_confirm.confirm_pid', 'desc')
                                    ->orderBy('customer_confirm.created_at', 'desc')
                                    ->wherebetween('customer_confirm.created_at', [$start, $stop])
                                    ->offset($offset)->limit($limit)
                                    ->get($get);

                                //查询总页数
                                $cnt = \DB::table('customer_info')
                                    ->leftJoin('customer_confirm', 'customer_info.cust_num', '=', 'customer_confirm.confirm_pid')
                                    ->where($condition)
                                    ->whereIn('customer_confirm.confirm_res', $YorN)
                                    ->whereIn('customer_info.cust_type', $AorB)
                                    ->wherebetween('customer_confirm.created_at', [$start, $stop])
                                    ->count();
                                $cnt_page = intval(ceil($cnt / $limit));
                            } else {
                                //不是超管
                                $res = \DB::table('customer_info')
                                    ->leftJoin('customer_confirm', 'customer_info.cust_num', '=', 'customer_confirm.confirm_pid')
                                    ->where($condition)
                                    ->where('belong_to', $mypid = Session::get('user')[0]['staff_num'])
                                    ->whereIn('customer_confirm.confirm_res', $YorN)
                                    ->whereIn('customer_info.cust_type', $AorB)
                                    ->orderBy('customer_confirm.confirm_pid', 'desc')
                                    ->orderBy('customer_confirm.created_at', 'desc')
                                    ->wherebetween('customer_confirm.created_at', [$start, $stop])
                                    ->offset($offset)->limit($limit)
                                    ->get($get);

                                //查询总页数
                                $cnt = \DB::table('customer_info')
                                    ->leftJoin('customer_confirm', 'customer_info.cust_num', '=', 'customer_confirm.confirm_pid')
                                    ->where($condition)
                                    ->where('belong_to', $mypid = Session::get('user')[0]['staff_num'])
                                    ->whereIn('customer_confirm.confirm_res', $YorN)
                                    ->whereIn('customer_info.cust_type', $AorB)
                                    ->wherebetween('customer_confirm.created_at', [$start, $stop])
                                    ->count();
                                $cnt_page = intval(ceil($cnt / $limit));
                            }

                            //从这里以上是导出数据的逻辑
                            foreach ($res as $myrow)
                            {
                                $cust_id[]=$myrow->cust_id;
                            }

                            //看看指静脉有没有通过的
                            $finger_res=CustFVModel::where($condition)->where('cust_last_confirm_date','>=',$start_tmp)->get([
                                'cust_name',
                                'cust_id',
                                'cust_phone_num',
                                'cust_phone_bku',
                                'cust_last_confirm_date',
                                'cust_num',
                                'cust_btw'])->toArray();

                            if (!isset($cust_id) && !empty($finger_res))
                            {
                                //声纹无数据，指静脉有数据
                                //自制分页
                                $data1=[];
                                for ($i=$offset;$i<=$limit*Input::get('page')-1;$i++)
                                {
                                    if (!isset($finger_res[$i]))
                                    {
                                        break;
                                    }
                                    $data2=null;

                                    //为了符合前台页面显示，数组里的数据顺序需要改一下
                                    $data2['cust_name']      = $finger_res[$i]['cust_name'];
                                    $data2['cust_id']        = $finger_res[$i]['cust_id'];
                                    $data2['cust_review_num']= $finger_res[$i]['cust_phone_num'];
                                    $data2['cust_phone_num'] = $finger_res[$i]['cust_phone_bku'];
                                    $data2['cust_type']      = '指静脉';
                                    $data2['created_at']     = $finger_res[$i]['cust_last_confirm_date'];
                                    $data2['confirm_res']    = '通过认证';
                                    $data2['confirm_num']    = $finger_res[$i]['cust_num'];
                                    $data2['confirm_btw']    = $finger_res[$i]['cust_btw'];

                                    $data1[]=$data2;
                                    $cust_id[]=$finger_res[$i]['cust_id'];
                                }

                                $time=time();
                                $this->redis_set('only_fv_yespass'.$time,json_encode($cust_id),100);

                                return ['error'=>'0','msg'=>'查询成功','data'=>$data1,'pages'=>intval(ceil(count($finger_res)/$limit)),'count_data'=>count($finger_res),'redis_key'=>'only_fv_yespass'.$time];
                            }elseif (!isset($cust_id) && empty($finger_res))
                            {
                                //声纹无数据，指静脉无数据
                                return ['error'=>'0','msg'=>'无匹配数据'];
                            }elseif (isset($cust_id) && !empty($finger_res))
                            {
                                //声纹有数据，指静脉有数据
                                //$res是声纹的数据，$cust_id是声纹的身份证号
                                $myres=$this->filter_confirm_res($res,$finger_res,'pass',[$start,$stop]);

                                //自制分页
                                $data1=[];
                                for ($i=$offset;$i<=$limit*Input::get('page')-1;$i++)
                                {
                                    if (!isset($myres[0][$i]))
                                    {
                                        break;
                                    }
                                    $data2=null;

                                    //为了符合前台页面显示，数组里的数据顺序需要改一下
                                    if (isset($myres[0][$i]['cust_review_num']))
                                    {
                                        $data2['confirm_num']    = $myres[0][$i]['confirm_num'];
                                        $data2['cust_name']      = $myres[0][$i]['cust_name'];
                                        $data2['cust_id']        = $myres[0][$i]['cust_id'];
                                        $data2['cust_review_num']= $myres[0][$i]['cust_review_num'];
                                        $data2['cust_phone_num'] = $myres[0][$i]['cust_phone_num'];
                                        $data2['cust_type']      = $myres[0][$i]['cust_type'];
                                        $data2['created_at']     = $myres[0][$i]['created_at'];
                                        $data2['confirm_res']    = $myres[0][$i]['confirm_res'];
                                        $data2['confirm_btw']    = $myres[0][$i]['confirm_btw'];
                                    }else
                                    {
                                        $data2['confirm_num']    = $myres[0][$i]['cust_num'];
                                        $data2['cust_name']      = $myres[0][$i]['cust_name'];
                                        $data2['cust_id']        = $myres[0][$i]['cust_id'];
                                        $data2['cust_phone_num'] = $myres[0][$i]['cust_phone_num'];
                                        $data2['cust_phone_bku'] = $myres[0][$i]['cust_phone_bku'];
                                        $data2['this_is_fv_cust']= '指静脉';
                                        $data2['cust_last_confirm_date']=$myres[0][$i]['cust_last_confirm_date'];
                                        $data2['pass_the_confirm']='通过认证';
                                        $data2['cust_btw']       =$myres[0][$i]['cust_btw'];
                                    }

                                    $data1[]=$data2;
                                }
                                return ['error'=>'0','msg'=>'查询成功','data'=>$data1,'pages'=>intval(ceil(count($myres[0])/$limit)),'count_data'=>count($myres[0]),'redis_key'=>$myres[1]];
                            }

                            //声纹有数据，指静脉无数据
                            $time=time();
                            $this->redis_set('yes_pass'.$time,json_encode($cust_id),100);

                            return ['error'=>'0','msg'=>'查询成功','data'=>$res,'pages'=>$cnt_page,'count_data'=>$cnt,'redis_key'=>'yes_pass'.$time];
                        }
                    }
                    //**************************************************************************************************

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
                        //判断是不是超级管理员
                        if (Session::get('user')[0]['staff_num']=='1')
                        {
                            //是超管
                            $sql="select confirm_num,confirm_pid,confirm_res,confirm_btw,created_at,COUNT(confirm_pid) as num 
FROM (select * from zbxl_customer_confirm as t1 where created_at between ? and ? GROUP BY confirm_pid,confirm_res) as t2 
GROUP BY confirm_pid HAVING (num<? AND confirm_res=?)";

                            $res1=\DB::select($sql,[$start,$stop,'2','N']);
                        }else
                        {
                            //不是超管
                            $sql="select confirm_num,confirm_pid,confirm_res,confirm_btw,created_at,COUNT(confirm_pid) as num 
FROM (select * from zbxl_customer_confirm as t1 where belong_to=? AND created_at between ? and ? GROUP BY confirm_pid,confirm_res) as t2 
GROUP BY confirm_pid HAVING (num<? AND confirm_res=?)";

                            $mypid=Session::get('user')[0]['staff_num'];
                            $res1=\DB::select($sql,[$mypid,$start,$stop,'2','N']);
                        }

                        //对象转数组
                        $res2=$this->obj2arr($res1);

                        if (empty($res2))
                        {
                            return ['error'=>'0','msg'=>'查询成功'];
                        }

                        //给每个数组里插入客户相关信息
                        foreach ($res2 as &$row)
                        {
                            try
                            {
                                $data=CustModel::findOrFail($row['confirm_pid']);
                            }catch (ModelNotFoundException $e)
                            {
                                //没有查找到相应客户，说明客户被删除
                                $row=[];
                                continue;
                            }

                            if ($data->cust_project==$condition['cust_project'] && $data->cust_si_type==$condition['cust_si_type'])
                            {
                                //判断要查询的是A还是B用户
                                if (count($AorB)=='2')
                                {
                                    //全部的用户
                                    $row['cust_name']=$data->cust_name;
                                    $row['cust_id']=$data->cust_id;
                                    $row['cust_review_num']=$data->cust_review_num;
                                    $row['cust_phone_num']=$data->cust_phone_num;
                                    $row['cust_type']=$data->cust_type;
                                }else
                                {
                                    //只是A或者B
                                    if ($data->cust_type==$AorB[0])
                                    {
                                        $row['cust_name']=$data->cust_name;
                                        $row['cust_id']=$data->cust_id;
                                        $row['cust_review_num']=$data->cust_review_num;
                                        $row['cust_phone_num']=$data->cust_phone_num;
                                        $row['cust_type']=$data->cust_type;
                                    }else
                                    {
                                        //不满族条件直接清除数据
                                        $row=[];
                                    }
                                }
                            }else
                            {
                                //不满族条件直接清除数据
                                $row=[];
                            }
                        }

                        unset($row);
                        //去掉空数组后，这才是满足条件的所有数据
                        $res2=array_filter($res2);
                        $res2=array_values($res2);

                        //自制分页
                        $data1=[];
                        for ($i=$offset;$i<=$limit*Input::get('page')-1;$i++)
                        {
                            if (!isset($res2[$i]))
                            {
                                break;
                            }
                            $data2=null;

                            //为了符合前台页面显示，数组里的数据顺序需要改一下
                            $data2['confirm_num']=$res2[$i]['confirm_num'];
                            $data2['cust_name']=$res2[$i]['cust_name'];
                            $data2['cust_id']=$res2[$i]['cust_id'];
                            $data2['cust_review_num']=$res2[$i]['cust_review_num'];
                            $data2['cust_phone_num']=$res2[$i]['cust_phone_num'];
                            $data2['cust_type']=$res2[$i]['cust_type'];
                            $data2['created_at']=$res2[$i]['created_at'];
                            $data2['confirm_res']=$res2[$i]['confirm_res'];
                            $data2['confirm_btw']=$res2[$i]['confirm_btw'];

                            $data1[]=$data2;
                        }

                        //把所有没通过的客户的pid取出来放到redis里
                        foreach ($res2 as $row)
                        {
                            $redis_value[]=$row['confirm_pid'];
                        }

                        if (!isset($redis_value))
                        {
                            return ['error'=>'0','msg'=>'没有匹配的数据'];
                        }

                        $redis_key='daochu_'.time();
                        $this->redis_set($redis_key,json_encode($redis_value),60);

                        return ['error'=>'0','msg'=>'查询成功','data'=>$data1,'pages'=>intval(ceil(count($res2)/$limit)),'count_data'=>count($res2),'redis_key'=>$redis_key];
                    }else
                    {
                        //判断是不是超管
                        if (Session::get('user')[0]['staff_num']=='1')
                        {
                            //是超管
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
                        }else
                        {
                            //不是超管
                            $res=\DB::table('customer_info')
                                ->leftJoin('customer_confirm','customer_info.cust_num','=','customer_confirm.confirm_pid')
                                ->where($condition)
                                ->where('belong_to',$mypid=Session::get('user')[0]['staff_num'])
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
                                ->where('belong_to',$mypid=Session::get('user')[0]['staff_num'])
                                ->whereIn('customer_confirm.confirm_res',$YorN)
                                ->whereIn('customer_info.cust_type',$AorB)
                                ->wherebetween('customer_confirm.created_at',[$start,$stop])
                                ->count();
                            $cnt_page=intval(ceil($cnt/$limit));
                        }

                        //从这里以上是导出数据的逻辑
                        foreach ($res as $myrow)
                        {
                            $cust_id[]=$myrow->cust_id;
                        }

                        if (!isset($cust_id))
                        {
                            return ['error'=>'0','msg'=>'没有匹配的数据'];
                        }

                        $time=time();
                        $this->redis_set('yes_pass'.$time,json_encode($cust_id),100);

                        return ['error'=>'0','msg'=>'查询成功','data'=>$res,'pages'=>$cnt_page,'count_data'=>$cnt,'redis_key'=>'yes_pass'.$time];
                    }
                    //*****************************************第一个页面结束*********************************************
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

            case 'daochushuju':

                $redis_key=Input::get('key');

                if (Redis::get($redis_key)=='')
                {
                    return ['error'=>'1','msg'=>'数据已经过期，请重新选择'];
                }

                //取出哪些数据
                $get=[
                    'cust_name','cust_id','cust_si_id'
                ];

                //这里是客户的主键数组
                $in_data=json_decode(Redis::get($redis_key),true);

                //only_fv_nopass
                if (strpos(Input::get('key'),'only_fv_nopass')!==false)
                {
                    //strpos($a, $b) !== false 如果$a 中存在 $b，则为 true ，否则为 false。
                    $res=CustFVModel::whereIn('cust_id',$in_data)->get($get)->toArray();

                    //给res的第一行
                    array_unshift($res,['客户姓名','身份证号','社保编号']);

                    //因为ajax触发不了Excel::里的export方法，所以用redis传过去
                    $time=time();
                    $this->redis_set('only_fv_nopass'.$time,json_encode($res),100);

                    //生成excel
                    file_get_contents(env('APP_URL').'/export4/only_fv_nopass'.$time);

                    $excel_file=env('APP_URL').'/storage/exports/'.'only_fv_nopass'.$time.'.xls';

                    return ['error'=>'0','msg'=>'导出成功','file_name'=>$excel_file];

                }

                //BothNopass
                if (strpos(Input::get('key'),'BothNopass')!==false)
                {
                    //strpos($a, $b) !== false 如果$a 中存在 $b，则为 true ，否则为 false。
                    if (!empty($in_data['intersect']))
                    {
                        $res_both=CustModel::whereIn('cust_id',$in_data['intersect'])->get($get)->toArray();
                        foreach ($res_both as &$row)
                        {
                            $row['service']='声纹和指静脉';
                        }
                        unset($row);
                    }
                    if (!empty($in_data['vp']))
                    {
                        $res_vp=CustModel::whereIn('cust_id',$in_data['vp'])->get($get)->toArray();
                        foreach ($res_vp as &$row)
                        {
                            $row['service']='声纹';
                        }
                        unset($row);
                    }
                    if (!empty($in_data['fv']))
                    {
                        $res_fv=CustFVModel::whereIn('cust_id',$in_data['fv'])->get($get)->toArray();
                        foreach ($res_fv as &$row)
                        {
                            $row['service']='指静脉';
                        }
                        unset($row);
                    }

                    $res_both=isset($res_both)?$res_both:[];
                    $res_vp=isset($res_vp)?$res_vp:[];
                    $res_fv=isset($res_fv)?$res_fv:[];
                    $res=array_merge($res_both,$res_vp,$res_fv);

                    //给res的第一行
                    array_unshift($res,['客户姓名','身份证号','社保编号','参与认证并且未通过']);

                    //因为ajax触发不了Excel::里的export方法，所以用redis传过去
                    $time=time();
                    $this->redis_set('BothNopass'.$time,json_encode($res),100);

                    //生成excel
                    file_get_contents(env('APP_URL').'/export5/BothNopass'.$time);

                    $excel_file=env('APP_URL').'/storage/exports/'.'BothNopass'.$time.'.xls';

                    return ['error'=>'0','msg'=>'导出成功','file_name'=>$excel_file];

                }

                //BothYespass
                if (strpos(Input::get('key'),'BothYespass')!==false)
                {
                    //strpos($a, $b) !== false 如果$a 中存在 $b，则为 true ，否则为 false。
                    if (!empty($in_data['intersect']))
                    {
                        $res_both=CustModel::whereIn('cust_id',$in_data['intersect'])->get($get)->toArray();
                        foreach ($res_both as &$row)
                        {
                            $row['service']='声纹和指静脉';
                        }
                        unset($row);
                    }
                    if (!empty($in_data['vp']))
                    {
                        $res_vp=CustModel::whereIn('cust_id',$in_data['vp'])->get($get)->toArray();
                        foreach ($res_vp as &$row)
                        {
                            $row['service']='声纹';
                        }
                        unset($row);
                    }
                    if (!empty($in_data['fv']))
                    {
                        $res_fv=CustFVModel::whereIn('cust_id',$in_data['fv'])->get($get)->toArray();
                        foreach ($res_fv as &$row)
                        {
                            $row['service']='指静脉';
                        }
                        unset($row);
                    }

                    $res_both=isset($res_both)?$res_both:[];
                    $res_vp=isset($res_vp)?$res_vp:[];
                    $res_fv=isset($res_fv)?$res_fv:[];
                    $res=array_merge($res_both,$res_vp,$res_fv);

                    //给res的第一行
                    array_unshift($res,['客户姓名','身份证号','社保编号','参与认证并且未通过']);

                    //因为ajax触发不了Excel::里的export方法，所以用redis传过去
                    $time=time();
                    $this->redis_set('BothYespass'.$time,json_encode($res),100);

                    //生成excel
                    file_get_contents(env('APP_URL').'/export7/BothYespass'.$time);

                    $excel_file=env('APP_URL').'/storage/exports/'.'BothYespass'.$time.'.xls';

                    return ['error'=>'0','msg'=>'导出成功','file_name'=>$excel_file];

                }

                //only_fv_yespass
                if (strpos(Input::get('key'),'only_fv_yespass')!==false)
                {
                    //strpos($a, $b) !== false 如果$a 中存在 $b，则为 true ，否则为 false。
                    $res=CustFVModel::whereIn('cust_id',$in_data)->get($get)->toArray();

                    //给res的第一行
                    array_unshift($res,['客户姓名','身份证号','社保编号']);

                    //因为ajax触发不了Excel::里的export方法，所以用redis传过去
                    $time=time();
                    $this->redis_set('only_fv_yespass'.$time,json_encode($res),100);

                    //生成excel
                    file_get_contents(env('APP_URL').'/export6/only_fv_yespass'.$time);

                    $excel_file=env('APP_URL').'/storage/exports/'.'only_fv_yespass'.$time.'.xls';

                    return ['error'=>'0','msg'=>'导出成功','file_name'=>$excel_file];

                }

                //如果要导出的是指静脉数据
                if (strpos(Input::get('key'),'fv_yes_pass')!==false)
                {
                    //strpos($a, $b) !== false 如果$a 中存在 $b，则为 true ，否则为 false。
                    $res=CustFVModel::whereIn('cust_id',$in_data)->get($get)->toArray();

                    //给res的第一行
                    array_unshift($res,['客户姓名','身份证号','社保编号']);

                    //因为ajax触发不了Excel::里的export方法，所以用redis传过去
                    $time=time();
                    $this->redis_set('fv_yes_pass'.$time,json_encode($res),100);

                    //生成excel
                    file_get_contents(env('APP_URL').'/export3/fv_yes_pass'.$time);

                    $excel_file=env('APP_URL').'/storage/exports/'.'fv_yes_pass'.$time.'.xls';

                    return ['error'=>'0','msg'=>'导出成功','file_name'=>$excel_file];

                }

                if (strpos(Input::get('key'),'yes_pass')!==false)
                {
                    //strpos($a, $b) !== false 如果$a 中存在 $b，则为 true ，否则为 false。
                    $res=CustModel::whereIn('cust_id',$in_data)->get($get)->toArray();

                    //给res的第一行
                    array_unshift($res,['客户姓名','身份证号','社保编号']);

                    //因为ajax触发不了Excel::里的export方法，所以用redis传过去
                    $time=time();
                    $this->redis_set('yes_pass'.$time,json_encode($res),100);

                    //生成excel
                    file_get_contents(env('APP_URL').'/export1/yes_pass'.$time);

                    $excel_file=env('APP_URL').'/storage/exports/'.'yes_pass'.$time.'.xls';

                }else
                {
                    $res=CustModel::whereIn('cust_num',$in_data)->get($get)->toArray();

                    //给res的第一行
                    array_unshift($res,['客户姓名','身份证号','社保编号']);

                    //因为ajax触发不了Excel::里的export方法，所以用redis传过去
                    $time=time();
                    $this->redis_set('no_pass'.$time,json_encode($res),100);

                    //生成excel
                    file_get_contents(env('APP_URL').'/export1/no_pass'.$time);

                    $excel_file=env('APP_URL').'/storage/exports/'.'no_pass'.$time.'.xls';
                }

                return ['error'=>'0','msg'=>'导出成功','file_name'=>$excel_file];

                break;

            case 'allocation':

                $data=Input::all();

                //员工主键
                if (!isset($data['key1']))
                {
                    return ['error'=>'1','msg'=>'没有选择待分配的员工'];
                }else
                {
                    $staff_pid=$data['key1'][0]['value'];
                }

                //要分配多少条
                if ($data['key2']=='0')
                {
                    return ['error'=>'1','msg'=>'分配0条数据？'];
                }else
                {
                    //要分配多少条
                    $data_totle=$data['key2'];
                }

                //时间范围
                foreach ($data['key3'] as $cond)
                {
                    if ($cond['name']=='star_date')
                    {
                        $start=$cond['value'].' '.'00:00:00';
                    }
                    if ($cond['name']=='stop_date')
                    {
                        $stop=$cond['value'].' '.'23:59:59';
                    }
                }

                //从redis里取出数据主键，然后修改belong_to字段
                //通过confirm_pid和时间范围确定更新哪些数据
                $data_redis=json_decode(Redis::get('allocation'),true);

                //判断一下要分配的条数是不是超过了redis中含有的条数
                if ($data_totle > count($data_redis))
                {
                    return ['error'=>'1','msg'=>'要分配的数据超出了现有的数据'];
                }

                //拿出相应条数的数据
                for ($i=0;$i<$data_totle;$i++)
                {
                    $data_tmp[]=array_pop($data_redis);
                }

                //只需要confrim_pid字段
                foreach ($data_tmp as $row)
                {
                    $confirm_pid_array[]=$row['confirm_pid'];
                }

                $change=CustConfirmModel::whereIn('confirm_pid',$confirm_pid_array)
                    ->whereBetween('created_at',[$start,$stop])
                    ->update(['belong_to'=>$staff_pid]);

                return ['error'=>'0','msg'=>'数据被分配完成'];

                break;

            case 'allocation_change':

                //用户传入的页
                $now_page=Input::get('page');

                //每页显示几条数据
                $limit=5;

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
                            if (!$this->check_project_select_permission($row['value']))
                            {
                                return ['error'=>'1','msg'=>'没有查看该区域的权限'];
                            }
                            $condition['cust_project']=$row['value'];
                            //$staff_cond['staff_project']=$row['value'];
                        }

                        if ($row['name']=='cust_si_type')
                        {
                            $condition['cust_si_type']=$row['value'];
                            //$staff_cond['staff_si_type']=$row['value'];
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

                        if ($row['name']=='vv_or_fv')
                        {
                            if ($row['value']=='1')
                            {
                                //声纹数据
                                $vv_or_fv='1';
                            }elseif ($row['value']=='2')
                            {
                                //指静脉数据
                                $vv_or_fv='2';
                            }else
                            {
                            }
                        }
                    }

                    //要声纹还是指静脉
                    if ($vv_or_fv=='2')
                    {
                        //指静脉
                        dd($condition);

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
FROM (select * from zbxl_customer_confirm as t1 where belong_to=? AND created_at between ? and ? GROUP BY confirm_pid,confirm_res) as t2 
GROUP BY confirm_pid HAVING (num<? AND confirm_res=?)";

                        $res1=\DB::select($sql,['0',$start,$stop,'2','N']);

                        //对象转数组
                        $res2=$this->obj2arr($res1);

                        if (empty($res2))
                        {
                            return ['error'=>'0','msg'=>'查询成功，没有匹配的数据','count_data'=>'0'];
                        }

                        //给每个数组里插入客户相关信息
                        foreach ($res2 as &$row)
                        {
                            try
                            {
                                //数据没有找到，说明用户已经删除了
                                $data=CustModel::findOrFail($row['confirm_pid']);
                            }catch (ModelNotFoundException $e)
                            {
                                continue;
                            }

                            if ($data->cust_project==$condition['cust_project'] && $data->cust_si_type==$condition['cust_si_type'])
                            {
                                //判断要查询的是A还是B用户
                                if (count($AorB)=='2')
                                {
                                    //全部的用户
                                    $row['cust_name']=$data->cust_name;
                                    $row['cust_id']=$data->cust_id;
                                    $row['cust_review_num']=$data->cust_review_num;
                                    $row['cust_phone_num']=$data->cust_phone_num;
                                    $row['cust_type']=$data->cust_type;
                                }else
                                {
                                    //只是A或者B
                                    if ($data->cust_type==$AorB[0])
                                    {
                                        $row['cust_name']=$data->cust_name;
                                        $row['cust_id']=$data->cust_id;
                                        $row['cust_review_num']=$data->cust_review_num;
                                        $row['cust_phone_num']=$data->cust_phone_num;
                                        $row['cust_type']=$data->cust_type;
                                    }else
                                    {
                                        //不满足条件直接清除数据
                                        $row=[];
                                    }
                                }
                            }else
                            {
                                //不满足条件直接清除数据
                                $row=[];
                            }
                        }

                        unset($row);
                        //去掉空数组后，这才是满足条件的所有数据
                        $res2=array_filter($res2);
                        $res2=array_values($res2);

                        if (empty($res2))
                        {
                            return ['error'=>'0','msg'=>'查询成功，没有匹配的数据','count_data'=>'0'];
                        }

                        //这里的res2是待分配的客户数据，存到redis里
                        Redis::set('allocation',json_encode($res2));

                        //找到属于这个属地，这个参保类型的员工
                        $staff_array=StaffModel::where('staff_num','<>','1')->get([
                            'staff_name',
                            'staff_account',
                            'staff_num',
                            'staff_project',
                            'staff_si_type'
                        ])->toArray();

                        //最后给前端页面传过去的变量，存放员工信息
                        $staff_array_info=null;

                        foreach ($staff_array as $row)
                        {
                            if (in_array($condition['cust_project'],explode(',',$row['staff_project'])) && in_array($condition['cust_si_type'],explode(',',$row['staff_si_type'])))
                            {
                                //属于当前区域，并且属于当前参保类型
                                $staff_array_info[]=$row;
                            }else
                            {
                                //不满足其一的就不显示
                            }
                        }

                        foreach ($staff_array_info as &$row)
                        {
                            unset($row['staff_project']);
                            unset($row['staff_si_type']);
                            $row['mission']=$row['staff_num'];
                        }

                        //员工信息的自制分页
                        for ($i=$offset;$i<=$limit*Input::get('page')-1;$i++)
                        {
                            if (!isset($staff_array_info[$i]))
                            {
                                break;
                            }

                            $staff_tmp[]=$staff_array_info[$i];
                        }

                        return ['error'=>'0','msg'=>'查询成功','staff'=>$staff_tmp,'pages'=>intval(ceil(count($staff_array_info)/$limit)),'count_data'=>count($res2)];
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
                    $data->save();

                    //判断是否需要改成通过认证
                    if ($YorN=='Y')
                    {
                        $data->confirm_res='Y';
                        $data->save();

                        $this->system_log('修改认证表的认证结果','把主键是'.$pid.'的认证结果改成了<通过认证>');
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

            case 'modify_btw_fv':

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
                    $data=CustFVModel::find($pid);

                    $data->cust_btw=$cond;
                    $data->save();

                    //判断是否需要改成通过认证
                    if ($YorN=='Y')
                    {
                        $data->cust_last_confirm_date=date('Y-m-d',time());
                        $today=date('Y-m-d',time());
                        $data->save();

                        $this->system_log('修改指静脉认证结果','把主键是'.$pid.'的最后认证通过时间修改了'.$today);
                    }else
                    {
                        $data->save();
                    }

                    $this->system_log('修改指静脉认证备注','把主键是'.$pid.'的备注改成了<'.$cond.'>');

                    return ['error'=>'0','msg'=>'修改成功'];

                }else
                {
                    return ['error'=>'1','msg'=>'查询数据失败'];
                }

                break;

            case 'modify_info':

                $cond=trim(Input::get('cond1'));
                $cust_review_flag=Input::get('cond2');
                $vv_or_fv=Input::get('cond3');

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
                if ($vv_or_fv=='1')
                {
                    //要查询的是声纹
                    if (isset($phone))
                    {
                        $where=['cust_review_num'=>$phone,'cust_review_flag'=>$cust_review_flag];
                    }else
                    {
                        $where=['cust_id'=>$id,'cust_review_flag'=>$cust_review_flag];
                    }

                    //开始查询
                    $res=CustModel::where($where)->get()->toArray();

                }elseif ($vv_or_fv=='2')
                {
                    //要查询的是指静脉
                    if (isset($phone))
                    {
                        return ['error'=>'1','msg'=>'查询指静脉客户必须用身份证号码'];
                    }else
                    {
                        $where=['cust_id'=>$id];
                    }

                    //开始查询
                    $res=CustFVModel::where($where)->get()->toArray();

                }else
                {

                }

                if (empty($res))
                {
                    return ['error'=>'1','msg'=>'查无结果'];
                }elseif ($vv_or_fv=='1')
                {
                    $nbsp='&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp';
                    $data['客户姓名']='<a id=modify_cust_name>'.$res[0]['cust_name'].'</a>';
                    $data['身份证号']='<a id=modify_cust_id>'.$res[0]['cust_id'].'</a>';
                    $data['社保编号']='<a id=modify_cust_si_id>'.$res[0]['cust_si_id'].'</a>';
                    $data['年审号码']='<a id=modify_cust_review_num>'.$res[0]['cust_review_num'].'</a>';
                    $data['备用号码']='<a id=modify_cust_phone_num>'.$res[0]['cust_phone_num'].'</a>';
                    $data['客户地址']='<a id=modify_cust_address>'.$res[0]['cust_address'].'</a>';

                    //组成一下所有父节点都有的地区名称
                    $place=implode('-',array_reverse($this->select_allproject_parent(ProjectModel::find($res[0]['cust_project'])->project_id)));
                    $data['所属区域']='<a id=modify_cust_project>'.$place.'</a>';

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

                    return ['error'=>'0','msg'=>'查询成功','data'=>$data,'idcard_picture'=>file_get_contents(storage_path('app/IDcard_picture/'.$res[0]['cust_id']))];

                }elseif ($vv_or_fv=='2')
                {
                    $nbsp='&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp';
                    $data['客户姓名']='<a id=modify_cust_name>'.$res[0]['cust_name'].'</a>';
                    $data['身份证号']='<a id=modify_cust_id>'.$res[0]['cust_id'].'</a>';
                    $data['社保编号']='<a id=modify_cust_si_id>'.$res[0]['cust_si_id'].'</a>';
                    $data['手机号码']='<a id=modify_cust_phone_num>'.$res[0]['cust_phone_num'].'</a>';
                    $data['备用号码']='<a id=modify_cust_phone_bku>'.$res[0]['cust_phone_bku'].'</a>';

                    //从mongo中的到客户采集的是哪个手指
                    $mongo=$this->mymongo();
                    $mongo_res=$mongo->Finger->CustTemplate->find(['_id'=>$res[0]['cust_num']]);
                    foreach ($mongo_res as $row)
                    {
                        $mongo_res=$row;
                    }
                    $collectioned=null;
                    for ($i=0;$i<=9;$i++)
                    {
                        if ($mongo_res['Finger_'.$i]!='')
                        {
                            $collectioned.="<span style='width: 100px;margin-left: 2px;' class='btn btn-success btn-sm'>".$this->get_finger_name($i)."</span>";
                        }
                    }
                    $data['已采手指']=$collectioned;
                    $data['客户地址']='<a id=modify_cust_address>'.$res[0]['cust_address'].'</a>';

                    //组成一下所有父节点都有的地区名称
                    $place=implode('-',array_reverse($this->select_allproject_parent(ProjectModel::find($res[0]['cust_project'])->project_id)));
                    $data['所属区域']='<a id=modify_cust_project>'.$place.'</a>';

                    $data['参保类型']='<a id=modify_cust_si_type>'.SiTypeModel::find($res[0]['cust_si_type'])->si_name.'</a>';
                    $data['创建时间']='<a id=modify_created_at>'.$res[0]['created_at'].'</a>';
                    $data['唯一主键']='<a id=modify_pid value='.$res[0]['cust_num'].'>'.$res[0]['cust_num'].'</a>';
                    if ($res[0]['cust_death_flag']=='1')
                    {
                        $data['更多操作']='<a class="btn btn-danger" id=cust_delete_btn>删除该客户</a>'.$nbsp.'<a class="btn btn-info" id=cust_restore_btn>恢复认证状态</a>';
                    }else
                    {
                        $data['更多操作']='<a class="btn btn-danger" id=cust_delete_btn>删除该客户</a>'.$nbsp.'<a class="btn btn-warning" id=cust_death_btn>设成去世状态</a>';
                    }

                    return ['error'=>'0','msg'=>'查询成功','data'=>$data,'idcard_picture'=>file_get_contents(storage_path('app/IDcard_picture/'.$res[0]['cust_id']))];

                }else
                {

                }

                break;

            case 'modify_cust_name':

                $name=Input::get('key');
                $pid=Input::get('pid');

                if (Input::get('stype')!='')
                {
                    if (Input::get('stype')=='1')
                    {
                        //声纹
                        $res=CustModel::find($pid);

                        $res->cust_name=$name;
                        $res->save();

                        $this->system_log('修改声纹客户姓名','主键:'.$pid.'修改内容:'.$res->cust_name.'=>'.$name);

                        return ['error'=>'0','msg'=>'修改成功'];

                    }elseif (Input::get('stype')=='2')
                    {
                        //指静脉
                        $res=CustFVModel::find($pid);

                        $res->cust_name=$name;
                        $res->save();

                        $this->system_log('修改指静脉客户姓名','主键:'.$pid.'修改内容:'.$res->cust_name.'=>'.$name);

                        return ['error'=>'0','msg'=>'修改成功'];

                    }else
                    {

                    }
                }else
                {
                    return ['error'=>'1','msg'=>'请重新选一下声纹还是指静脉'];
                }

                break;

            case 'modify_cust_id':

                $id=Input::get('key');
                $pid=Input::get('pid');

                if (!$this->is_idcard($id))
                {
                    return ['error'=>'1','msg'=>'身份证输入不正确'];
                }

                if (Input::get('stype')!='')
                {
                    if (Input::get('stype')=='1')
                    {
                        //声纹
                        $res=CustModel::where(['cust_id'=>$id])->get()->toArray();

                        if (!empty($res))
                        {
                            return ['error'=>'1','msg'=>'身份证已存在，修改失败'];
                        }

                        $res=CustModel::find($pid);
                        $this->system_log('修改声纹客户身份证','主键:'.$pid.'修改内容:'.$res->cust_id.'=>'.$id);
                        $res->cust_id=$id;
                        $res->save();

                        return ['error'=>'0','msg'=>'修改成功'];

                    }elseif (Input::get('stype')=='2')
                    {
                        //指静脉
                        $res=CustFVModel::where(['cust_id'=>$id])->get()->toArray();

                        if (!empty($res))
                        {
                            return ['error'=>'1','msg'=>'身份证已存在，修改失败'];
                        }

                        $res=CustFVModel::find($pid);
                        $this->system_log('修改指静脉客户身份证','主键:'.$pid.'修改内容:'.$res->cust_id.'=>'.$id);
                        $res->cust_id=$id;
                        $res->save();

                        return ['error'=>'0','msg'=>'修改成功'];

                    }else
                    {

                    }
                }else
                {
                    return ['error'=>'1','msg'=>'请重新选一下声纹还是指静脉'];
                }

                break;

            case 'modify_cust_si_id':

                $id=Input::get('key');
                $pid=Input::get('pid');

                if (Input::get('stype')!='')
                {
                    if (Input::get('stype')=='1')
                    {
                        //声纹
                        if ($id=='')
                        {
                            $res=CustModel::find($pid);
                            $this->system_log('修改声纹客户社保编号','主键:'.$pid.'修改内容:'.$res->cust_si_id.'=>'.$id);
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
                            $this->system_log('修改声纹客户社保编号','主键:'.$pid.'修改内容:'.$res->cust_si_id.'=>'.$id);
                            $res->cust_si_id=$id;
                            $res->save();

                            return ['error'=>'0','msg'=>'修改成功'];
                        }

                    }elseif (Input::get('stype')=='2')
                    {
                        //指静脉
                        if ($id=='')
                        {
                            $res=CustFVModel::find($pid);
                            $this->system_log('修改指静脉客户社保编号','主键:'.$pid.'修改内容:'.$res->cust_si_id.'=>'.$id);
                            $res->cust_si_id=$id;
                            $res->save();

                            return ['error'=>'0','msg'=>'修改成功'];
                        }else
                        {
                            $res=CustFVModel::where(['cust_si_id'=>$id])->get()->toArray();

                            if (!empty($res))
                            {
                                return ['error'=>'1','msg'=>'社保编号已存在，修改失败'];
                            }

                            $res=CustFVModel::find($pid);
                            $this->system_log('修改指静脉客户社保编号','主键:'.$pid.'修改内容:'.$res->cust_si_id.'=>'.$id);
                            $res->cust_si_id=$id;
                            $res->save();

                            return ['error'=>'0','msg'=>'修改成功'];
                        }

                    }else
                    {

                    }
                }else
                {
                    return ['error'=>'1','msg'=>'请重新选一下声纹还是指静脉'];
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
                    //修改linux文件名
                    $this->voice_file_ModifyOrDelete($pid,'modify',['phone'=>$phone]);

                    //修改数据库中的年审号码
                    $res=CustModel::find($pid);
                    $this->system_log('修改年审号码','主键:'.$pid.'修改内容:'.$res->cust_review_num.'=>'.$phone);
                    //保存一下电话号码，修改下一个客户用，如果有的话***********
                    $old=$res->cust_review_num;
                    $new=$phone;
                    //******************************************************
                    $res->cust_review_num=$phone;
                    $res->save();

                    //修改另一个客户，如果有的话
                    $res=CustModel::where(['cust_review_num'=>$old])->first();
                    if (!empty($res))
                    {
                        $res->cust_review_num=$new;
                        $res->save();
                        $this->system_log('修改年审号码','主键:'.$res->cust_num.'修改内容:'.$old.'=>'.$new);
                    }

                    //修改vocalprint表中的数据，把电话号码改成新的
                    $sql='replace(vp_ivr_url,'.$old.','.$new.')';
                    \DB::table('vocalprint')->update(['vp_ivr_url'=>\DB::raw($sql)]);
                    $sql='replace(vp_model_url,'.$old.','.$new.')';
                    \DB::table('vocalprint')->update(['vp_model_url'=>\DB::raw($sql)]);

                    return ['error'=>'0','msg'=>'修改成功'];
                }

                break;

            case 'modify_cust_phone_num':

                $phone=Input::get('key');
                $pid=Input::get('pid');

                if (Input::get('stype')!='')
                {
                    if (Input::get('stype')=='1')
                    {
                        //声纹
                        $res=CustModel::find($pid);
                        $this->system_log('修改声纹客户备用号码','主键:'.$pid.'修改内容:'.$res->cust_phone_num.'=>'.$phone);
                        $res->cust_phone_num=$phone;
                        $res->save();

                        return ['error'=>'0','msg'=>'修改成功'];

                    }elseif (Input::get('stype')=='2')
                    {
                        //指静脉
                        $res=CustFVModel::find($pid);
                        $this->system_log('修改指静脉客户手机号码','主键:'.$pid.'修改内容:'.$res->cust_phone_num.'=>'.$phone);
                        $res->cust_phone_num=$phone;
                        $res->save();

                        return ['error'=>'0','msg'=>'修改成功'];

                    }else
                    {

                    }
                }else
                {
                    return ['error'=>'1','msg'=>'请重新选一下声纹还是指静脉'];
                }

                break;

            case 'modify_cust_address':

                $addr=Input::get('key');
                $pid=Input::get('pid');

                if (Input::get('stype')!='')
                {
                    if (Input::get('stype')=='1')
                    {
                        //声纹
                        $res=CustModel::find($pid);
                        $this->system_log('修改声纹客户地址','主键:'.$pid.'修改内容:'.$res->cust_address.'=>'.$addr);
                        $res->cust_address=$addr;
                        $res->save();

                        return ['error'=>'0','msg'=>'修改成功'];

                    }elseif (Input::get('stype')=='2')
                    {
                        //指静脉
                        $res=CustFVModel::find($pid);
                        $this->system_log('修改指静脉客户地址','主键:'.$pid.'修改内容:'.$res->cust_address.'=>'.$addr);
                        $res->cust_address=$addr;
                        $res->save();

                        return ['error'=>'0','msg'=>'修改成功'];

                    }else
                    {

                    }
                }else
                {
                    return ['error'=>'1','msg'=>'请重新选一下声纹还是指静脉'];
                }

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

                if (Input::get('stype')!='')
                {
                    if (Input::get('stype')=='1')
                    {
                        //声纹
                        $res=CustModel::find($pid);
                        $this->system_log('修改声纹客户属地','主键:'.$pid.'修改内容:'.$res->cust_project.'=>'.$proj);
                        $res->cust_project=$proj;
                        $res->save();

                        return ['error'=>'0','msg'=>'修改成功'];

                    }elseif (Input::get('stype')=='2')
                    {
                        //指静脉
                        $res=CustFVModel::find($pid);
                        $this->system_log('修改指静脉客户属地','主键:'.$pid.'修改内容:'.$res->cust_project.'=>'.$proj);
                        $res->cust_project=$proj;
                        $res->save();

                        return ['error'=>'0','msg'=>'修改成功'];

                    }else
                    {

                    }
                }else
                {
                    return ['error'=>'1','msg'=>'请重新选一下声纹还是指静脉'];
                }

                break;

            case 'modify_cust_si_type':

                $si=Input::get('key');
                $pid=Input::get('pid');

                if (Input::get('stype')!='')
                {
                    if (Input::get('stype')=='1')
                    {
                        //声纹
                        $res=CustModel::find($pid);
                        $this->system_log('修改声纹客户参保类型','主键:'.$pid.'修改内容:'.$res->cust_si_type.'=>'.$si);
                        $res->cust_si_type=$si;
                        $res->save();

                        return ['error'=>'0','msg'=>'修改成功'];

                    }elseif (Input::get('stype')=='2')
                    {
                        //指静脉
                        $res=CustFVModel::find($pid);
                        $this->system_log('修改指静脉客户参保类型','主键:'.$pid.'修改内容:'.$res->cust_si_type.'=>'.$si);
                        $res->cust_si_type=$si;
                        $res->save();

                        return ['error'=>'0','msg'=>'修改成功'];

                    }else
                    {

                    }
                }else
                {
                    return ['error'=>'1','msg'=>'请重新选一下声纹还是指静脉'];
                }

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

                //明静大人老随意删数据，所以我决定加一个权限
                $user=Session::get('user');
                $level=explode(',',$user[0]['staff_level']);
                $can_i_delete=false;
                foreach ($level as $row)
                {
                    try
                    {
                        $model=LevelModel::findOrFail($row);
                    }catch (ModelNotFoundException $e)
                    {
                        continue;
                    }

                    if ($model->level_name=='删除客户信息')
                    {
                        $can_i_delete=true;
                    }
                }

                if (!$can_i_delete)
                {
                    return ['error'=>'1','msg'=>'您没有删除客户的权限'];
                }

                $pid=Input::get('pid');

                //分辨是声纹还是指静脉
                if (Input::get('stype')!='')
                {
                    if (Input::get('stype')=='1')
                    {
                        //声纹
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

                        $this->system_log('删除声纹客户信息','主键:'.$pid);

                        CustDeleteModel::create($res->toArray());

                        $res->delete();

                        VocalPrintModel::where(['vp_id'=>$pid])->delete();

                        return ['error'=>'0','msg'=>'删除成功'];

                    }elseif (Input::get('stype')=='2')
                    {
                        //指静脉
                        $res=CustFVModel::find($pid);

                        $res->delete();

                        $this->system_log('删除声纹客户信息','主键:'.$pid);

                        return ['error'=>'0','msg'=>'删除成功'];

                    }else
                    {

                    }

                }else
                {
                    return ['error'=>'1','msg'=>'请重新选一下声纹还是指静脉'];
                }

                break;

            case 'modify_cust_death':

                if (Input::get('stype')!='')
                {
                    if (Input::get('stype')=='1')
                    {
                        //声纹
                        $pid=Input::get('pid');

                        $res=CustModel::find($pid);

                        $res->cust_death_flag='1';
                        $res->save();

                        $this->system_log('设置声纹客户为去世状态','主键:'.$pid);

                        return ['error'=>'0','msg'=>'设置成功'];

                    }elseif (Input::get('stype')=='2')
                    {
                        //指静脉
                        $pid=Input::get('pid');

                        $res=CustFVModel::find($pid);

                        $res->cust_death_flag='1';
                        $res->save();

                        $this->system_log('设置指静脉客户为去世状态','主键:'.$pid);

                        return ['error'=>'0','msg'=>'设置成功'];

                    }else
                    {

                    }
                }else
                {
                    return ['error'=>'1','msg'=>'请重新选一下声纹还是指静脉'];
                }

                break;

            case 'modify_cust_restore':

                if (Input::get('stype')!='')
                {
                    if (Input::get('stype')=='1')
                    {
                        //声纹
                        $pid=Input::get('pid');

                        $res=CustModel::find($pid);

                        $res->cust_death_flag='0';
                        $res->save();

                        $this->system_log('设置声纹客户为认证状态','主键:'.$pid);

                        return ['error'=>'0','msg'=>'设置成功'];

                    }elseif (Input::get('stype')=='2')
                    {
                        //指静脉
                        $pid=Input::get('pid');

                        $res=CustFVModel::find($pid);

                        $res->cust_death_flag='0';
                        $res->save();

                        $this->system_log('设置指静脉客户为认证状态','主键:'.$pid);

                        return ['error'=>'0','msg'=>'设置成功'];

                    }else
                    {

                    }
                }else
                {
                    return ['error'=>'1','msg'=>'请重新选一下声纹还是指静脉'];
                }

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
                        foreach (json_decode($row['value'],true) as $id)
                        {
                            if (!$id['id']=='0')
                            {
                                $p[]=$id;
                            }
                        }
                        $staff_project=$this->arr2str_new($p);
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
                        foreach (json_decode($row['value'],true) as $id)
                        {
                            if (!$id['id']=='0')
                            {
                                $s[]=$id;
                            }
                        }
                        $staff_si_type=$this->arr2str_new($s);
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
                        foreach (json_decode($row['value'],true) as $id)
                        {
                            if (!$id['id']=='0')
                            {
                                $l[]=$id;
                            }
                        }
                        $staff_level=$this->arr2str_new($l);
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

                //明静大人老随意删数据，所以我决定加一个权限
                $level=$this->get_data_in_session('staff_level');
                $can_i_delete=false;
                foreach (explode(',',$level) as $row)
                {
                    try
                    {
                        $model=LevelModel::findOrFail($row);
                    }catch (ModelNotFoundException $e)
                    {
                        continue;
                    }

                    if ($model->level_name=='删除客户语音')
                    {
                        $can_i_delete=true;
                    }
                }

                if (!$can_i_delete)
                {
                    return ['error'=>'1','msg'=>'您没有删除语音的权限'];
                }

                $res=CustModel::find(Input::get('key'));

                $res->cust_register_flag='0';
                $res->save();

                return ['error'=>'0','msg'=>'可以重新注册了'];

                break;

            case 'get_config':

                if (Redis::get('ivr_verify_score_threshold')=='')
                {
                    $this->redis_set('ivr_verify_score_threshold','15');
                }else
                {
                    $ivr_verify_score_threshold=Redis::get('ivr_verify_score_threshold');
                }

                if (Redis::get('ivr_max_lines')=='')
                {
                    $this->redis_set('ivr_max_lines','30');
                }else
                {
                    $ivr_max_lines=Redis::get('ivr_max_lines');
                }

                if (Redis::get('ivr_outgoing_pool_size')=='')
                {
                    $this->redis_set('ivr_outgoing_pool_size','20');
                }else
                {
                    $ivr_outgoing_pool_size=Redis::get('ivr_outgoing_pool_size');
                }

                if (Redis::get('ivr_verify_record_time')=='')
                {
                    $this->redis_set('ivr_verify_record_time','30');
                }else
                {
                    $ivr_verify_record_time=Redis::get('ivr_verify_record_time');
                }

                if (Redis::get('ivr_register_record_time')=='')
                {
                    $this->redis_set('ivr_register_record_time','60');
                }else
                {
                    $ivr_register_record_time=Redis::get('ivr_register_record_time');
                }

                if (Redis::get('ivr_vpr_silence_hits')=='')
                {
                    $this->redis_set('ivr_vpr_silence_hits','1');
                }else
                {
                    $ivr_vpr_silence_hits=Redis::get('ivr_vpr_silence_hits');
                }

                return [
                    'error'=>'0',
                    'DynamicPassword'=>Config::get('confirm_type.repeat'),
                    'TextDependent'=>Config::get('confirm_type.text'),
                    'ivr_verify_score_threshold'=>$ivr_verify_score_threshold,
                    'ivr_max_lines'=>$ivr_max_lines,
                    'ivr_outgoing_pool_size'=>$ivr_outgoing_pool_size,
                    'ivr_verify_record_time'=>$ivr_verify_record_time,
                    'ivr_register_record_time'=>$ivr_register_record_time,
                    'ivr_vpr_silence_hits'=>$ivr_vpr_silence_hits
                ];

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

            case 'set_config_for_ivr':

                if (Input::get('modify_or_default')=='0')
                {
                    $this->redis_set('ivr_verify_score_threshold','15');
                    $this->redis_set('ivr_max_lines','30');
                    $this->redis_set('ivr_outgoing_pool_size','20');
                    $this->redis_set('ivr_verify_record_time','30');
                    $this->redis_set('ivr_register_record_time','60');
                    $this->redis_set('ivr_vpr_silence_hits','1');

                    $cond=[
                        'verify_score_threshold'=>'15',
                        'max_lines'=>'30',
                        'outgoing_pool_size'=>'20',
                        'verify_record_time'=>'30',
                        'register_record_time'=>'60',
                        'vpr_silence_hits'=>'1'
                    ];

                }elseif (Input::get('modify_or_default')=='1')
                {
                    foreach (Input::get('key') as $row)
                    {
                        if (is_numeric($row['value']) && strstr($row['value'],'.')===false)
                        {
                            $this->redis_set('ivr_'.$row['name'],(string)$row['value']);

                            $cond[$row['name']]=$row['value'];
                        }else
                        {
                            return ['error'=>'1','msg'=>'输入错误，必须是正整数'];
                        }
                    }

                }else
                {

                }

                //发送修改请求
                $curl_res=$this->mycurl('http://127.0.0.1:7510/config_vars',$cond);
                //$curl_res=$this->mycurl('http://58.19.253.212:7510/config_vars',$cond);

                //判断返回值
                if ($curl_res['error']!='0')
                {
                    return ['error'=>'1','msg'=>'ivr修改错误'];
                }

                return ['error'=>'0','msg'=>'ivr修改成功'];

                break;

            case 'select_china_all_position':

                //取得查询条件
                $cond='';
                foreach (Input::get('key') as $row)
                {
                    //取得省
                    if ($row['name']=='province_name' && $row['value']!='')
                    {
                        $cond['province_name']=$row['value'];
                    }

                    //取得市
                    if ($row['name']=='city_name' && $row['value']!='')
                    {
                        $cond['city_name']=$row['value'];
                    }

                    //取得县
                    if ($row['name']=='county_name' && $row['value']!='')
                    {
                        $cond['county_name']=$row['value'];
                    }

                    //取得镇
                    if ($row['name']=='town_name' && $row['value']!='')
                    {
                        $cond['town_name']=$row['value'];
                    }

                    //取得村
                    if ($row['name']=='village_name' && $row['value']!='')
                    {
                        $cond['village_name']=$row['value'];
                    }
                }

                if ($cond=='')
                {
                    return ['error'=>'1','msg'=>'查询条件不能为空'];
                }else
                {

                }

                //一条一条查询
                $res_single='';
                foreach ($cond as $key=>$value)
                {
                    $res_single[$key]=ChinaAllPositionModel::where($key,'like','%'.$value.'%')
                        ->distinct()
                        ->get([$key])
                        ->toArray();
                }

                //省市县镇村
                $tmp=0;
                if (isset($cond['county_name']))
                {
                    $tmp++;
                    $deep['name'][]='county_name';
                    $deep['value'][]=$cond['county_name'];
                }
                if (isset($cond['town_name']))
                {
                    $tmp++;
                    $deep['name'][]='town_name';
                    $deep['value'][]=$cond['town_name'];
                }
                if (isset($cond['village_name']))
                {
                    $tmp++;
                    $deep['name'][]='village_name';
                    $deep['value'][]=$cond['village_name'];
                }

                //判断县镇村传了几个进来
                if (!$tmp=='0')
                {
                    if ($tmp=='1')
                    {
                        //传进来一个
                        $res_all=ChinaAllPositionModel::where($deep['name'][0],'like','%'.$deep['value'][0].'%')
                            ->distinct()
                            ->get([
                                'province_name','city_name','county_name','town_name','village_name'
                            ])
                            ->toArray();
                    }elseif ($tmp=='2')
                    {
                        //传进来两个
                        $res_all=ChinaAllPositionModel::where($deep['name'][0],'like','%'.$deep['value'][0].'%')
                            ->where($deep['name'][1],'like','%'.$deep['value'][1].'%')
                            ->distinct()
                            ->get([
                                'province_name','city_name','county_name','town_name','village_name'
                            ])
                            ->toArray();
                    }else
                    {
                        //传进来三个
                        $res_all=ChinaAllPositionModel::where($deep['name'][0],'like','%'.$deep['value'][0].'%')
                            ->where($deep['name'][1],'like','%'.$deep['value'][1].'%')
                            ->where($deep['name'][2],'like','%'.$deep['value'][2].'%')
                            ->distinct()
                            ->get([
                                'province_name','city_name','county_name','town_name','village_name'
                            ])
                            ->toArray();
                    }
                }else
                {
                    return ['error'=>'0','msg'=>'查询成功','res_single'=>$res_single];
                }

                //以下是用来导出地区的
                $for_redis[]=['province_name','city_name','county_name','town_name','village_name'];

                foreach ($res_all as $myrow)
                {
                    $for_redis[]=array_values($myrow);
                }

                $this->redis_set('project_for_redis',json_encode($for_redis),100);

                return ['error'=>'0','msg'=>'查询成功','res_single'=>$res_single,'res_all'=>$res_all];

                break;

            case 'get_show_system_log_data':

                //用户传入的页
                $now_page=Input::get('page');

                //每页显示几条数据
                $limit=15;

                //从第几条开始显示
                $offset=($now_page-1)*$limit;

                //查询数据
                $res=LogModel::orderBy('created_at','desc')->offset($offset)->limit($limit)->get([
                    'log_account',
                    'log_todo',
                    'log_detail',
                    'created_at',
                ])->toArray();

                //总页数
                $cnt=LogModel::count();
                $cnt_page=intval(ceil($cnt/$limit));

                return ['error'=>'0','data'=>$res,'pages'=>$cnt_page,'count_data'=>$cnt];

                break;

            case 'get_show_staff_list_data':

                //用户传入的页
                $now_page=Input::get('page');

                //每页显示几条数据
                $limit=15;

                //从第几条开始显示
                $offset=($now_page-1)*$limit;

                //查询数据
                $res=StaffModel::offset($offset)->limit($limit)->get([
                    'staff_account',
                    'staff_name',
                    'staff_project',
                    'staff_si_type',
                    'staff_level',
                    'allow_login'
                ])->toArray();

                //总页数
                $cnt=StaffModel::count();
                $cnt_page=intval(ceil($cnt/$limit));

                return ['error'=>'0','data'=>$res,'pages'=>$cnt_page,'count_data'=>$cnt];

                break;

            case 'set_redis':

                //拿到proj_id，遍历出父名称，然后存入redis
                $proj_id=Input::get('proj_id');

                while (1)
                {
                    $tmp=ProjectModel::find($proj_id);
                    if ($tmp->project_parent=='0')
                    {
                        $name[$tmp->project_id]=$tmp->project_name;
                        break;
                    }else
                    {
                        $name[$tmp->project_id]=$tmp->project_name;
                        $proj_id=$tmp->project_parent;
                    }
                }

                $value=implode('-',array_reverse($name));

                $key=Session::get('user');
                $key=Input::get('key').'_'.$key[0]['staff_account'];

                $this->redis_set($key,$value,Input::get('time'));
                $this->redis_set($key.'_',Input::get('proj_id'),Input::get('time'));

                return ['error'=>'0','value'=>$value,'id_in_mysql'=>Input::get('proj_id')];

                break;

            case 'get_redis':

                $key=Session::get('user');
                $key='chose_project_'.$key[0]['staff_account'];

                if (Redis::get($key)=='')
                {
                    return ['error'=>'1','res'=>'点击选择地区'];
                }else
                {
                    return ['error'=>'0','res'=>Redis::get($key),'res1'=>Redis::get($key.'_')];
                }

                break;

            case 'project_for_redis':

                file_get_contents(env('APP_URL').'/export1/project_for_redis');

                $excel_file=env('APP_URL').'/storage/exports/project_for_redis.xls';

                return ['error'=>'0','msg'=>'导出完成','file_name'=>$excel_file];

                break;

            case 'set_treeview_active':

                $id_in_html=Input::get('key');

                $user=Session::get('user');
                $user_id=$user[0]['staff_num'];

                //一开始进来时候是空，说明菜单是未选中状态
                if (Redis::get($id_in_html.'_'.$user_id)=='')
                {
                    //给菜单添加成选中状态
                    $this->redis_set($id_in_html.'_'.$user_id,'active');
                }else
                {
                    //当是选中时候再次点击，移除选中状态
                    $this->redis_set($id_in_html.'_'.$user_id,'');
                }

                return ['error'=>'0'];

                break;

            case 'get_treeview_active':

                //选择菜单中的id
                $now_we_get=[
                    'yong_hu_deng_ji',
                    'yong_hu_ren_zheng',
                    'ke_hu_guan_li',
                    'shu_ju_tong_ji',
                    'fen_xi',
                    'cao_zuo_ri_zhi',
                    'xi_tong_she_zhi',
                    'chao_ji_guan_li_yuan_gong_neng'
                ];

                $user=Session::get('user');
                $user_id=$user[0]['staff_num'];

                foreach ($now_we_get as $one)
                {
                    if (Redis::get($one.'_'.$user_id)=='')
                    {
                        $active[$one]='';
                    }else
                    {
                        $active[$one]=Redis::get($one.'_'.$user_id);
                    }
                }

                return ['error'=>'0','res'=>$active];

                break;

            case 'get_ip_address':

                $res=$this->is_local_IP_address($_SERVER['SERVER_ADDR']);

                return ['error'=>'0','data'=>$res];

                break;

            case 'import_confirm_result':

                foreach (Input::get('key') as $row)
                {
                    if ($row['name']=='cust_project')
                    {
                        $cust_project=$row['value'];
                    }

                    if ($row['name']=='cust_si_type')
                    {
                        $cust_si_type=$row['value'];
                    }

                    if ($row['name']=='cust_type')
                    {
                        $cust_type=$row['value'];
                    }

                    if ($row['name']=='import_type')
                    {
                        $import_type=$row['value'];
                    }

                    if ($row['name']=='star_date')
                    {
                        $star_date=$row['value'];
                    }

                    if ($row['name']=='stop_date')
                    {
                        $stop_date=$row['value'];
                    }
                }

                //得到当前结点的所有子节点
                foreach ($this->get_all_children($cust_project) as $row)
                {
                    $proj[]=$row['project_id'];
                }

                //得到了所有节点
                $proj[]=$cust_project;

                //根据条件查询所有客户
                if ($cust_type=='0')
                {
                    //选择为全部客户
                    $res_in_mysql=CustModel::whereIn('cust_project',$proj)->where('cust_si_type',$cust_si_type)->get()->toArray();
                }else
                {
                    //选择为A或者B
                    $res_in_mysql=CustModel::whereIn('cust_project',$proj)->where(['cust_si_type'=>$cust_si_type,'cust_type'=>$cust_type])->get()->toArray();
                }

                if (empty($res_in_mysql))
                {
                    return ['error'=>'1','msg'=>'没有匹配的数据，导出终止'];
                }

                //查询出社保提供导入客户信息
                //自动判断是第几级区域
                $check_project_level=ProjectModel::find($cust_project);
                $check_project_level=explode('-',$check_project_level->project_path);

                if (count($check_project_level)=='4')
                {
                    //第五级区域，对应village
                    $get_cond=['county_id','town_id','village_id'];

                }elseif (count($check_project_level)=='3')
                {
                    //第四级区域，对应town
                    $get_cond=['county_id','town_id'];

                }elseif (count($check_project_level)=='2')
                {
                    //第三级区域，对应county
                    $get_cond=['county_id'];

                }else
                {
                    //第二级，第一级，参数错误
                    return ['error'=>'1','msg'=>'只支持包括第三级及其以下区域，导出终止'];
                }

                //判断是登记，还是认证
                if ($import_type=='1')
                {
                    //说明要导出的是认证，就需要开始时间和结束时间
                    if ($star_date=='' || $stop_date=='')
                    {
                        return ['error'=>'1','msg'=>'请设置时间'];
                    }
                    $star_date=$star_date.' 00:00:00';
                    $stop_date=$stop_date.' 23:59:59';

                    //认证是自己的mysql数据间对比，登记是和社保导入数据对比
                    //**************************************************************************************************
                    //$res_in_mysql是当前区域下所有的客户信息，通过客户主键，在认证表里找到是否认证成功或者失败的信息
                    foreach ($res_in_mysql as $row)
                    {
                        //先拿到所有的客户主键
                        $cust_pid[]=$row['cust_num'];
                    }
                    $cust_pid=array_flatten($cust_pid);
                    $res_in_confirm_table=CustConfirmModel::whereIn('confirm_pid',$cust_pid)
                        ->wherebetween('created_at',[$star_date,$stop_date])
                        ->where('confirm_res','Y')->get(['confirm_pid'])->toArray();

                    $si_table=SiTypeModel::all();
                    foreach ($si_table as $key=>$obj)
                    {
                        $si_array[$obj->si_id]=$obj->si_name;
                    }

                    //得到了所有通过认证的客户id了
                    $is_pass_id=array_unique(array_flatten($res_in_confirm_table));

                    foreach ($cust_pid as $row1)
                    {
                        if (in_array($row1,$is_pass_id))
                        {
                            //通过了认证
                            foreach ($res_in_mysql as $row2)
                            {
                                if ($row2['cust_num']==$row1)
                                {
                                    $my_tmp_1['cust_name']=$row2['cust_name'];
                                    $my_tmp_1['cust_id']=$row2['cust_id'];
                                    $my_tmp_1['cust_si_type']=$si_array[$row2['cust_si_type']];
                                    $my_tmp_1['cust_si_id']=$row2['cust_si_id'];
                                    $my_tmp_1['cust_review_num']=$row2['cust_review_num'];
                                    $my_tmp_1['status']='通过认证';

                                    $my_dat_1[]=$my_tmp_1;
                                }
                            }
                        }else
                        {
                            //没通过认证
                            foreach ($res_in_mysql as $row3)
                            {
                                if ($row3['cust_num']==$row1)
                                {
                                    $my_tmp_1['cust_name']=$row3['cust_name'];
                                    $my_tmp_1['cust_id']=$row3['cust_id'];
                                    $my_tmp_1['cust_si_type']=$si_array[$row3['cust_si_type']];
                                    $my_tmp_1['cust_si_id']=$row3['cust_si_id'];
                                    $my_tmp_1['cust_review_num']=$row3['cust_review_num'];
                                    $my_tmp_1['status']='没有通过';

                                    $my_dat_1[]=$my_tmp_1;
                                }
                            }
                        }
                    }

                    //把数据放到redis集合
                    $time=time();
                    foreach ($my_dat_1 as $myrow)
                    {
                        Redis::lpush('import_confirm_YorN'.$time,json_encode($myrow));
                    }
                    Redis::expire('import_confirm_YorN'.$time,600);

                    file_get_contents(env('APP_URL').'/export2/'.'import_confirm_YorN'.$time);

                    $excel_file=env('APP_URL').'/storage/exports/'.'import_confirm_YorN'.$time.'.xls';

                    //以上是制作excel的代码，下面是分页代码
                    //用户传入的页
                    $now_page=Input::get('page');

                    //每页显示几条数据
                    $limit=12;

                    //从第几条开始显示
                    $offset=($now_page-1)*$limit;

                    //自制分页
                    $data='';
                    for ($i=$offset;$i<=$limit*$now_page-1;$i++)
                    {
                        if (!isset($my_dat_1[$i]))
                        {
                            break;
                        }

                        //为了符合前台页面显示，数组里的数据顺序需要改一下
                        $tmp['name']=$my_dat_1[$i]['cust_name'];
                        $tmp['idcard']=$my_dat_1[$i]['cust_id'];
                        $tmp['sitype']=$my_dat_1[$i]['cust_si_type'];
                        $tmp['sicard']=$my_dat_1[$i]['cust_si_id'];
                        $tmp['phone']=$my_dat_1[$i]['cust_review_num'];
                        $tmp['status']=$my_dat_1[$i]['status'];
                        $data[]=$tmp;
                    }

                    $cnt_page=intval(ceil(count($my_dat_1)/$limit));

                    return ['error'=>'0','msg'=>'查询成功','data'=>$data,'pages'=>$cnt_page,'count_data'=>count($my_dat_1),'filename'=>$excel_file];

                    //**************************************************************************************************
                }

                //随机产生一个人，拿到他的身份证，然后在导入的表格中找到对应的区域id
                $cond=$res_in_mysql[array_rand($res_in_mysql,1)];
                $cond=$cond['cust_id'];

                //区域代码
                $proj_id=SocialInsuranceModel::where('idcard',$cond)
                    ->get($get_cond)
                    ->toArray();

                //查出这个区域，这个参保类型的，所有社保导入数据中的数据
                $sitype=SiTypeModel::find($cust_si_type);
                $res_in_import=SocialInsuranceModel::where($proj_id[0])
                    ->where('sitype',$sitype->si_name)
                    ->get([
                        'county',
                        'town',
                        'village',
                        'name',
                        'idcard',
                        'sitype',
                        'sicard'
                    ])
                    ->toArray();

                //两边的数据都查询出来，现在开始对比
                foreach ($res_in_mysql as $row)
                {
                    //拿出所有身份证号
                    $idcard_in_mysql[]=$row['cust_id'];
                }

                //生成要导出的excel数据数组
                foreach ($res_in_import as &$row)
                {
                    if (in_array($row['idcard'],$idcard_in_mysql))
                    {
                        //说明参加采集了
                        $row['status']='已采集';
                    }else
                    {
                        //说明没参加采集
                        $row['status']='未采集';
                    }
                }
                unset($row);

                //把数据放到redis集合
                $time=time();
                foreach ($res_in_import as $myrow)
                {
                    Redis::lpush('import_register_YorN'.$time,json_encode($myrow));
                }
                Redis::expire('import_register_YorN'.$time,600);

                file_get_contents(env('APP_URL').'/export2/'.'import_register_YorN'.$time);

                $excel_file=env('APP_URL').'/storage/exports/'.'import_register_YorN'.$time.'.xls';

                //以上是制作excel的代码，下面是分页代码
                //用户传入的页
                $now_page=Input::get('page');

                //每页显示几条数据
                $limit=12;

                //从第几条开始显示
                $offset=($now_page-1)*$limit;

                //自制分页
                $data='';
                for ($i=$offset;$i<=$limit*$now_page-1;$i++)
                {
                    if (!isset($res_in_import[$i]))
                    {
                        break;
                    }

                    //为了符合前台页面显示，数组里的数据顺序需要改一下
                    $tmp['name']=$res_in_import[$i]['name'];
                    $tmp['idcard']=$res_in_import[$i]['idcard'];
                    $tmp['sitype']=$res_in_import[$i]['sitype'];
                    $tmp['sicard']=$res_in_import[$i]['sicard'];
                    $tmp['phone']=CustModel::where('cust_id',$res_in_import[$i]['idcard'])->get()[0]->cust_review_num;
                    $tmp['status']=$res_in_import[$i]['status'];
                    $data[]=$tmp;
                }

                $cnt_page=intval(ceil(count($res_in_import)/$limit));

                return ['error'=>'0','msg'=>'查询成功','data'=>$data,'pages'=>$cnt_page,'count_data'=>count($res_in_import),'filename'=>$excel_file];

                break;

            case 'get_wait_register_customer_info':

                if (!$this->is_idcard(Input::get('key')))
                {
                    return ['error'=>'1','msg'=>'不是一个有效的身份证'];
                }

                //查询是否有这个人的信息
                $res=SocialInsuranceModel::where('idcard',Input::get('key'))->get()->toArray();

                if (empty($res))
                {
                    return ['error'=>'1','msg'=>'系统中不含此人信息'];
                }else
                {
                    //查出来这个地区的中文
                    $str=explode('_',$res[0]['position_path']);

                    $projtmp1=ChinaAllPositionModel::where([
                        'county_id'=>$str[0],
                        'town_id'=>$str[1],
                        'village_id'=>$str[2]
                    ])->get();

                    //一会要对比的数组
                    $arr_saki=[
                        $projtmp1[0]->province_name,
                        $projtmp1[0]->city_name,
                        $projtmp1[0]->county_name,
                        $projtmp1[0]->town_name
                    ];

                    $projtmp2=ProjectModel::where([
                        'project_name'=>$projtmp1[0]->village_name
                    ])->get();

                    foreach ($projtmp2 as $row)
                    {
                        //在project表中，所有等于village_name的地名
                        //mysql表中储存了，该地区往上4级的地名pid，一个一个找，看看有没有符合的
                        $position=explode('-',$row->project_path);
                        $position=ProjectModel::whereIn('project_id',$position)->get();

                        //是不是都在数组里
                        $inarray_res=null;
                        foreach ($position as $inarray)
                        {
                            if (in_array($inarray->project_name,$arr_saki))
                            {
                                $inarray_res[]='yes';
                            }else
                            {
                                $inarray_res[]='no';
                            }
                        }

                        if (in_array('no',$inarray_res))
                        {
                            $res[0]['project_id']='';
                        }else
                        {
                            $res[0]['project_id']=$row->project_id;
                            break;
                        }
                    }

                    //如果没查到，返回   不存在
                    if ($res[0]['project_id']=='')
                    {
                        $res[0]['project_long_name']='未找到地区';
                    }else
                    {
                        $res[0]['project_long_name']=implode('-',$arr_saki);
                    }

                    return ['error'=>'0','data'=>$res[0],'msg'=>'查询成功'];
                }

                break;

            case 'get_wait_register_customer_fv_info':

                if (!$this->is_idcard(Input::get('key')))
                {
                    return ['error'=>'1','msg'=>'不是一个有效的身份证'];
                }

                //查询是否有这个人的信息
                $res=CustFVModel::where('cust_id',Input::get('key'))->get()->toArray();

                if (empty($res))
                {
                    return ['error'=>'1','msg'=>'此人未参加采集'];
                }else
                {
                    return ['error'=>'0','msg'=>'请开始认证'];
                }

                break;

            case 'get_finger_mongo_data':

                if (!$this->is_idcard(Input::get('key')))
                {
                    return ['error'=>'1','msg'=>'不是一个有效的身份证'];
                }

                //查询是否有这个人的信息
                $res=CustFVModel::where('cust_id',Input::get('key'))->get();

                if (empty($res->toArray()))
                {
                    return ['error'=>'0','msg'=>'新的客户，请开始采集'];
                }else
                {
                    //说明这个客户已经采集过了
                    //拿到客户主键，去mongo里查询指静脉和指纹信息
                    $mongo=$this->mymongo();
                    $pid=$res[0]->cust_num;

                    $res=$mongo->Finger->CustTemplate->find(['_id'=>$pid]);

                    foreach ($res as $row)
                    {
                        $data=$row;
                    }

                    $fv_id=null;
                    $fp_id='[';
                    $fv_tm=null;
                    $fp_tm='[';
                    for ($i=0;$i<=9;$i++)
                    {
                        if ($data['Finger_'.$i]!='')
                        {
                            $fv_id.=$i.'_0,'.$i.'_1,'.$i.'_2,';
                            $fv_tm.=$data['Finger_'.$i].',';
                            $fp_id.=$i.',';
                            $fp_tm.=$data['FingerPrint_'.$i].',';
                        }
                    }
                    $fv_id=substr($fv_id,0,strlen($fv_id)-1);

                    $fp_id=substr($fp_id,0,strlen($fp_id)-1);
                    $fp_id.=']';

                    $fv_tm=substr($fv_tm,0,strlen($fv_tm)-1);

                    $fp_tm=substr($fp_tm,0,strlen($fp_tm)-1);
                    $fp_tm.=']';

                    return ['error'=>'2','msg'=>'查到了此人指静脉信息','fv_id'=>$fv_id,'fv_tm'=>$fv_tm,'fp_id'=>$fp_id,'fp_tm'=>$fp_tm];
                }

                break;

            case 'get_login_user_name':

                if (Session::get('user')==null)
                {
                    return ['error'=>'1','msg'=>'您的登陆已经过期'];
                }else
                {
                    $data=Session::get('user');

                    $data=$data[0]['staff_name'];

                    return ['error'=>'0','data'=>$data,'msg'=>'取得成功'];
                }

                break;

            case 'change_allow_login':

                $type_id=Input::get("type_id");
                $staff_account=Input::get("staff_account");

                $staff=StaffModel::where("staff_account",$staff_account)->get();

                //type_id等于0，代表当前可登陆，要修改成不可登陆
                if ($type_id=='0')
                {
                    $staff[0]->update(['allow_login'=>'1']);
                    $staff[0]->save();
                }else if ($type_id=='1')
                {
                    $staff[0]->update(['allow_login'=>'0']);
                    $staff[0]->save();
                }else
                {

                }

                return ["error"=>"0","msg"=>"修改完成"];

                break;

            case 'add_fv_cust':

                $info=Input::get('key');
                $cust_photo=Input::get('cust_photo');

                //取出指静脉信息
                foreach ($info['fv_info'] as $row)
                {
                    if ($row['name']=='my_fvID')
                    {
                        $id=$row['value'];
                    }

                    if ($row['name']=='my_fvTemplate')
                    {
                        $template=$row['value'];
                    }
                    //上面是指静脉信息，下面是指纹信息
                    if ($row['name']=='my_fpID')
                    {
                        $fp_id=$row['value'];
                    }

                    if ($row['name']=='my_fpTemplate')
                    {
                        $fp_template=$row['value'];
                    }
                }

                //如果数据是空
                if (trim($id)=='' || trim($template)=='' || trim($fp_id)=='' || trim($fp_template)=='')
                {
                    return ['error'=>'1','msg'=>'未取得指静脉数据'];
                }

                $template=trim($template);
                $template=str_replace(["\r\n","\n"],'',$template);

                //注册指静脉
                $myfv=FingerRegister::getSingleton();
                $myfv->Register(explode(',',$id),explode(',',$template));

                //注册指纹
                $fp_id=str_replace(['[',']'],'',$fp_id);
                $fp_template=str_replace(['[',']'],'',$fp_template);
                $myfv->RegisterFP(explode(',',$fp_id),explode(',',$fp_template));

                //创建变量，储存指静脉模板
                foreach ($myfv->whichAttrHasData() as $key=>$value)
                {
                    //指静脉
                    $attr='Finger_'.$value;
                    $$attr=implode(',',$myfv->$attr);

                    //指纹
                    $attr1='FingerPrint_'.$value;
                    $$attr1=$myfv->$attr1;
                }

                //取出要添加的用户信息
                foreach ($info['cust_info'] as $row)
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
                        if (count(CustFVModel::where(['cust_id'=>$row['value']])->get()->toArray())!='0')
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

                    //手机号码
                    if ($row['name']=='cust_phone_num')
                    {
                        if (!$this->check_something($row['value'],'phonenumber',null))
                        {
                            return ['error'=>'1','msg'=>'手机号码输入不正确'];
                        }

                        $cust_info['cust_phone_num']=$row['value'];
                    }

                    //备用手机号
                    if ($row['name']=='cust_phone_bku')
                    {
                        //不验证了

                        $cust_info['cust_phone_bku']=trim($row['value']);
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
                        if ($row['value']=='')
                        {
                            return ['error'=>'1','msg'=>'所属地区已经过期，请重新选择'];
                        }else
                        {
                            if (!$this->before_insert_check_projectlevel($row['value']))
                            {
                                return ['error'=>'1','msg'=>'您没有该地区的采集权限'];
                            }

                            $cust_info['cust_project']=$row['value'];
                        }
                    }

                    //参保类型
                    if ($row['name']=='cust_si_type')
                    {
                        $res=SiTypeModel::where(['si_name'=>$row['value']])->pluck('si_id')->toArray();

                        $cust_info['cust_si_type']=$res[0];
                    }
                }

                //设置成为为死亡
                $cust_info['cust_death_flag']='0';

                //设置最后一次认证成功时间
                $cust_info['cust_last_confirm_date']=date('Y-m-d',time());

                $model=CustFVModel::create($cust_info);

                //储存用户的身份证头像
                Storage::disk('IDcard')->put($model->cust_id,$cust_photo);

                //指静脉信息
                //$id是fv的id
                //$template是模板

                //把指静脉和指纹信息存储到mongo里
                $obj=$this->mymongo();
                $obj->Finger->CustTemplate->insert([
                    '_id'=>$model->cust_num,
                    'Finger_0'=>isset($Finger_0)?$Finger_0:'',
                    'Finger_1'=>isset($Finger_1)?$Finger_1:'',
                    'Finger_2'=>isset($Finger_2)?$Finger_2:'',
                    'Finger_3'=>isset($Finger_3)?$Finger_3:'',
                    'Finger_4'=>isset($Finger_4)?$Finger_4:'',
                    'Finger_5'=>isset($Finger_5)?$Finger_5:'',
                    'Finger_6'=>isset($Finger_6)?$Finger_6:'',
                    'Finger_7'=>isset($Finger_7)?$Finger_7:'',
                    'Finger_8'=>isset($Finger_8)?$Finger_8:'',
                    'Finger_9'=>isset($Finger_9)?$Finger_9:'',
                    'FingerPrint_0'=>isset($FingerPrint_0)?$FingerPrint_0:'',
                    'FingerPrint_1'=>isset($FingerPrint_1)?$FingerPrint_1:'',
                    'FingerPrint_2'=>isset($FingerPrint_2)?$FingerPrint_2:'',
                    'FingerPrint_3'=>isset($FingerPrint_3)?$FingerPrint_3:'',
                    'FingerPrint_4'=>isset($FingerPrint_4)?$FingerPrint_4:'',
                    'FingerPrint_5'=>isset($FingerPrint_5)?$FingerPrint_5:'',
                    'FingerPrint_6'=>isset($FingerPrint_6)?$FingerPrint_6:'',
                    'FingerPrint_7'=>isset($FingerPrint_7)?$FingerPrint_7:'',
                    'FingerPrint_8'=>isset($FingerPrint_8)?$FingerPrint_8:'',
                    'FingerPrint_9'=>isset($FingerPrint_9)?$FingerPrint_9:'',
                    'time'=>time()
                ]);

                return ['error'=>'0','msg'=>'登记成功'];

                break;

            case 'modify_fv_class_attr':

                foreach (Input::get('key') as $row)
                {
                    if ($row['name']=='my_fvID')
                    {
                        $id=$row['value'];
                    }

                    if ($row['name']=='my_fvTemplate')
                    {
                        $template=$row['value'];
                    }
                    //上面是指静脉信息，下面是指纹信息
                    if ($row['name']=='my_fpID')
                    {
                        $fp_id=$row['value'];
                    }

                    if ($row['name']=='my_fpTemplate')
                    {
                        $fp_template=$row['value'];
                    }
                }

                //如果数据是空
                if (trim($id)=='' || trim($template)=='' || trim($fp_id)=='' || trim($fp_template)=='')
                {
                    return ['error'=>'1','msg'=>'等待指静脉数据'];
                }

                $template=trim($template);
                $template=str_replace(["\r\n","\n"],'',$template);

                //实时更新前台采集的数据
                $obj_for_fv=FingerRegister::getSingleton();
                $obj_for_fv->Register(explode(',',$id),explode(',',$template));

                //把前台传过来的数据加工一下后，放到类里
                $fp_id=str_replace(['[',']'],'',$fp_id);
                $fp_template=str_replace(['[',']'],'',$fp_template);
                $obj_for_fv->RegisterFP(explode(',',$fp_id),explode(',',$fp_template));

                return ['error'=>'0','data'=>$obj_for_fv->attrToChinese($obj_for_fv)];

                break;

            case 'fv_match':

                foreach (Input::get('key') as $row)
                {
                    if ($row['name']=='my_fvTemplate')
                    {
                        $fv_template=trim($row['value']);
                    }
                    if ($row['name']=='my_fpTemplate')
                    {
                        $fp_template=trim($row['value']);
                    }
                }

                //如果数据是空
                if (trim($fv_template)=='' || trim($fp_template)=='' || trim(Input::get('cust_id'))=='')
                {
                    return ['error'=>'3','msg'=>'等待数据'];
                }

                if (!$this->is_idcard(trim(Input::get('cust_id'))))
                {
                    return ['error'=>'3','msg'=>'身份证输入不正确'];
                }

                $cust_pid=CustFVModel::where('cust_id',trim(Input::get('cust_id')))->first();

                $data=[
                    'pid'=>(string)$cust_pid->cust_num,//用户的主键号
                    'fv'=>$fv_template,//当前采集的客户指静脉
                    'fp'=>$fp_template,//当前采集的客户指纹
                    'fvs'=>(string)Config::get('constant.fingervenascore'),//指静脉阈值
                    'fps'=>(string)Config::get('constant.fingerprintscore')//指纹阈值
                ];

                $curl_res=$this->mycurl('http://127.0.0.1:7510/fingervena',$data);
                //$curl_res=$this->mycurl('http://58.19.253.212:7510/fingervena',$data);

                //判断返回值
                if ($curl_res['error']!='0')
                {
                    return ['error'=>'1','msg'=>'指静脉SDK故障，请求被拒绝'];
                }

                $curl_res=json_decode($curl_res['msg'],true);

                if ($curl_res['fv']['result']=='true' || $curl_res['fp']['result']=='true')
                {
                    //得到fno
                    if ($curl_res['fv']['result']=='true')
                    {
                        $fno=substr($curl_res['fv']['match'],-1);
                    }elseif ($curl_res['fp']['result']=='true')
                    {
                        $fno=substr($curl_res['fp']['match'],-1);
                    }else
                    {

                    }

                    //修改mysql中，该客户的最后认证时间
                    $model=CustFVModel::find($curl_res['pid']);
                    $model->cust_last_confirm_date=date('Y-m-d',time());
                    $model->save();

                    //结果放到mongo里
                    $mongo=$this->mymongo();
                    $mongo->Finger->ConfirmRes->insert([
                        'id_in_mysql'=>$curl_res['pid'],//mysql表中的客户主键
                        'res_of_fv'=>$curl_res['fv']['result'],//指静脉的对比结果
                        'res_of_fp'=>$curl_res['fp']['result'],//指纹的对比分数
                        'fno'=>$fno,//该次认证的哪个手指
                        'sno'=>$this->get_data_in_session('staff_num'),//操作认证的员工主键(mysql)
                        'time'=>date('Ymd',time()),//该条数据的插入时间
                        'sort'=>time()//用来排序的时间
                    ]);

                    return ['error'=>'0','msg'=>'认证成功'];

                }elseif ($curl_res['fv']['result']=='false' || $curl_res['fp']['result']=='false')
                {
                    $fno='99';

                    //结果放到mongo里
                    $mongo=$this->mymongo();
                    $mongo->Finger->ConfirmRes->insert([
                        'id_in_mysql'=>$curl_res['pid'],//mysql表中的主键
                        'res_of_fv'=>$curl_res['fv']['result'],//指静脉的对比结果
                        'res_of_fp'=>$curl_res['fp']['result'],//指纹的对比分数
                        'fno'=>$fno,//该次认证的哪个手指
                        'sno'=>$this->get_data_in_session('staff_num'),//操作认证的员工主键(mysql)
                        'time'=>date('Ymd',time()),//该条数据的插入时间
                        'sort'=>time()//用来排序的时间
                    ]);

                    return ['error'=>'0','msg'=>'认证失败'];

                }elseif ($curl_res['fv']['result']=='error' || $curl_res['fp']['result']=='error')
                {
                    return ['error'=>'1','msg'=>'指静脉SDK故障，接口调用失败'];
                }else
                {

                }

                return ['error'=>'0','msg'=>'未知的错误'];

                break;

            case 'fv_match_refresh':

                $getMongo=$this->mymongo();

                //用户传入的页
                $now_page=Input::get('page');

                //每页显示几条数据
                $limit=3;

                //从第几条开始显示
                $offset=($now_page-1)*$limit;

                //查询当天的数据
                $time=date('Ymd');

                //总页数
                $count_data=$getMongo->Finger->ConfirmRes->count(['sno'=>$this->get_data_in_session('staff_num'),'time'=>$time]);
                $cnt_page=intval(ceil($count_data/$limit));

                //展示的数据
                $model=$getMongo->Finger->ConfirmRes->find(['sno'=>$this->get_data_in_session('staff_num'),'time'=>$time])
                    ->sort(['sort'=>-1])->limit($limit)->skip($offset);

                foreach ($model as $row)
                {
                    $tmp[]=$row;
                }

                foreach ($tmp as $row)
                {
                    //组成前端能显示的数据
                    $cust=CustFVModel::find($row['id_in_mysql']);

                    $tmp_tmp[]=[
                        $cust->cust_name,
                        $cust->cust_id,
                        $row['res_of_fv']=='true'?'<font color="green">认证通过</font>':'<font color="red">认证失败</font>',
                        $row['res_of_fp']=='error'?'<font color="red">认证失败</font>':($row['res_of_fp']=='true'?'<font color="green">认证通过</font>':'<font color="red">认证失败</font>'),
                        $this->get_finger_name($row['fno']),
                        $cust->cust_phone_num,
                        $cust->cust_phone_bku
                    ];
                }

                $model=$tmp_tmp;

                return ['error'=>'0','msg'=>'数据读取成功','pages'=>$cnt_page,'data'=>$model,'count_data'=>$count_data];

                break;
        }
    }

}
