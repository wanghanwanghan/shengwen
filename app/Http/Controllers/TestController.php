<?php

namespace App\Http\Controllers;

use App\Http\Model\CustModel;
use App\Http\Model\OnlyHuangShiModel;
use App\Http\Model\OnlyNanLingModel;
use App\Http\Model\OnlyTianMenModel;
use App\Http\Model\TextIndependentModel;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class TestController extends Controller
{
    public function test_1()
    {
//        set_time_limit(0);
//
//        $myfile=fopen(public_path('zzzz.txt'),"r") or die("Unable to open file!");
//
//        while(!feof($myfile))
//        {
//            $template=fgets($myfile);
//            $template=str_replace(["\r\n","\n"],'',$template);
//            $data[]=$template;
//        }
//
//        dd(CustModel::whereIn('cust_id',$data)->get()->toArray());



        dd($this->myrand());
        $res=CustModel::where('cust_review_num','123456')->get();
        dd(count($res));









        if (!$this->check_something(trim($_GET['phonenum']),'phonenumber',null))
        {
            return ['error'=>'1','msg'=>'手机号码输入不正确'];
        }











    }

    public function test_2()
    {
        set_time_limit(0);

        $myfile=fopen(public_path('step1.txt'),"r") or die("Unable to open file!");

        $wfile=fopen(public_path('wanghan_new.txt'),"w") or die("Unable to open file!");

        while(!feof($myfile))
        {
            $template=fgets($myfile);

            $template=str_replace(["\r\n","\n"],'',$template);

            $template=explode(',',$template);

            $mybankRES='123';
            while ($mybankRES!=null)
            {
                $mybank='tianmen_'.substr(md5(time().$template[3]),0,11);
                $mybankRES=OnlyTianMenModel::where('bank',$mybank)->first();
            }

            array_push($template,$mybank);

            $arrayKey=['c_name','si_num','p_name','idcard','sex','birthday','c_day','r_day','bank'];

            //城乡
            //$arrayKey=['p_name','idcard','birthday','sex'];

            $template=array_combine($arrayKey,$template);

            //城乡
            //$template['c_name']='城乡';
            //$template['c_day']='';
            //$template['r_day']='';

//            $mybankRES='123';
//            while ($mybankRES!=null)
//            {
//                $mybank='nanling_'.substr(md5(time().$template['idcard']),0,11);
//                $mybankRES=OnlyNanLingModel::where('bank',$mybank)->first();
//            }

            if($template['bank']=='')
            {
                $template['bank']=$mybank;
            }
            if($template['si_num']=='')
            {
                $template['si_num']=$mybank;
            }

            //$template['bank']='0';
            $template['id_in_mysql']='0';
            $template['id_in_ready']=null;
            $template['cust_type']='';
            $template['is_register']='';
            $template['is_second_reviewnum']='';
            $template['is_error_info']='';
            $template['phone']=null;
            $template['btw']=null;

            //$this->insert_something($template['birthday'],[4,6]);

            OnlyTianMenModel::create($template);
        }

        fclose($wfile);
        fclose($myfile);

        dd('完成oyw');

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
                if ($array[$i]>$array[$j])
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

    //遍历文件夹
    public function my_dir($dir)
    {
        $res=[];

        if($handle=opendir($dir))
        {
            while(($file=readdir($handle))!==false)
            {
                if($file!=".." && $file!=".")
                {
                    //排除根目录
                    if(is_dir($dir."/".$file))
                    {
                        //如果是子文件夹，就进行递归
                        $res[$file]=$this->my_dir($dir."/".$file);
                    }else
                    {
                        //不然就将文件的名字存入数组
                        $res[]=$file;
                    }
                }
            }

            closedir($handle);

            return $res;
        }
    }

    //计算等差为3的数字之和
    public function arithmetic_3($num)
    {
        $is_in_arr=$num%3;

        if ($is_in_arr=='1')
        {
            //说明在1-4-7-10-13-16-19-22.....数列中
            //判断是第几个数字
            $num_pos=explode('.',$num/3);
            $num_pos=array_shift($num_pos)+1;

            $tmp=1;
            $res=$tmp;

            while ($num_pos>1)
            {
                $tmp+=3;

                $res+=$tmp;

                $num_pos--;
            }

            return $res;

        }else
        {
            //不在数列
            return $this->arithmetic_3($num-1);
        }
    }











































}
