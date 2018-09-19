<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class TextIndependentModel extends Model
{
    //定义ORM属性
    protected $table='text_independent';
    protected $primaryKey='ti_pid';
    //黑名单
    protected $guarded=[];
}
