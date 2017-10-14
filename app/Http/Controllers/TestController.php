<?php

namespace App\Http\Controllers;

use App\Http\Model\ProjectModel;
use App\Http\Myclass\FingerRegister;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

class TestController extends Controller
{
    public function test_1()
    {
        $myarr=[
            '赵','钱','孙','李','周','吴','郑',
            '王','冯','陈','褚','卫','蒋','沈',
            '韩','杨','朱','秦','尤','许','何',
            '吕','施','张','孔','曹','严','华'
        ];

        $a = 1;
        $b = $a + $a++;

        $a = 1;
        $c = $a + $a + $a++;

        require_once public_path('baidu/AipSpeech.php');


        $aipSpeech=new \AipSpeech(Config::get('constant.APP_ID'),Config::get('constant.API_KEY'),Config::get('constant.SECRET_KEY'));

        //识别本地文件
        //$res=$aipSpeech->asr(file_get_contents('test.pcm'),'pcm',16000,['lan'=>'zh']);
        $res=$aipSpeech->asr(file_get_contents('wanghan123.wav'),'wav',16000,['lan'=>'zh']);
        //$res=$aipSpeech->asr(file_get_contents('wanghan1234.wav'),'wav',8000,['lan'=>'zh']);

        dd($res);
















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
    public function filterword()
    {
        $black_word=['','',''];

        $tmp=array_combine($black_word,array_fill(0,count($black_word),'*'));

        $a='';

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
