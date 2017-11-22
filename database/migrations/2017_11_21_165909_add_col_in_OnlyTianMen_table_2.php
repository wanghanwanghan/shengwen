<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColInOnlyTianMenTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('OnlyTianMen', function (Blueprint $table) {

            $table->string('btw')->nullable()->after('is_error_info');

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

            $table->dropColumn('btw');

        });
    }
}
