<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColInOnlyTianMenTable1 extends Migration
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
        Schema::table('OnlyTianMen', function (Blueprint $table) {

            $table->string('is_error_info','5')->nullable()->after('is_second_reviewnum');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('OnlyTianMen', function (Blueprint $table) {

            $table->dropColumn('is_error_info');

        });
    }
}
