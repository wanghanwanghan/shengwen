<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class OnlyZhaoXianModel extends Model
{
    //定义ORM属性
    protected $table='onlyzhaoxian';
    protected $primaryKey='id';
    //黑名单
    protected $guarded=[];
}
