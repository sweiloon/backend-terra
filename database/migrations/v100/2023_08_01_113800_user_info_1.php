<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('user_info', function (Blueprint $table) {
        $table->integer('countryID')->unsigned()->nullable()->after('address');
        $table->foreign('countryID')->references('id')->on('country')->onDelete('cascade');
      });

      DB::table('user_info')->update(['countryID' => 108]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('user_info', function (Blueprint $table) {
        $table->dropForeign(['countryID']);
        $table->dropColumn('countryID');
      });
    }
};
