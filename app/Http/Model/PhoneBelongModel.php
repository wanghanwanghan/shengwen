<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class PhoneBelongModel extends Model
{
    //定义ORM属性
    protected $table='mobile_location';
    protected $primaryKey='id';
    //黑名单
    protected $guarded=[];
}
