<?php

namespace App\Http\Myclass;

class FingerRegister
{
    //单例模式
    public static $MySingleton;

    //左手小拇指为0，左手无名指为1，依次往下，右手无名指为8，右手小拇指为9
    public $Finger_0;
    public $Finger_1;
    public $Finger_2;
    public $Finger_3;
    public $Finger_4;
    public $Finger_5;
    public $Finger_6;
    public $Finger_7;
    public $Finger_8;
    public $Finger_9;

    //把指静脉模板数据绑定到这个变量里，然后再分配给Finger_[0-9]
    public $FingerTemplateData;

    public function Register(Array $id,Array $template)
    {
        //给指静脉属性赋值
        for ($i=0;$i<count($id);$i++)
        {
            $IdNumber=substr($id[$i],0,1);
            $this->FingerTemplateData[$IdNumber][]=array_shift($template);
        }

        //分配给Finger_[0-9]
        foreach ($this->FingerTemplateData as $key=>$value)
        {
            $AttrName='Finger_'.$key;
            $this->$AttrName=$value;
        }
    }

    public function whichAttrHasData()
    {
        //显示所有指静脉属性，哪些有数据，给前台显示
        for ($i=0;$i<=9;$i++)
        {
            $AttrName='Finger_'.$i;
            if ($this->$AttrName)
            {
                $HasValue[]=$i;
            }
        }

        return $HasValue;
    }

    public function attrToChinese(FingerRegister $fvObj)
    {
        $HasValue=$fvObj->whichAttrHasData();

        foreach ($HasValue as $key=>$value)
        {
            if ($value=='0')
            {
                $chinese[]='左手小拇指';
            }
            if ($value=='1')
            {
                $chinese[]='左手无名指';
            }
            if ($value=='2')
            {
                $chinese[]='左手中指';
            }
            if ($value=='3')
            {
                $chinese[]='左手食指';
            }
            if ($value=='4')
            {
                $chinese[]='左手大拇指';
            }
            if ($value=='5')
            {
                $chinese[]='右手大拇指';
            }
            if ($value=='6')
            {
                $chinese[]='右手食指';
            }
            if ($value=='7')
            {
                $chinese[]='右手中指';
            }
            if ($value=='8')
            {
                $chinese[]='右手无名指';
            }
            if ($value=='9')
            {
                $chinese[]='右手小拇指';
            }
        }

        return $chinese;
    }

    public function clearFingerData()
    {
        //清除所有指静脉信息
        for ($i=0;$i<=9;$i++)
        {
            $AttrName='Finger_'.$i;
            $this->$AttrName=null;
        }
    }

    private function __clone()
    {
    }

    private function __construct()
    {
    }

    public function __set($name, $value)
    {

    }

    public function __call($name, $arguments)
    {
        return '不存在名为'.$name.'的函数，或该函数不可访问';
    }

    public static function __callStatic($name, $arguments)
    {
        return '不存在名为'.$name.'的函数，或该函数不可访问';
    }

    public static function getSingleton()
    {
        if (self::$MySingleton==null)
        {
            self::$MySingleton=new FingerRegister();
        }

        return self::$MySingleton;
    }




































}