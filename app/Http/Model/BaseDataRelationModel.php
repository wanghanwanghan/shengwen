<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class BaseDataRelationModel extends Model
{
    //定义ORM属性
    protected $table='basedata_tablename_relation';
    protected $primaryKey='id';
    //黑名单
    protected $guarded=[];
}
