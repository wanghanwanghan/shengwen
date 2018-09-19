<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        if (env('NEIMENG_DB_LISTEN','false'))
        {
            \DB::listen(function ($query){

                $sql=$query->sql;
                $bind=$query->bindings;
                $time=$query->time;
                //\Log::debug(var_export(compact('sql','bind','time'),true));


                //dd($sql,$bind,$time);

            });
        }












    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    //只替换一次
    public function str_replace_once($needle, $replace, $haystack)
    {
        $pos = strpos($haystack, $needle);

        if ($pos === false)
        {
            return $haystack;
        }

        return substr_replace($haystack, $replace, $pos, strlen($needle));
    }

    public function return_primary_name($tablename)
    {
        //主键叫id的
        $arr1=
            [
                'zbxl_basedata_tablename_relation','zbxl_base_data_and_cust_fv_data_relation',
                'zbxl_china_all_position','zbxl_customer_bank_num','zbxl_mobile_location'
            ];



        if (in_array($tablename,$arr1))
        {
            return 'id';
        }

        if ($tablename=='zbxl_confirm_type')
        {
            return 'confirm_id';
        }

        if ($tablename=='zbxl_customer_confirm')
        {
            return 'confirm_num';
        }

        if ($tablename=='zbxl_customer_fv_info' || $tablename=='zbxl_customer_info' || $tablename=='zbxl_customer_info_ready_tianmen')
        {
            return 'cust_num';
        }

        if ($tablename=='zbxl_customer_info_delete_use')
        {
            return 'pid';
        }

        if ($tablename=='zbxl_level')
        {
            return 'level_id';
        }

        if ($tablename=='zbxl_log')
        {
            return 'log_id';
        }

        if ($tablename=='zbxl_log')
        {
            return 'log_id';
        }















    }
}
