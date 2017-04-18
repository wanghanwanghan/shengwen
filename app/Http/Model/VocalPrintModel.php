<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class VocalPrintModel extends Model
{
    //定义ORM属性
    protected $table='vocalprint';
    protected $primaryKey='vp_pid';
    //黑名单
    protected $guarded=[];
}
