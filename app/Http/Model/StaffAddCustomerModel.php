<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class StaffAddCustomerModel extends Model
{
    //定义ORM属性
    protected $table='staff_add_customer';
    protected $primaryKey='sac_id';
    //黑名单
    protected $guarded=[];
}
