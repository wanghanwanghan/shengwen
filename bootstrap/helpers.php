<?php

function func_in_helpers_Get_data_in_session($myinput)
{
    $data=\Illuminate\Support\Facades\Session::get('user');
    return $data[0][$myinput];
}

function is_fv_user()
{
    $data=\Illuminate\Support\Facades\Session::get('user');

    $data=explode('_',$data[0]['staff_account']);

    $data=array_shift($data);

    if ($data=='fv')
    {
        return false;
    }else
    {
        return true;
    }
}




























