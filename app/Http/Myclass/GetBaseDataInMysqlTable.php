<?php

namespace App\Http\Myclass;

use App\Http\Model\BaseDataRelationModel;

class GetBaseDataInMysqlTable
{
    //*****************************************
    //传入一个地区pid，返回该地区的基础数据表名称
    //*****************************************

    //单例模式
    public static $MySingleton;

    //上一个传入的项目id
    protected $lastProj;

    //修改$lastProj
    public function changeLastproj($lastProj)
    {
        $this->lastProj=$lastProj;
    }

    //利用pid查询表名
    public function getTablename()
    {
        if ($this->lastProj=='')
        {
            return '地区pid为空，请先赋值';
        }

        return BaseDataRelationModel::where(['project'=>$this->lastProj])->first();
    }

    private function __clone()
    {
    }

    private function __construct($pid_for_proj)
    {
        if (!$this->lastProj==$pid_for_proj)
        {
            $this->lastProj=$pid_for_proj;
        }
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

    public static function getSingleton($pid_for_proj)
    {
        if (self::$MySingleton==null)
        {
            self::$MySingleton=new GetBaseDataInMysqlTable($pid_for_proj);
        }

        return self::$MySingleton;
    }




































}