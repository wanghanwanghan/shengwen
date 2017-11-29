<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColInOnlyTianMenTable4 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('OnlyTianMen', function (Blueprint $table) {

            $table->integer('id_in_ready')->unsigned()->nullable()->index()->after('id_in_mysql');

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

            $table->dropColumn('id_in_ready');

        });
    }
}
