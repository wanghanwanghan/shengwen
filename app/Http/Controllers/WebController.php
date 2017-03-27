<?php

namespace App\Http\Controllers;

use App\Http\Model\ConfirmTypeModel;
use App\Http\Model\CustModel;
use App\Http\Model\ProjectModel;
use App\Http\Model\SiTypeModel;
use App\Http\Model\StaffModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class WebController extends Controller
{
    public function add_cust()
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

        return view('add_cust',compact('staff_project','staff_si_type','confirm_type'));
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

        //先通过传过来的第一年审人id，查询处第一年审人的信息给前端页面
        $model=CustModel::find(Input::get('id'))->toArray();

        //把model中的数字代码变成中文
        foreach ($model as $key=>$value)
        {
            if ($key=='cust_project')
            {
                $model['cust_project']=ProjectModel::find($value)->project_name;
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
















}
