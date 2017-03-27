<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class SendMailModel extends Model
{
    //定义ORM属性
    protected $table='staff_mail';
    protected $primaryKey='mail_id';
    //黑名单
    protected $guarded=[];
}
