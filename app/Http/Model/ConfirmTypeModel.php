<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class ConfirmTypeModel extends Model
{
    //定义ORM属性
    protected $table='confirm_type';
    protected $primaryKey='confirm_id';
    //黑名单
    protected $guarded=[];
}
