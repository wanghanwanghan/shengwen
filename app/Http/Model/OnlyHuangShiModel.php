<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class OnlyHuangShiModel extends Model
{
    //定义ORM属性
    protected $table='onlyceshi';
    protected $primaryKey='id';
    //黑名单
    protected $guarded=[];
}
