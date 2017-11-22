<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerBankNumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_bank_num', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cust_id','60')->nullable()->index();
            $table->string('cust_bank_num','60')->nullable()->index();
            $table->timestamps();
            $table->engine='innodb';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('customer_bank_num');
    }
}
