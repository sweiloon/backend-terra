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
        $table->dropForeign(['habitatID']);
        $table->dropIndex('farm_habitatid_foreign');
      });

      Schema::table('crop_type', function (Blueprint $table) {
        $table->dropForeign(['habitatID']);
        $table->dropIndex('crop_type_habitatid_foreign');
      });

      Schema::rename('habitat', 'habitat_type');

      Schema::table('habitat_type', function (Blueprint $table) {
        $table->renameColumn('habitat', 'habitatType');
      });

      Schema::table('farm', function (Blueprint $table) {
        $table->renameColumn('habitatID', 'habitatTypeID');
        $table->foreign('habitatTypeID')->references('id')->on('habitat_type')->onDelete('cascade');
      });

      Schema::table('crop_type', function (Blueprint $table) {
        $table->renameColumn('habitatID', 'habitatTypeID');
        $table->foreign('habitatTypeID')->references('id')->on('habitat_type')->onDelete('cascade');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('farm', function (Blueprint $table) {
        $table->dropForeign(['habitatTypeID']);
        $table->dropIndex('farm_habitattypeid_foreign');
      });

      Schema::table('crop_type', function (Blueprint $table) {
        $table->dropForeign(['habitatTypeID']);
        $table->dropIndex('crop_type_habitattypeid_foreign');
      });

      Schema::rename('habitat_type', 'habitat');

      Schema::table('habitat', function (Blueprint $table) {
        $table->renameColumn('habitatType', 'habitat');
      });

      Schema::table('farm', function (Blueprint $table) {
        $table->renameColumn('habitatTypeID', 'habitatID');
        $table->foreign('habitatID')->references('id')->on('habitat')->onDelete('cascade');
      });

      Schema::table('crop_type', function (Blueprint $table) {
        $table->renameColumn('habitatTypeID', 'habitatID');
        $table->foreign('habitatID')->references('id')->on('habitat')->onDelete('cascade');
      });
    }
};
