<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class CustBelongTI extends Model
{
    //定义ORM属性
    protected $table='customer_belong_whitch_text_independent';
    protected $primaryKey='id';
    //黑名单
    protected $guarded=[];
}
