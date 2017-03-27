<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class ProjectModel extends Model
{
    //定义ORM属性
    protected $table='project';
    protected $primaryKey='project_id';
    //黑名单
    protected $guarded=[];
}
