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

                $cut_string=substr($sql,0,6);

                if ($cut_string=='insert')
                {
                    for ($i=0;$i<count($bind);$i++)
                    {
                        if ($bind[$i]=='')
                        {
                            $v='null';
                        }else
                        {
                            $v='\''.$bind[$i].'\'';
                        }

                        $sql=$this->str_replace_once('?',$v,$sql);

                    }

//                $con=oci_connect('C##wanghan','wanghan','orcl','ZHS16GBK') or die('数据库连接失败');
//                $query=$sql;
//                $stid=oci_parse($con,$query);
//                oci_execute($stid);
//                oci_free_statement($stid);
//                oci_close($con);


                    $sql=$this->str_replace_once('`','"',$sql);
                    $sql=$this->str_replace_once('`','"',$sql);
                    dd($sql,$bind,'insert',explode('"',$sql));
                }

                if ($cut_string=='update')
                {
                    $con=oci_connect('C##wanghan','wanghan','orcl','ZHS16GBK') or die('数据库连接失败');
                    $query='truncate table ZBXL_CUSTOMER_INFO';
                    $stid=oci_parse($con,$query);
                    oci_execute($stid);
                    oci_free_statement($stid);
                    oci_close($con);
                    dd($sql,'update');
                }

                if ($cut_string=='delete')
                {
                    dd($sql,'delete');
                }


//            $con=oci_connect('C##wanghan','wanghan','orcl','ZHS16GBK') or die('数据库连接失败');
//            $query="insert into aa values (:ID)";
//            $stid=oci_parse($con,$query);
//            $ID=123;
//            oci_bind_by_name($stid,':ID',$ID);
//            oci_execute($stid);
//            oci_free_statement($stid);
//            oci_close($con);



                $connect=oci_connect('C##wanghan','wanghan','orcl','ZHS16GBK') or die('数据库连接失败');

                $tmp=oci_parse($connect,'select * from aa');

                oci_execute($tmp);

                while (($res=oci_fetch_assoc($tmp))!=false)
                {
                }

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
