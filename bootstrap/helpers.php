<?php

function func_in_helpers_Get_data_in_session($myinput)
{
    $data=\Illuminate\Support\Facades\Session::get('user');
    return $data[0][$myinput];
}




























