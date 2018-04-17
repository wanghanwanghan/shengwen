<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class OnlyNanLingModel extends Model
{
    //定义ORM属性
    protected $table='onlynanling';
    protected $primaryKey='id';
    //黑名单
    protected $guarded=[];
}
