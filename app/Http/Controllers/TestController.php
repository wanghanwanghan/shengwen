<?php

namespace App\Http\Controllers;

use App\Http\Model\CustModel;
use App\Http\Model\LevelModel;
use App\Http\Model\OnlyTianMenModel;
use App\Http\Model\ProjectModel;
use App\Http\Myclass\FingerRegister;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Mockery\Exception;

class TestController extends Controller
{
    public function test_1()
    {


        $rds=CustModel::where(['cust_name'=>'dfdf'])->first();

        dd($rds);








    }
    public function test_2()
    {

        $res=$this->is_idcard('422428460816632');

        dd($res);

        $myfile=fopen(public_path('wanghan.txt'),"r") or die("Unable to open file!");

        $wfile=fopen(public_path('wanghan_new.txt'),"w") or die("Unable to open file!");

        while(!feof($myfile))
        {
            $template=fgets($myfile);

            $template=str_replace(["\r\n","\n"],'',$template);

            $template=explode(',',$template);

            $template_use_insert=$template;

            $template=[$template[1]];

            $arrayKey1=['c_name','si_num','p_name','idcard','sex','birthday','c_day','r_day','bank'];
            $arrayKey=['si_num'];

            $template=array_combine($arrayKey,$template);

            $model=OnlyTianMenModel::where($template)->get()->toArray();

            if (empty($model))
            {
                $txt=implode(',',$template_use_insert)."\r\n";

                fwrite($wfile,$txt);

                $template_use_insert=array_combine($arrayKey1,$template_use_insert);

                $template_use_insert['id_in_mysql']='0';
                $template_use_insert['id_in_ready']=null;
                $template_use_insert['cust_type']='';
                $template_use_insert['is_register']='';
                $template_use_insert['is_second_reviewnum']='';
                $template_use_insert['is_error_info']='';
                $template_use_insert['phone']=null;
                $template_use_insert['btw']=null;

            }else
            {

            }
        }

        fclose($wfile);
        fclose($myfile);

        dd('完成');

    }

    //二维数组排序
    public function sort1(Array $array,Array $cond)
    {
        /*
         * $array=[
            ['name'=>'张1','age'=>'23','totle'=>'1'],
            ['name'=>'李2','age'=>'64','totle'=>'11'],
            ['name'=>'王3','age'=>'55','totle'=>'111'],
            ['name'=>'赵4','age'=>'66','totle'=>'1111'],
            ['name'=>'孙5','age'=>'17','totle'=>'11111']
        ];
        $this->sort1($array,['SORT_ASC','totle'])
         */

        $mysort=null;

        foreach ($array as $k=>$v)
        {
            foreach ($v as $key=>$value)
            {
                $mysort[$key][$k]=$value;
            }
        }

        array_multisort($mysort[$cond[1]],constant($cond[0]),$array);

        return $array;

    }

    //冒泡
    public function sort2(Array $array)
    {
        if (count($array)<=1)
        {
            return $array;
        }

        for ($i=0;$i<count($array);$i++)
        {
            for ($j=$i;$j<=count($array)-1;$j++)
            {
                if ($array[$i]>$array{$j})
                {
                    $tmp=$array[$i];
                    $array[$i]=$array[$j];
                    $array[$j]=$tmp;
                }
            }
        }

        return $array;
    }

    //快速
    public function sort3(Array $array)
    {
        if (count($array)<=1)
        {
            return $array;
        }

        $key=$array[0];
        $left=[];
        $righ=[];

        for ($i=1;$i<count($array);$i++)
        {
            if ($array[$i]<=$key)
            {
                $left[]=$array[$i];
            }else
            {
                $righ[]=$array[$i];
            }
        }

        $left=$this->sort3($left);
        $righ=$this->sort3($righ);

        array_merge($left,$key,$righ);
    }

    //过滤敏感词
    public function filterword($black_word,$a)
    {
        //黑名单词汇
        //$black_word=['','',''];

        $tmp=array_combine($black_word,array_fill(0,count($black_word),'*'));

        //需要过滤的句子
        //$a='';

        return strtr($a,$tmp);
    }

    //反转函数
    public function reverse($arr)
    {
        $n = count($arr);

        $left = 0;
        $right = $n - 1;

        while ($left < $right) {
            $temp = $arr[$left];
            $arr[$left++] = $arr[$right];
            $arr[$right--] = $temp;
        }

        return $arr;
    }

    //打乱数组
    public function custom_shuffle($arr)
    {
        $n = count($arr);
        for ($i = 0; $i < $n; $i++) {
            $rand_pos = mt_rand(0, $n);
            if ($rand_pos != $i) {
                $temp = $arr[$i];
                $arr[$i] = $arr[$rand_pos];
                $arr[$rand_pos] = $temp;
            }
        }
        return $arr;
    }

    //约瑟夫环
    public function JosephProblem($arr,$step=3)
    {
        //计数器
        $num=0;

        while (count($arr)>1)
        {
            //取出一个元素
            $element=array_shift($arr);

            //计数器加一
            $num++;

            if ($num==$step)
            {
                //删除这个元素
                //计数器清零
                $num=0;
            }else
            {
                array_push($arr,$element);
            }
        }

        return $arr;
    }











































}
