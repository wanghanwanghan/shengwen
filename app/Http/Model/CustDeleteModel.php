<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class CustDeleteModel extends Model
{
    //定义ORM属性
    protected $table='customer_info_delete_use';
    //黑名单
    protected $guarded=[];
}
