<?php

namespace App\Http\Controllers;

use App\Http\Model\ChinaAllPositionModel;
use App\Http\Model\LogModel;
use App\Http\Model\ProjectModel;
use App\Http\Model\SiTypeModel;
use App\Http\Model\StaffModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    //导入社保数据
    public function import_1()
    {
        $cond=Input::all();

        $error1=isset($cond['myfile']) ? '' : '请选择要导入的文件！';
        $error2=isset($cond['check']) ? '' : '没有勾选单选框！';
        $msg='';

        if ($error1!='')
        {
            $msg=$msg.$error1;
        }
        if ($error2!='')
        {
            $msg=$msg.$error2;
        }
        if ($msg!='')
        {
            return redirect()->back()->with('danger',$msg);
        }

        if ($_FILES['myfile']['type']!='application/vnd.ms-excel' || substr($_FILES['myfile']['name'],-4)!='.xls')
        {
            return redirect()->back()->with('warning','导入的文件格式不正确！');
        }

        //如果通过所有验证
        Excel::load($_FILES['myfile']['tmp_name'],function($reader){
            Redis::set('file_content',json_encode($reader->all()->toArray()));
        });

        //要上传的文件中的所有内容
        $file_content=json_decode(Redis::get('file_content'),true);

        //判断每行数据是否正确，并且得到数据表名称
        $line=1;
        foreach ($file_content as $row)
        {
            //第一个条数据作为判断项
            if ($line=='1')
            {
                $this_county=$row['county'];
                $this_town=$row['town'];
                $this_village=$row['village'];
                $this_sitype=SiTypeModel::pluck('si_name')->toArray();

                //这个info打算建立path用
                $id_info=ChinaAllPositionModel::where(['county_name'=>$this_county,'town_name'=>$this_town,'village_name'=>$this_village])
                    ->get(['county_id','town_id','village_id'])
                    ->toArray();

                if (empty($id_info))
                {
                    return redirect()->back()->with('warning','第'.++$line.'行county，town，village未找到，请重新确认');
                }
            }

            if ($row['county']!=$this_county)
            {
                return redirect()->back()->with('warning','第'.++$line.'行county列里的'.$row['county'].'不属于'.$this_county);
            }
            if ($row['town']!=$this_town)
            {
                return redirect()->back()->with('warning','第'.++$line.'行town列里的'.$row['town'].'不属于'.$this_town);
            }
            if ($row['village']!=$this_village)
            {
                return redirect()->back()->with('warning','第'.++$line.'行village列里的'.$row['village'].'不属于'.$this_village);
            }
            if (!in_array($row['sitype'],$this_sitype))
            {
                return redirect()->back()->with('warning','第'.++$line.'行sitype列里的'.$row['sitype'].'不正确');
            }

            $line++;
        }

        $table_name='social_insurance';
        $position_path=$id_info[0]['county_id'].'_'.$id_info[0]['town_id'].'_'.$id_info[0]['village_id'];

        //创建mysql表
        if(!Schema::hasTable($table_name))
        {
            Schema::create($table_name,function (Blueprint $table)
            {
                $table->increments('id');
                $table->string('county','20');
                $table->string('town','20');
                $table->string('village','20');
                $table->string('county_id','20')->index();;
                $table->string('town_id','20')->index();;
                $table->string('village_id','20')->index();;
                $table->string('name','6');
                $table->string('idcard','18')->unique();
                $table->string('sitype','20')->index();
                $table->string('sicard','20')->index();
                $table->string('bank','30')->index();
                $table->string('position_path','100')->index();
                $table->engine='innodb';
            });
        }

        //判断身份证是否已经被插入过了
        $idcard_arr=DB::table($table_name)->pluck('idcard')->toArray();
        //判断社保号是否已经被插入过了
        $sicard_arr=DB::table($table_name)->whereNotNull('sicard')->pluck('sicard')->toArray();

        //如果不是空，就进来判断一下身份证号码是不是已经插入了
        if (!empty($idcard_arr))
        {
            foreach ($file_content as $row)
            {
                $need=strtoupper(trim($row['idcard']));
                if (in_array($need,$idcard_arr))
                {
                    session()->flash('warning','身份证：'.$need.'已经在数据库中了，导入已终止');
                    return redirect()->back();
                }
            }
        }

        if (!empty($sicard_arr))
        {
            foreach ($file_content as $row)
            {
                $need=strtoupper(trim($row['sicard']));
                if (in_array($need,$sicard_arr))
                {
                    session()->flash('warning','社保号：'.$need.'已经在数据库中了，导入已终止');
                    return redirect()->back();
                }
            }
        }

        //最后，给内容中插入position_path
        foreach ($file_content as &$row)
        {
            $row['county_id']=$id_info[0]['county_id'];
            $row['town_id']=$id_info[0]['town_id'];
            $row['village_id']=$id_info[0]['village_id'];
            $row['position_path']=$position_path;
        }
        unset($row);

        $redis_key='excel_'.time();

        //给redis加个过期时间
        $this->redis_set($redis_key,json_encode($file_content),300);

        //插入数据，这个动作要发送给另一个程序，不然会超时
        file_get_contents(env('APP_URL').'/insert_excel_data_1?table_name='.$table_name.'&redis_key='.$redis_key.'&position_path='.$position_path);

        session()->flash('success','开始导入，是否导入成功请查看日志');
        return redirect()->back();
    }

    //导入地区数据
    public function import_2()
    {
        $cond=Input::all();

        $error1=isset($cond['myfile']) ? '' : '请选择要导入的文件！';
        $error2=isset($cond['check']) ? '' : '没有勾选单选框！';
        $msg='';

        if ($error1!='')
        {
            $msg=$msg.$error1;
        }
        if ($error2!='')
        {
            $msg=$msg.$error2;
        }
        if ($msg!='')
        {
            return redirect()->back()->with('danger2',$msg);
        }

        if ($_FILES['myfile']['type']!='application/vnd.ms-excel' || substr($_FILES['myfile']['name'],-4)!='.xls')
        {
            return redirect()->back()->with('warning2','导入的文件格式不正确！');
        }

        //如果通过所有验证
        Excel::load($_FILES['myfile']['tmp_name'],function($reader){
            Redis::set('import_position',json_encode($reader->all()->toArray()));
        });

        //要上传的文件中的所有内容
        $file_content=json_decode(Redis::get('import_position'),true);

        //**************************************
        // 导入文件中的内容没有验证
        //**************************************

        $redis_key='excel_'.time();

        //给redis加个过期时间
        $this->redis_set($redis_key,json_encode($file_content),300);

        //插入数据，这个动作要发送给另一个程序，不然会超时
        file_get_contents(env('APP_URL').'/insert_excel_data_2?redis_key='.$redis_key);

        return redirect()->back()->with('success2','开始导入，是否导入成功请查看日志');
    }

    //导入新的属地
    public function import_3()
    {
        $cond=Input::all();

        $error1=isset($cond['myfile']) ? '' : '请选择要导入的文件！';
        $error2=isset($cond['check']) ? '' : '没有勾选单选框！';
        $msg='';

        if ($error1!='')
        {
            $msg=$msg.$error1;
        }
        if ($error2!='')
        {
            $msg=$msg.$error2;
        }
        if ($msg!='')
        {
            return redirect()->back()->with('danger',$msg);
        }

        if ($_FILES['myfile']['type']!='application/vnd.ms-excel' || substr($_FILES['myfile']['name'],-4)!='.xls')
        {
            return redirect()->back()->with('warning','导入的文件格式不正确！');
        }

        //如果通过所有验证
        Excel::load($_FILES['myfile']['tmp_name'],function($reader){
            $this->redis_set('file_content_1',json_encode($reader->all()->toArray()),300);
        });

        //要上传的文件中的所有内容
        $file_content=json_decode(Redis::get('file_content_1'),true);

        //判断每行数据是否正确，并且得到数据表名称
        $line=1;
        foreach ($file_content as $row)
        {
            //dd($row);

            //判断excel每行是否存在
            //判断第一级
            $res1=ProjectModel::where([
                'project_name'=>$row['province_name'],
                'project_parent'=>'0'
            ])->count();
            if ($res1=='0')
            {
                //没有这个省（第一级）
                $check=ChinaAllPositionModel::where(['province_name'=>$row['province_name']])->distinct()->count();
                if ($check=='0')
                {
                    return redirect()->back()->with('danger','第'.++$line.'行，第1级数据未找到，请先导入');
                }
                $province_id=ProjectModel::create(['project_name'=>$row['province_name'],'project_parent'=>'0','project_path'=>'0']);
                $province_id=$province_id->project_id;
            }else
            {
                $province_id=ProjectModel::where(['project_name'=>$row['province_name'],'project_parent'=>'0'])->get();
                $province_id=$province_id[0]->project_id;
            }

            if (!isset($row['city_name']))
            {
                continue;
            }

            //判断第二级
            $res2=ProjectModel::where([
                'project_name'=>$row['city_name'],
                'project_parent'=>$province_id
            ])->count();
            if ($res2=='0')
            {
                //没有这个市（第二级）
                $check=ChinaAllPositionModel::where([
                    'province_name'=>$row['province_name'],
                    'city_name'=>$row['city_name']
                ])->distinct()->count();
                if ($check=='0')
                {
                    return redirect()->back()->with('danger','第'.++$line.'行，第2级数据未找到，请先导入');
                }
                $city_id=ProjectModel::create(['project_name'=>$row['city_name'],'project_parent'=>$province_id,'project_path'=>$province_id]);
                $city_id=$city_id->project_id;
            }else
            {
                $city_id=ProjectModel::where(['project_name'=>$row['city_name'],'project_parent'=>$province_id])->get();
                $city_id=$city_id[0]->project_id;
            }

            if (!isset($row['county_name']))
            {
                continue;
            }

            //判断第三级
            $res3=ProjectModel::where([
                'project_name'=>$row['county_name'],
                'project_parent'=>$city_id
            ])->count();
            if ($res3=='0')
            {
                //没有这个县（第三级）
                $check=ChinaAllPositionModel::where([
                    'province_name'=>$row['province_name'],
                    'city_name'=>$row['city_name'],
                    'county_name'=>$row['county_name']
                ])->distinct()->count();
                if ($check=='0')
                {
                    return redirect()->back()->with('danger','第'.++$line.'行，第3级数据未找到，请先导入');
                }
                $county_id=ProjectModel::create(['project_name'=>$row['county_name'],'project_parent'=>$city_id,'project_path'=>$province_id.'-'.$city_id]);
                $county_id=$county_id->project_id;
            }else
            {
                $county_id=ProjectModel::where(['project_name'=>$row['county_name'],'project_parent'=>$city_id])->get();
                $county_id=$county_id[0]->project_id;
            }

            if (!isset($row['town_name']))
            {
                continue;
            }

            //判断第四级
            $res4=ProjectModel::where([
                'project_name'=>$row['town_name'],
                'project_parent'=>$county_id
            ])->count();
            if ($res4=='0')
            {
                //没有这个镇（第四级）
                $check=ChinaAllPositionModel::where([
                    'province_name'=>$row['province_name'],
                    'city_name'=>$row['city_name'],
                    'county_name'=>$row['county_name'],
                    'town_name'=>$row['town_name']
                ])->distinct()->count();
                if ($check=='0')
                {
                    return redirect()->back()->with('danger','第'.++$line.'行，第4级数据未找到，请先导入');
                }
                $town_id=ProjectModel::create(['project_name'=>$row['town_name'],'project_parent'=>$county_id,'project_path'=>$province_id.'-'.$city_id.'-'.$county_id]);
                $town_id=$town_id->project_id;
            }else
            {
                $town_id=ProjectModel::where(['project_name'=>$row['town_name'],'project_parent'=>$county_id])->get();
                $town_id=$town_id[0]->project_id;
            }

            if (!isset($row['village_name']))
            {
                continue;
            }

            //判断第五级
            $res5=ProjectModel::where([
                'project_name'=>$row['village_name'],
                'project_parent'=>$town_id
            ])->count();
            if ($res5=='0')
            {
                //没有这个村（第五级）
                $check=ChinaAllPositionModel::where([
                    'province_name'=>$row['province_name'],
                    'city_name'=>$row['city_name'],
                    'county_name'=>$row['county_name'],
                    'town_name'=>$row['town_name'],
                    'village_name'=>$row['village_name']
                ])->distinct()->count();
                if ($check=='0')
                {
                    return redirect()->back()->with('danger','第'.++$line.'行，第5级数据未找到，请先导入');
                }
                $village_id=ProjectModel::create(['project_name'=>$row['village_name'],'project_parent'=>$town_id,'project_path'=>$province_id.'-'.$city_id.'-'.$county_id.'-'.$town_id]);
                $village_id=$village_id->project_id;
            }else
            {
                $village_id=ProjectModel::where(['project_name'=>$row['village_name'],'project_parent'=>$town_id])->get();
                $village_id=$village_id[0]->project_id;
            }
            $line++;
        }

        return redirect()->back()->with('success','导入成功');
    }

    //导入社保数据
    public function insert_excel_data_1()
    {
        $table_name=isset($_GET['table_name']) ? $_GET['table_name'] : '';
        $redis_key=isset($_GET['redis_key']) ? $_GET['redis_key'] : '';
        $position_path=isset($_GET['position_path']) ? $_GET['position_path'] : '';

        $root=StaffModel::find(1);

        if ($table_name!='' && $redis_key!='' && $position_path!='')
        {
            $file_content=json_decode(Redis::get($redis_key),true);

            try
            {
                DB::table($table_name)->insert($file_content);
            }catch (\Exception $exception)
            {
                LogModel::create([
                    'log_account'=>$root->staff_account,
                    'log_todo'=>'导入社保数据失败',
                    'log_detail'=>$exception,
                    'created_at'=>date('Y-m-d H:i:s',time()),
                    'updated_at'=>date('Y-m-d H:i:s',time())
                ]);
            }

            $cond=explode('_',$position_path);
            $position_name=ChinaAllPositionModel::where(['county_id'=>$cond[0],'town_id'=>$cond[1],'village_id'=>$cond[2]])
                ->get(['county_name','town_name','village_name'])->toArray();

            LogModel::create([
                'log_account'=>$root->staff_account,
                'log_todo'=>'导入社保数据成功',
                'log_detail'=>'地区是：'.$position_name[0]['county_name'].'-'.$position_name[0]['town_name'].'-'.$position_name[0]['village_name'],
                'created_at'=>date('Y-m-d H:i:s',time()),
                'updated_at'=>date('Y-m-d H:i:s',time())
            ]);

        }else
        {
            LogModel::create([
                'log_account'=>$root->staff_account,
                'log_todo'=>'导入社保数据失败',
                'log_detail'=>'参数不正确或者redis没启动',
                'created_at'=>date('Y-m-d H:i:s',time()),
                'updated_at'=>date('Y-m-d H:i:s',time())
            ]);
        }
    }

    //导入地区数据
    public function insert_excel_data_2()
    {
        $redis_key=isset($_GET['redis_key']) ? $_GET['redis_key'] : '';

        $root=StaffModel::find(1);

        if ($redis_key!='')
        {
            $file_content=json_decode(Redis::get($redis_key),true);

            try
            {
                //DB::table($table_name)->insert($file_content);
            }catch (\Exception $exception)
            {
                LogModel::create([
                    'log_account'=>$root->staff_account,
                    'log_todo'=>'导入地区数据失败',
                    'log_detail'=>$exception,
                    'created_at'=>date('Y-m-d H:i:s',time()),
                    'updated_at'=>date('Y-m-d H:i:s',time())
                ]);
            }

            LogModel::create([
                'log_account'=>$root->staff_account,
                'log_todo'=>'导入地区数据成功',
                'log_detail'=>'成功',
                'created_at'=>date('Y-m-d H:i:s',time()),
                'updated_at'=>date('Y-m-d H:i:s',time())
            ]);

        }else
        {
            LogModel::create([
                'log_account'=>$root->staff_account,
                'log_todo'=>'导入地区数据失败',
                'log_detail'=>'参数不正确或者redis没启动',
                'created_at'=>date('Y-m-d H:i:s',time()),
                'updated_at'=>date('Y-m-d H:i:s',time())
            ]);
        }
    }

    //导出未通过认证的客户
    public function export1($key)
    {
        $data=json_decode(Redis::get($key),true);

        foreach ($data as &$row)
        {
            $row=array_values($row);
        }

        Excel::create($key,function($excel) use ($data){
            $excel->sheet('score', function($sheet) use ($data){
                $sheet->rows($data);
            });
        })->store('xls')->export('xls');
    }

    //导出未登记或者已登记的客户
    //导出未认证或者已认证的客户
    public function export2($key)
    {
        //这个key是个队列
        while (1)
        {
            $one=Redis::rpop($key);
            if ($one!='')
            {
                $data[]=json_decode($one,true);
            }else
            {
                break;
            }
        }

        foreach ($data as &$row)
        {
            $row=array_values($row);
        }

        if (strpos($key,'confirm')!==false)
        {
            //strpos($a, $b) !== false 如果$a 中存在 $b，则为 true ，否则为 false。
            array_unshift($data,[
                '姓名',
                '身份证号码',
                '参保类型',
                '社保编号',
                '认证电话',
                '状态'
            ]);

        }else
        {
            array_unshift($data,[
                '县',
                '镇',
                '村',
                '姓名',
                '身份证号码',
                '参保类型',
                '社保编号',
                '状态'
            ]);
        }

        Excel::create($key,function($excel) use ($data){
            $excel->sheet('score', function($sheet) use ($data){
                $sheet->rows($data);
            });
        })->store('xls')->export('xls');
    }

    //导出指静脉
    public function export3($key)
    {
        $data=json_decode(Redis::get($key),true);

        foreach ($data as &$row)
        {
            $row=array_values($row);
        }

        Excel::create($key,function($excel) use ($data){
            $excel->sheet('score', function($sheet) use ($data){
                $sheet->rows($data);
            });
        })->store('xls')->export('xls');
    }

    //导出声纹指静脉认证结果
    public function export4($key)
    {
        $data=json_decode(Redis::get($key),true);

        foreach ($data as &$row)
        {
            $row=array_values($row);
        }

        Excel::create($key,function($excel) use ($data){
            $excel->sheet('score', function($sheet) use ($data){
                $sheet->rows($data);
            });
        })->store('xls')->export('xls');
    }

    //导出声纹指静脉认证结果
    public function export5($key)
    {
        $data=json_decode(Redis::get($key),true);

        foreach ($data as &$row)
        {
            $row=array_values($row);
        }

        Excel::create($key,function($excel) use ($data){
            $excel->sheet('score', function($sheet) use ($data){
                $sheet->rows($data);
            });
        })->store('xls')->export('xls');
    }

    //导出声纹指静脉认证结果
    public function export6($key)
    {
        $data=json_decode(Redis::get($key),true);

        foreach ($data as &$row)
        {
            $row=array_values($row);
        }

        Excel::create($key,function($excel) use ($data){
            $excel->sheet('score', function($sheet) use ($data){
                $sheet->rows($data);
            });
        })->store('xls')->export('xls');
    }

    //导出声纹指静脉认证结果
    public function export7($key)
    {
        $data=json_decode(Redis::get($key),true);

        foreach ($data as &$row)
        {
            $row=array_values($row);
        }

        Excel::create($key,function($excel) use ($data){
            $excel->sheet('score', function($sheet) use ($data){
                $sheet->rows($data);
            });
        })->store('xls')->export('xls');
    }

    //导出天门专用已采集已注册未采集未注册数据
    public function export8($key)
    {
        // 头部标题
        $csv_header=[
            '客户主键',
            '客户姓名',
            '原身份证',
            '现身份证',
            '社保编号',
            '认证电话',
            '备用电话',
            '银行账号',
            '出生日期',
            '参工日期',
            '退休日期',
            '参保类型',
            '所属地区',
            '生物特征'
        ];
        // 内容
        //这个key是个队列
        while (1)
        {
            $one=Redis::rpop($key);
            if ($one!='')
            {
                $csv_body[]=json_decode($one,true);
            }else
            {
                break;
            }
        }

        /*
         * 开始生成
         * 1.首先将数组拆分成以逗号（注意需要英文）分割的字符串
         * 2.然后加上每行的换行符号，这里建议直接使用PHP的预定义常量PHP_EOL
         * 3.最后写入文件
         */

        //打开文件资源，不存在则创建
        $fp=fopen(storage_path('exports/'.$key.'.csv'),'a');
        //处理头部标题
        $header=implode(',', $csv_header).PHP_EOL;
        //处理内容
        $content='';
        foreach ($csv_body as $k => $v)
        {
            $content.=implode(',',$v).PHP_EOL;
        }

        //拼接
        $csv=$header.$content;
        //写入并关闭资源
        fwrite($fp,$csv);
        fclose($fp);

        return;
    }






}
