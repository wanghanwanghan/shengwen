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

        \DB::listen(function ($query){

            $sql=$query->sql;
            $bind=$query->bindings;
            $time=$query->time;

            \Log::debug(var_export(compact('sql','bind','time'),true));

        });




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
}
