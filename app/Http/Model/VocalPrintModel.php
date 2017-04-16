<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class VocalPrintModel extends Model
{
    //定义ORM属性
    protected $table='vocalprint';
    //黑名单
    protected $guarded=[];
}
