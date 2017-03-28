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

    //建立和custconfirm表的一对多关联
    public function custconfirm()
    {
        return $this->hasMany('App\Http\Model\CustConfirmModel','confirm_pid','cust_num');
        //return $this->belongsTo('App\Http\Model\CustConfirmModel','cust_num','confirm_pid');
    }
}
