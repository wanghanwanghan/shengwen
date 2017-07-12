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
        $a1=array("red","green");
        $a2=array("blue","red");
        dd(array_unique(array_merge($a1,$a2)));
    }












































}
