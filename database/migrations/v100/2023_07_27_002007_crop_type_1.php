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
      Schema::table('crop_type', function (Blueprint $table) {
        $table->integer('habitatID')->unsigned()->nullable()->after('id');
        $table->foreign('habitatID')->references('id')->on('habitat')->onDelete('cascade');
      });

      DB::table('crop_type')->update(['habitatID' => 1]);

      DB::table('crop_type')->insert([
        [ 'id' => 3, 'habitatID' => 2, 'cropType' => 'Broiler Chicken' ],
      ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('crop_type', function (Blueprint $table) {
        $table->dropForeign(['habitatID']);
        $table->dropColumn('habitatID');
      });

      DB::table('crop_type')->where('id', 3)->delete();
    }
};
