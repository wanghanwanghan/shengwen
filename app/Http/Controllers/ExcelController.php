<?php

namespace App\Http\Controllers;

use App\Http\Model\ChinaAllPositionModel;
use App\Http\Model\LogModel;
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
    //导入
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
                $table->string('name','6');
                $table->string('idcard','18')->unique();
                $table->string('sicard','20')->index();
                $table->string('status','6');
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
            $row['position_path']=$position_path;
        }
        unset($row);

        $redis_key='excel_'.time();

        //给redis加个过期时间
        $this->redis_set($redis_key,json_encode($file_content),300);

        //插入数据，这个动作要发送给另一个程序，不然会超时
        file_get_contents('http://zbxl.com/insert_excel_data_1?table_name='.$table_name.'&redis_key='.$redis_key.'&position_path='.$position_path);

        session()->flash('success','开始导入，是否导入成功请查看日志');
        return redirect()->back();
    }

    //导入各个地区的客户数据
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

            LogModel::create([
                'log_account'=>$root->staff_account,
                'log_todo'=>'导入社保数据成功',
                'log_detail'=>'成功',
                'created_at'=>date('Y-m-d H:i:s',time()),
                'updated_at'=>date('Y-m-d H:i:s',time())
            ]);

        }else
        {
            LogModel::create([
                'log_account'=>$root->staff_account,
                'log_todo'=>'导入社保数据失败',
                'log_detail'=>'参数不正确',
                'created_at'=>date('Y-m-d H:i:s',time()),
                'updated_at'=>date('Y-m-d H:i:s',time())
            ]);
        }
    }

    //导出
    public function export()
    {
        $cellData = [
            ['学号','姓名','成绩'],
            ['10001','AAAAA','99'],
            ['10002','BBBBB','92'],
            ['10003','CCCCC','95'],
            ['10004','DDDDD','89'],
            ['10005','EEEEE','96'],
        ];
        Excel::create('学生成绩',function($excel) use ($cellData){
            $excel->sheet('score', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->export('xls');
    }
}
