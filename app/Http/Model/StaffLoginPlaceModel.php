<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class StaffLoginPlaceModel extends Model
{
    //定义ORM属性
    protected $table='staff_login_place';
    protected $primaryKey='id';
    //黑名单
    protected $guarded=[];
}
