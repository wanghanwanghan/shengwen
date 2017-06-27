<?php

namespace App\Http\Controllers;

use App\Http\Model\ChinaAllPositionModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
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
            session()->flash('danger',$msg);
            return redirect()->back();
        }

        if ($_FILES['myfile']['type']!='application/vnd.ms-excel' || substr($_FILES['myfile']['name'],-4)!='.xls')
        {
            session()->flash('warning','导入的文件格式不正确！');
            return redirect()->back();
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

                //这个info打算建立数据表用
                $id_info=ChinaAllPositionModel::where(['county_name'=>$this_county,'town_name'=>$this_town,'village_name'=>$this_village])
                    ->get(['county_id','town_id','village_id'])
                    ->toArray();

                if (empty($id_info))
                {
                    session()->flash('warning','第'.++$line.'行county，town，village未找到，请重新确认');
                    return redirect()->back();
                }
            }

            if ($row['county']!=$this_county)
            {
                session()->flash('warning','第'.++$line.'行county列里的'.$row['county'].'不属于'.$this_county);
                return redirect()->back();
            }
            if ($row['town']!=$this_town)
            {
                session()->flash('warning','第'.++$line.'行town列里的'.$row['town'].'不属于'.$this_town);
                return redirect()->back();
            }
            if ($row['village']!=$this_village)
            {
                session()->flash('warning','第'.++$line.'行village列里的'.$row['village'].'不属于'.$this_village);
                return redirect()->back();
            }

            $line++;
        }

        $table_name=$id_info[0]['county_id'].'_'.$id_info[0]['town_id'].'_'.$id_info[0]['village_id'];

        //创建mysql表
        if(!Schema::hasTable($table_name))
        {
            Schema::create($table_name,function ($table)
            {
                $table->increments('id');
                $table->string('county','20');
                $table->string('town','20');
                $table->string('village','20');
                $table->string('name','6');
                $table->string('idcard','18')->unique();
                $table->string('status','6');
                $table->engine='innodb';
            });
        }

        //判断身份证是否已经被插入过了
        $idcard_arr=DB::table($table_name)->pluck('idcard')->toArray();

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

        $redis_key='excel_'.time();
        Redis::set($redis_key,json_encode($file_content));

        //插入数据，这个动作要发送给另一个程序，不然会超时
        file_get_contents('http://zbxl.com/insert_excel_data_1?table_name='.$table_name.'&redis_key='.$redis_key);

        session()->flash('success','导入成功');
        return redirect()->back();
    }

    //导入各个地区的客户数据
    public function insert_excel_data_1()
    {
        $table_name=isset($_GET['table_name']) ? $_GET['table_name'] : '';
        $redis_key=isset($_GET['redis_key']) ? $_GET['redis_key'] : '';

        if ($table_name!='' && $redis_key!='')
        {
            $file_content=json_decode(Redis::get($redis_key),true);

            DB::table($table_name)->insert($file_content);
        }else
        {

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
