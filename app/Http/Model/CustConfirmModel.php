<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class CustConfirmModel extends Model
{
    //定义ORM属性
    protected $table='customer_confirm';
    protected $primaryKey='confirm_num';
    //黑名单
    protected $guarded=[];
}
