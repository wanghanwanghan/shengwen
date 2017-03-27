<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class LogModel extends Model
{
    //定义ORM属性
    protected $table='log';
    protected $primaryKey='log_id';
    //黑名单
    protected $guarded=[];
}
