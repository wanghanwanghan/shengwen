<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class LevelModel extends Model
{
    //定义ORM属性
    protected $table='level';
    protected $primaryKey='level_id';
    //黑名单
    protected $guarded=[];
}
