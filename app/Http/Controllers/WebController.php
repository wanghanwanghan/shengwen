<?php

namespace App\Http\Controllers;

use App\Http\Model\ConfirmTypeModel;
use App\Http\Model\CustModel;
use App\Http\Model\CustModel_tianmen_ready;
use App\Http\Model\OnlyTianMenModel;
use App\Http\Model\ProjectModel;
use App\Http\Model\SiTypeModel;
use App\Http\Model\StaffModel;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class WebController extends Controller
{
    public function add_cust()
    {
        foreach (Session::get('user') as $row)
        {
            $staff_project=$row['staff_project'];
            $staff_si_type=$row['staff_si_type'];
            $staff_level=$row['staff_level'];
            $staff_name=explode('_',$row['staff_account']);
        }

        if ($staff_name[0]=='sw' || $staff_name[0]=='zbxl')
        {
            $staff_name='yes';
        }else
        {
            $staff_name='no';
        }

        $staff_project=explode(',',$staff_project);
        $staff_project=ProjectModel::whereIn('project_id',$staff_project)->get(['project_name'])->toArray();
        $staff_project=array_flatten($staff_project);

        $staff_si_type=explode(',',$staff_si_type);
        $staff_si_type=SiTypeModel::whereIn('si_id',$staff_si_type)->get(['si_name'])->toArray();
        $staff_si_type=array_flatten($staff_si_type);

        $confirm_type=ConfirmTypeModel::get(['confirm_name'])->toArray();
        $confirm_type=array_flatten($confirm_type);

        if (Config::get('constant.app_edition')=='1')
        {
            return view('add_cust_onlyhubeitianmen',compact('staff_project','staff_si_type','confirm_type','staff_name'));
        }else
        {
            return view('add_cust',compact('staff_project','staff_si_type','confirm_type'));
        }
    }

    public function add_second()
    {
        foreach (Session::get('user') as $row)
        {
            $staff_project=$row['staff_project'];
            $staff_si_type=$row['staff_si_type'];
            $staff_level=$row['staff_level'];
        }

        $staff_project=explode(',',$staff_project);
        $staff_project=ProjectModel::whereIn('project_id',$staff_project)->get(['project_name'])->toArray();
        $staff_project=array_flatten($staff_project);

        $staff_si_type=explode(',',$staff_si_type);
        $staff_si_type=SiTypeModel::whereIn('si_id',$staff_si_type)->get(['si_name'])->toArray();
        $staff_si_type=array_flatten($staff_si_type);

        $confirm_type=ConfirmTypeModel::get(['confirm_name'])->toArray();
        $confirm_type=array_flatten($confirm_type);

        if (Input::get('is_ready_cust')=='yes')
        {
            //天门专用
            $model=OnlyTianMenModel::find(Input::get('id'))->toArray();
            $first_id=Input::get('id');

            return view('add_second_onlyhubeitianmen',compact('first_id','model','staff_project','staff_si_type','confirm_type'));

        }elseif (Input::get('is_ready_cust')=='no' && Redis::get('which_table_'.$this->get_data_in_session('staff_num'))=='cust_a')
        {
            //天门专用
            $model=CustModel::find(Input::get('id'))->toArray();
            $first_id=Input::get('id');

            return view('add_second_onlyhubeitianmen',compact('first_id','model','staff_project','staff_si_type','confirm_type'));

        }else
        {}

        if (Redis::get('which_table_'.$this->get_data_in_session('staff_num'))=='cust_ready')
        {
            //天门专用
            $model=CustModel_tianmen_ready::find(Input::get('id'))->toArray();
            $first_id=Input::get('id');

            //dd($model);

            return view('add_second_onlyhubeitianmen',compact('first_id','model','staff_project','staff_si_type','confirm_type'));
        }

        //先通过传过来的第一年审人id，查询处第一年审人的信息给前端页面
        $model=CustModel::find(Input::get('id'))->toArray();

        //把model中的数字代码变成中文
        foreach ($model as $key=>$value)
        {
            if ($key=='cust_project')
            {
                $model['cust_project']=ProjectModel::find($value)->project_name;
                $model['cust_project_id']=$value;
            }

            if ($key=='cust_confirm_type')
            {
                $model['cust_confirm_type']=ConfirmTypeModel::find($value)->confirm_name;
            }

            if ($key=='cust_si_type')
            {
                $model['cust_si_type']=SiTypeModel::find($value)->si_name;
            }

            if ($key=='cust_type')
            {
                $model['cust_type']=$value;
            }
        }

        //第一年审人的id传过去
        $first_id=Input::get('id');

        return view('add_second',compact('first_id','model','staff_project','staff_si_type','confirm_type'));
    }

    public function add_cust_b()
    {
        foreach (Session::get('user') as $row)
        {
            $staff_project=$row['staff_project'];
            $staff_si_type=$row['staff_si_type'];
            $staff_level=$row['staff_level'];
        }

        $staff_project=explode(',',$staff_project);
        $staff_project=ProjectModel::whereIn('project_id',$staff_project)->get(['project_name'])->toArray();
        $staff_project=array_flatten($staff_project);

        $staff_si_type=explode(',',$staff_si_type);
        $staff_si_type=SiTypeModel::whereIn('si_id',$staff_si_type)->get(['si_name'])->toArray();
        $staff_si_type=array_flatten($staff_si_type);

        $confirm_type=ConfirmTypeModel::get(['confirm_name'])->toArray();
        $confirm_type=array_flatten($confirm_type);

        return view('add_cust_b',compact('staff_project','staff_si_type','confirm_type'));
    }

    public function add_cust_vena()
    {
        foreach (Session::get('user') as $row)
        {
            $staff_project=$row['staff_project'];
            $staff_si_type=$row['staff_si_type'];
            $staff_level=$row['staff_level'];
        }

        $staff_project=explode(',',$staff_project);
        $staff_project=ProjectModel::whereIn('project_id',$staff_project)->get(['project_name'])->toArray();
        $staff_project=array_flatten($staff_project);

        $staff_si_type=explode(',',$staff_si_type);
        $staff_si_type=SiTypeModel::whereIn('si_id',$staff_si_type)->get(['si_name'])->toArray();
        $staff_si_type=array_flatten($staff_si_type);

        $confirm_type=ConfirmTypeModel::get(['confirm_name'])->toArray();
        $confirm_type=array_flatten($confirm_type);

        return view('add_cust_vena',compact('staff_project','staff_si_type','confirm_type'));
    }

    public function fv_match()
    {
        foreach (Session::get('user') as $row)
        {
            $staff_project=$row['staff_project'];
            $staff_si_type=$row['staff_si_type'];
            $staff_level=$row['staff_level'];
        }

        $staff_project=explode(',',$staff_project);
        $staff_project=ProjectModel::whereIn('project_id',$staff_project)->get(['project_name'])->toArray();
        $staff_project=array_flatten($staff_project);

        $staff_si_type=explode(',',$staff_si_type);
        $staff_si_type=SiTypeModel::whereIn('si_id',$staff_si_type)->get(['si_name'])->toArray();
        $staff_si_type=array_flatten($staff_si_type);

        $confirm_type=ConfirmTypeModel::get(['confirm_name'])->toArray();
        $confirm_type=array_flatten($confirm_type);

        return view('fv_match',compact('staff_project','staff_si_type','confirm_type'));
    }

    public function service_care()
    {
        foreach (Session::get('user') as $row)
        {
            $staff_project=$row['staff_project'];
            $staff_si_type=$row['staff_si_type'];
            $staff_level=$row['staff_level'];
        }

        $staff_project=explode(',',$staff_project);
        $staff_project=ProjectModel::whereIn('project_id',$staff_project)->get(['project_id','project_name'])->toArray();
        //遍历成键值对数组，发给前台页面
        foreach ($staff_project as $row)
        {
            $proj[$row['project_id']]=$row['project_name'];
        }
        $staff_project=$proj;

        $staff_si_type=explode(',',$staff_si_type);
        $staff_si_type=SiTypeModel::whereIn('si_id',$staff_si_type)->get(['si_id','si_name'])->toArray();
        //遍历成键值对数组，发给前台页面
        foreach ($staff_si_type as $row)
        {
            $si[$row['si_id']]=$row['si_name'];
        }
        $staff_si_type=$si;

        $confirm_type=ConfirmTypeModel::get(['confirm_name'])->toArray();
        $confirm_type=array_flatten($confirm_type);

        return view('service_care',compact('staff_project','staff_si_type','confirm_type'));
    }

    public function select_info()
    {
        foreach (Session::get('user') as $row)
        {
            $staff_project=$row['staff_project'];
            $staff_si_type=$row['staff_si_type'];
            $staff_level=$row['staff_level'];
        }

        $staff_project=explode(',',$staff_project);
        $staff_project=ProjectModel::whereIn('project_id',$staff_project)->get(['project_id','project_name'])->toArray();
        //遍历成键值对数组，发给前台页面
        foreach ($staff_project as $row)
        {
            $proj[$row['project_id']]=$row['project_name'];
        }
        $staff_project=$proj;

        $staff_si_type=explode(',',$staff_si_type);
        $staff_si_type=SiTypeModel::whereIn('si_id',$staff_si_type)->get(['si_id','si_name'])->toArray();
        //遍历成键值对数组，发给前台页面
        foreach ($staff_si_type as $row)
        {
            $si[$row['si_id']]=$row['si_name'];
        }
        $staff_si_type=$si;

        $confirm_type=ConfirmTypeModel::get(['confirm_name'])->toArray();
        $confirm_type=array_flatten($confirm_type);

        return view('select_info',compact('staff_project','staff_si_type'));
    }

    public function loop_call()
    {
        foreach (Session::get('user') as $row)
        {
            $staff_project=$row['staff_project'];
            $staff_si_type=$row['staff_si_type'];
            $staff_level=$row['staff_level'];
        }

        $staff_project=explode(',',$staff_project);
        $staff_project=ProjectModel::whereIn('project_id',$staff_project)->get(['project_id','project_name'])->toArray();
        //遍历成键值对数组，发给前台页面
        foreach ($staff_project as $row)
        {
            $proj[$row['project_id']]=$row['project_name'];
        }
        $staff_project=$proj;

        $staff_si_type=explode(',',$staff_si_type);
        $staff_si_type=SiTypeModel::whereIn('si_id',$staff_si_type)->get(['si_id','si_name'])->toArray();
        //遍历成键值对数组，发给前台页面
        foreach ($staff_si_type as $row)
        {
            $si[$row['si_id']]=$row['si_name'];
        }
        $staff_si_type=$si;

        $confirm_type=ConfirmTypeModel::get(['confirm_name'])->toArray();
        $confirm_type=array_flatten($confirm_type);

        return view('loop_call',compact('staff_project','staff_si_type'));
    }

    public function modify_cust_info()
    {
        foreach (Session::get('user') as $row)
        {
            $staff_project=$row['staff_project'];
            $staff_si_type=$row['staff_si_type'];
            $staff_level=$row['staff_level'];
        }

        $staff_project=explode(',',$staff_project);
        $staff_project=ProjectModel::whereIn('project_id',$staff_project)->get(['project_name'])->toArray();
        $staff_project=array_flatten($staff_project);

        $staff_si_type=explode(',',$staff_si_type);
        $staff_si_type=SiTypeModel::whereIn('si_id',$staff_si_type)->get(['si_name'])->toArray();
        $staff_si_type=array_flatten($staff_si_type);

        $confirm_type=ConfirmTypeModel::get(['confirm_name'])->toArray();
        $confirm_type=array_flatten($confirm_type);

        return view('modify_cust_info',compact('staff_project','staff_si_type','confirm_type'));
    }

    public function modify_cust_info_ready()
    {
        //天门用
        foreach (Session::get('user') as $row)
        {
            $staff_project=$row['staff_project'];
            $staff_si_type=$row['staff_si_type'];
            $staff_level=$row['staff_level'];
        }

        $staff_project=explode(',',$staff_project);
        $staff_project=ProjectModel::whereIn('project_id',$staff_project)->get(['project_name'])->toArray();
        $staff_project=array_flatten($staff_project);

        $staff_si_type=explode(',',$staff_si_type);
        $staff_si_type=SiTypeModel::whereIn('si_id',$staff_si_type)->get(['si_name'])->toArray();
        $staff_si_type=array_flatten($staff_si_type);

        $confirm_type=ConfirmTypeModel::get(['confirm_name'])->toArray();
        $confirm_type=array_flatten($confirm_type);

        return view('modify_cust_info_ready',compact('staff_project','staff_si_type','confirm_type'));
    }

    public function ivr_return_msg()
    {
        foreach (Session::get('user') as $row)
        {
            $staff_project=$row['staff_project'];
            $staff_si_type=$row['staff_si_type'];
            $staff_level=$row['staff_level'];
        }

        $staff_project=explode(',',$staff_project);
        $staff_project=ProjectModel::whereIn('project_id',$staff_project)->get(['project_id','project_name'])->toArray();
        //遍历成键值对数组，发给前台页面
        foreach ($staff_project as $row)
        {
            $proj[$row['project_id']]=$row['project_name'];
        }
        $staff_project=$proj;

        $staff_si_type=explode(',',$staff_si_type);
        $staff_si_type=SiTypeModel::whereIn('si_id',$staff_si_type)->get(['si_id','si_name'])->toArray();
        //遍历成键值对数组，发给前台页面
        foreach ($staff_si_type as $row)
        {
            $si[$row['si_id']]=$row['si_name'];
        }
        $staff_si_type=$si;

        $confirm_type=ConfirmTypeModel::get(['confirm_name'])->toArray();
        $confirm_type=array_flatten($confirm_type);

        return view('ivr_return_msg',compact('staff_project','staff_si_type'));
    }

    public function fv_register_return_msg()
    {
        foreach (Session::get('user') as $row)
        {
            $staff_project=$row['staff_project'];
            $staff_si_type=$row['staff_si_type'];
            $staff_level=$row['staff_level'];
        }

        $staff_project=explode(',',$staff_project);
        $staff_project=ProjectModel::whereIn('project_id',$staff_project)->get(['project_id','project_name'])->toArray();
        //遍历成键值对数组，发给前台页面
        foreach ($staff_project as $row)
        {
            $proj[$row['project_id']]=$row['project_name'];
        }
        $staff_project=$proj;

        $staff_si_type=explode(',',$staff_si_type);
        $staff_si_type=SiTypeModel::whereIn('si_id',$staff_si_type)->get(['si_id','si_name'])->toArray();
        //遍历成键值对数组，发给前台页面
        foreach ($staff_si_type as $row)
        {
            $si[$row['si_id']]=$row['si_name'];
        }
        $staff_si_type=$si;

        $confirm_type=ConfirmTypeModel::get(['confirm_name'])->toArray();
        $confirm_type=array_flatten($confirm_type);

        return view('fv_register_return_msg',compact('staff_project','staff_si_type'));
    }

    public function ivr_return_loop_msg()
    {
        foreach (Session::get('user') as $row)
        {
            $staff_project=$row['staff_project'];
            $staff_si_type=$row['staff_si_type'];
            $staff_level=$row['staff_level'];
        }

        $staff_project=explode(',',$staff_project);
        $staff_project=ProjectModel::whereIn('project_id',$staff_project)->get(['project_id','project_name'])->toArray();
        //遍历成键值对数组，发给前台页面
        foreach ($staff_project as $row)
        {
            $proj[$row['project_id']]=$row['project_name'];
        }
        $staff_project=$proj;

        $staff_si_type=explode(',',$staff_si_type);
        $staff_si_type=SiTypeModel::whereIn('si_id',$staff_si_type)->get(['si_id','si_name'])->toArray();
        //遍历成键值对数组，发给前台页面
        foreach ($staff_si_type as $row)
        {
            $si[$row['si_id']]=$row['si_name'];
        }
        $staff_si_type=$si;

        $confirm_type=ConfirmTypeModel::get(['confirm_name'])->toArray();
        $confirm_type=array_flatten($confirm_type);

        return view('ivr_return_loop_msg',compact('staff_project','staff_si_type'));
    }

    public function fv_confirm_return_msg()
    {
        foreach (Session::get('user') as $row)
        {
            $staff_project=$row['staff_project'];
            $staff_si_type=$row['staff_si_type'];
            $staff_level=$row['staff_level'];
        }

        $staff_project=explode(',',$staff_project);
        $staff_project=ProjectModel::whereIn('project_id',$staff_project)->get(['project_id','project_name'])->toArray();
        //遍历成键值对数组，发给前台页面
        foreach ($staff_project as $row)
        {
            $proj[$row['project_id']]=$row['project_name'];
        }
        $staff_project=$proj;

        $staff_si_type=explode(',',$staff_si_type);
        $staff_si_type=SiTypeModel::whereIn('si_id',$staff_si_type)->get(['si_id','si_name'])->toArray();
        //遍历成键值对数组，发给前台页面
        foreach ($staff_si_type as $row)
        {
            $si[$row['si_id']]=$row['si_name'];
        }
        $staff_si_type=$si;

        $confirm_type=ConfirmTypeModel::get(['confirm_name'])->toArray();
        $confirm_type=array_flatten($confirm_type);

        return view('fv_confirm_return_msg',compact('staff_project','staff_si_type'));
    }

    public function statistics()
    {
        foreach (Session::get('user') as $row)
        {
            $staff_project=$row['staff_project'];
            $staff_si_type=$row['staff_si_type'];
            $staff_level=$row['staff_level'];
        }

        $staff_project=explode(',',$staff_project);
        $staff_project=ProjectModel::whereIn('project_id',$staff_project)->get(['project_id','project_name'])->toArray();
        //遍历成键值对数组，发给前台页面
        foreach ($staff_project as $row)
        {
            $proj[$row['project_id']]=$row['project_name'];
        }
        $staff_project=$proj;

        $staff_si_type=explode(',',$staff_si_type);
        $staff_si_type=SiTypeModel::whereIn('si_id',$staff_si_type)->get(['si_id','si_name'])->toArray();
        //遍历成键值对数组，发给前台页面
        foreach ($staff_si_type as $row)
        {
            $si[$row['si_id']]=$row['si_name'];
        }
        $staff_si_type=$si;

        $confirm_type=ConfirmTypeModel::get(['confirm_name'])->toArray();
        $confirm_type=array_flatten($confirm_type);

        return view('statistics',compact('staff_project','staff_si_type'));
    }

    public function analysis()
    {
        foreach (Session::get('user') as $row)
        {
            $staff_project=$row['staff_project'];
            $staff_si_type=$row['staff_si_type'];
            $staff_level=$row['staff_level'];
        }

        $staff_project=explode(',',$staff_project);
        $staff_project=ProjectModel::whereIn('project_id',$staff_project)->get(['project_id','project_name'])->toArray();
        //遍历成键值对数组，发给前台页面
        foreach ($staff_project as $row)
        {
            $proj[$row['project_id']]=$row['project_name'];
        }
        $staff_project=$proj;

        $staff_si_type=explode(',',$staff_si_type);
        $staff_si_type=SiTypeModel::whereIn('si_id',$staff_si_type)->get(['si_id','si_name'])->toArray();
        //遍历成键值对数组，发给前台页面
        foreach ($staff_si_type as $row)
        {
            $si[$row['si_id']]=$row['si_name'];
        }
        $staff_si_type=$si;

        $confirm_type=ConfirmTypeModel::get(['confirm_name'])->toArray();
        $confirm_type=array_flatten($confirm_type);

        return view('analysis',compact('staff_project','staff_si_type'));
    }

    public function get_username()
    {
        $res=Session::get('user');
        return $res[0]['staff_name'];
    }

    public function allocation()
    {
        foreach (Session::get('user') as $row)
        {
            $staff_project=$row['staff_project'];
            $staff_si_type=$row['staff_si_type'];
            $staff_level=$row['staff_level'];
        }

        $staff_project=explode(',',$staff_project);
        $staff_project=ProjectModel::whereIn('project_id',$staff_project)->get(['project_id','project_name'])->toArray();
        //遍历成键值对数组，发给前台页面
        foreach ($staff_project as $row)
        {
            $proj[$row['project_id']]=$row['project_name'];
        }
        $staff_project=$proj;

        $staff_si_type=explode(',',$staff_si_type);
        $staff_si_type=SiTypeModel::whereIn('si_id',$staff_si_type)->get(['si_id','si_name'])->toArray();
        //遍历成键值对数组，发给前台页面
        foreach ($staff_si_type as $row)
        {
            $si[$row['si_id']]=$row['si_name'];
        }
        $staff_si_type=$si;

        $confirm_type=ConfirmTypeModel::get(['confirm_name'])->toArray();
        $confirm_type=array_flatten($confirm_type);

        return view('allocation',compact('staff_project','staff_si_type','confirm_type'));
    }

    public function source_cust_data()
    {
        return view('source_cust_data');
    }

    public function import_confirm_result()
    {
        foreach (Session::get('user') as $row)
        {
            $staff_project=$row['staff_project'];
            $staff_si_type=$row['staff_si_type'];
            $staff_level=$row['staff_level'];
        }

        $staff_project=explode(',',$staff_project);
        $staff_project=ProjectModel::whereIn('project_id',$staff_project)->get(['project_id','project_name'])->toArray();
        //遍历成键值对数组，发给前台页面
        foreach ($staff_project as $row)
        {
            $proj[$row['project_id']]=$row['project_name'];
        }
        $staff_project=$proj;

        $staff_si_type=explode(',',$staff_si_type);
        $staff_si_type=SiTypeModel::whereIn('si_id',$staff_si_type)->get(['si_id','si_name'])->toArray();
        //遍历成键值对数组，发给前台页面
        foreach ($staff_si_type as $row)
        {
            $si[$row['si_id']]=$row['si_name'];
        }
        $staff_si_type=$si;

        $confirm_type=ConfirmTypeModel::get(['confirm_name'])->toArray();
        $confirm_type=array_flatten($confirm_type);

        return view('import_confirm_result',compact('staff_project','staff_si_type','confirm_type'));
    }

    public function upload(Request $request)
    {
        $file=$request->file('表单名');

        //判断是否上传成功
        if ($file->isValid())
        {
            //成功
            $a=$file->getClientOriginalName();

            $b=$file->getClientOriginalExtension();

            $c=$file->getClientMimeType();

            $d=$file->getRealPath();

            $filename=date('Y-m-d',time()).'-'.uniqid().'.'.$b;

            $bool=Storage::disk('upload')->put($filename,file_get_contents($d));

        }else
        {
            //不成功
        }





    }

    public function mail()
    {
        Mail::raw('邮件内容 测试',function ($msg){

            $msg->from('minglongoc@me.com','王瀚');

            $msg->subject('邮件主题 测试');

            $msg->to('sb@sina.com');

        });

        Mail::send('视图模板名称',['name'=>'王瀚'],function ($msg){

            $msg->to('sb@sina.com');

        });
    }

















}
