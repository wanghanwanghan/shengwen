<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class SiTypeModel extends Model
{
    //定义ORM属性
    protected $table='si_type';
    protected $primaryKey='si_id';
    //黑名单
    protected $guarded=[];
}
