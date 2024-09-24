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
      Schema::table('farm', function (Blueprint $table) {
        $table->integer('habitatID')->unsigned()->nullable()->after('userID');
        $table->foreign('habitatID')->references('id')->on('habitat')->onDelete('cascade');
        $table->longText('image')->change();
      });

      DB::table('farm')->update(['habitatID' => 1]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('farm', function (Blueprint $table) {
        $table->dropForeign(['habitatID']);
        $table->dropColumn('habitatID');
        $table->text('image')->change();
      });
    }
};
