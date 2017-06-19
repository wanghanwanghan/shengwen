<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    //导入
    public function import()
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

        //如果通过所有验证
        Excel::load($_FILES['myfile']['tmp_name'],function($reader) {
            dd($reader->all()->toArray());
        });

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
