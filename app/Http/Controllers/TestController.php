<?php

namespace App\Http\Controllers;

use App\Http\Model\ProjectModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

class TestController extends Controller
{
    public function test_1()
    {
        $phone='13800138000';
        $res=$this->insert_something($phone,[3,7]);
        dd($res);//返回"138-0013-8000"
    }












































}
