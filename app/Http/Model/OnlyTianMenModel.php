<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class OnlyTianMenModel extends Model
{
    //定义ORM属性
    protected $table='OnlyTianMen';
    protected $primaryKey='id';
    //黑名单
    protected $guarded=[];
}
