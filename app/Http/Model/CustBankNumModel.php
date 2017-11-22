<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class CustBankNumModel extends Model
{
    //定义ORM属性
    protected $table='customer_bank_num';
    protected $primaryKey='id';
    //黑名单
    protected $guarded=[];
}
