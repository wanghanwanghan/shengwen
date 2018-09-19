<?php

namespace App\Http\Model\zfsq_model;

use Illuminate\Database\Eloquent\Model;

class Publish_baoxian extends Model
{
    //定义ORM属性
    protected $table='zfsq_baoxian';
    protected $primaryKey='id';
    //黑名单
    protected $guarded=[];
}
