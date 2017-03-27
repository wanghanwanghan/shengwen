<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class StaffModel extends Model
{
    //定义ORM属性
    protected $table='staff_info';
    protected $primaryKey='staff_num';
    //黑名单
    protected $guarded=[];

}
