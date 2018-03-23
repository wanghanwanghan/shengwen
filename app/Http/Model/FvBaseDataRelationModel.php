<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class FvBaseDataRelationModel extends Model
{
    //定义ORM属性
    protected $table='base_data_and_cust_fv_data_relation';
    protected $primaryKey='id';
    //黑名单
    protected $guarded=[];
}
