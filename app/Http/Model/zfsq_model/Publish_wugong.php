<?php

namespace App\Http\Model\zfsq_model;

use Illuminate\Database\Eloquent\Model;

class Publish_wugong extends Model
{
    //定义ORM属性
    protected $table='zfsq_wugong';
    protected $primaryKey='id';
    //黑名单
    protected $guarded=[];
}
