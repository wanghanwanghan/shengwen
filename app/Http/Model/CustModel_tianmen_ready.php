<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class CustModel_tianmen_ready extends Model
{
    //定义ORM属性
    protected $table='customer_info_ready_tianmen';
    protected $primaryKey='cust_num';
    //黑名单
    protected $guarded=[];
}
