<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class ChinaAllPositionModel extends Model
{
    //定义ORM属性
    protected $table='china_all_position';
    protected $primaryKey='id';
    //黑名单
    protected $guarded=[];
}
