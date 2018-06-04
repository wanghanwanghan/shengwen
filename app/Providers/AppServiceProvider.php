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
                        $v='\''.$bind[$i].'\'';
                        $sql=$this->str_replace_once('?',$v,$sql);


                    }

//                $con=oci_connect('C##wanghan','wanghan','orcl','ZHS16GBK') or die('数据库连接失败');
//                $query=$sql;
//                $stid=oci_parse($con,$query);
//                oci_execute($stid);
//                oci_free_statement($stid);
//                oci_close($con);



                    dd($sql,$bind,'insert');
                }

                if ($cut_string=='update')
                {
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
}
