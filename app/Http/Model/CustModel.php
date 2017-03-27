<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class CustModel extends Model
{
    //定义ORM属性
    protected $table='customer_info';
    protected $primaryKey='cust_num';
    //黑名单
    protected $guarded=[];
}
