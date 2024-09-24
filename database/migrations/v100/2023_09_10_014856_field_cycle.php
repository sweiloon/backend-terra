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
      Schema::table('field_cycleTask', function (Blueprint $table) {
        $table->dropForeign(['fieldActivityID']);
        $table->dropIndex('field_cycletask_fieldactivityid_foreign');
      });

      Schema::table('field_activity', function (Blueprint $table) {
        $table->dropForeign(['fieldID']);
        $table->dropForeign(['cropVarietyID']);
        $table->dropIndex('field_activity_fieldid_foreign');
        $table->dropIndex('field_activity_cropvarietyid_foreign');
      });

      Schema::rename('field_activity', 'field_cycle');

      Schema::table('field_cycle', function (Blueprint $table) {
        $table->foreign('fieldID')->references('id')->on('field')->onDelete('cascade');
        $table->foreign('cropVarietyID')->references('id')->on('crop_variety')->onDelete('cascade');
      });

      Schema::table('field_cycleTask', function (Blueprint $table) {
        $table->renameColumn('fieldActivityID', 'fieldCycleID');
        $table->foreign('fieldCycleID')->references('id')->on('field_cycle')->onDelete('cascade');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('field_cycleTask', function (Blueprint $table) {
        $table->dropForeign(['fieldCycleID']);
        $table->dropIndex('field_cycletask_fieldcycleid_foreign');
      });

      Schema::table('field_cycle', function (Blueprint $table) {
        $table->dropForeign(['fieldID']);
        $table->dropForeign(['cropVarietyID']);
        $table->dropIndex('field_cycle_fieldid_foreign');
        $table->dropIndex('field_cycle_cropvarietyid_foreign');
      });

      Schema::rename('field_cycle', 'field_activity');

      Schema::table('field_activity', function (Blueprint $table) {
        $table->foreign('fieldID')->references('id')->on('field')->onDelete('cascade');
        $table->foreign('cropVarietyID')->references('id')->on('crop_variety')->onDelete('cascade');
      });

      Schema::table('field_cycleTask', function (Blueprint $table) {
        $table->renameColumn('fieldCycleID', 'fieldActivityID');
        $table->foreign('fieldActivityID')->references('id')->on('field_activity')->onDelete('cascade');
      });
    }
};
