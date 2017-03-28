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

    //建立和CustModel表的一对多关联
    public function cust()
    {
        return $this->belongsTo('App\Http\Model\CustModel','confirm_pid','cust_num');
        //return $this->hasMany('App\Http\Model\CustModel','cust_num','confirm_pid');
    }
}
