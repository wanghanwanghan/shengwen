<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOnlyTianMenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
         * 导入：1、单位名称；2、个人编号‘3、个人姓名；4、身份证编号’5、性别；6、出生年月；7、参工日期；8、离休日期；9、银行卡账号（卡号）。
         */
        Schema::create('OnlyTianMen', function (Blueprint $table) {
            $table->increments('id');
            $table->string('c_name','60')->index();
            $table->string('si_num','30')->index();
            $table->string('p_name','30')->index();
            $table->string('idcard','30')->index();
            $table->string('sex','5')->index();
            $table->string('birthday','30')->index();
            $table->string('c_day','30')->index();
            $table->string('r_day','30')->index();
            $table->string('bank','30')->index();
            $table->integer('id_in_mysql')->unsigned()->nullable()->index();
            $table->string('cust_type','5')->nullable()->index();
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
        Schema::drop('OnlyTianMen');
    }
}
