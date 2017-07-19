<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class SocialInsuranceModel extends Model
{
    //定义ORM属性
    protected $table='social_insurance';
    protected $primaryKey='id';
    //黑名单
    protected $guarded=[];
}
