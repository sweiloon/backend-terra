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
      Schema::table('field_cycle', function (Blueprint $table) {
        $table->dropForeign(['fieldID']);
        $table->dropIndex('field_cycle_fieldid_foreign');
      });

      Schema::table('field', function (Blueprint $table) {
        $table->dropForeign(['farmID']);
        $table->dropForeign(['contactID']);
        $table->dropForeign(['fieldSizeUnitID']);
        $table->dropForeign(['totalAreaHarvestedUnitID']);
        $table->dropIndex('field_farmid_foreign');
        $table->dropIndex('field_contactid_foreign');
        $table->dropIndex('field_fieldsizeunitid_foreign');
        $table->dropIndex('field_totalareaharvestedunitid_foreign');
      });

      Schema::rename('field', 'field_activity');

      Schema::table('field_activity', function (Blueprint $table) {
        $table->renameColumn('fieldName', 'fieldActivityName');
        $table->renameColumn('fieldSize', 'landSize');
        $table->renameColumn('fieldSizeUnitID', 'landSizeUnitID');
        $table->foreign('farmID')->references('id')->on('farm')->onDelete('cascade');
        $table->foreign('contactID')->references('id')->on('contact')->onDelete('cascade');
        $table->foreign('landSizeUnitID')->references('id')->on('unit_type')->onDelete('cascade');
        $table->foreign('totalAreaHarvestedUnitID')->references('id')->on('unit_type')->onDelete('cascade');
      });

      Schema::table('field_cycle', function (Blueprint $table) {
        $table->renameColumn('fieldID', 'fieldActivityID');
        $table->foreign('fieldActivityID')->references('id')->on('field_activity')->onDelete('cascade');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('field_cycle', function (Blueprint $table) {
        $table->dropForeign(['fieldActivityID']);
        $table->dropIndex('field_cycle_fieldactivityid_foreign');
      });

      Schema::table('field_activity', function (Blueprint $table) {
        $table->dropForeign(['farmID']);
        $table->dropForeign(['contactID']);
        $table->dropForeign(['landSizeUnitID']);
        $table->dropForeign(['totalAreaHarvestedUnitID']);
        $table->dropIndex('field_activity_farmid_foreign');
        $table->dropIndex('field_activity_contactid_foreign');
        $table->dropIndex('field_activity_landsizeunitid_foreign');
        $table->dropIndex('field_activity_totalareaharvestedunitid_foreign');
      });

      Schema::rename('field_activity', 'field');

      Schema::table('field', function (Blueprint $table) {
        $table->renameColumn('fieldActivityName', 'fieldName');
        $table->renameColumn('landSize', 'fieldSize');
        $table->renameColumn('landSizeUnitID', 'fieldSizeUnitID');
        $table->foreign('farmID')->references('id')->on('farm')->onDelete('cascade');
        $table->foreign('contactID')->references('id')->on('contact')->onDelete('cascade');
        $table->foreign('fieldSizeUnitID')->references('id')->on('unit_type')->onDelete('cascade');
        $table->foreign('totalAreaHarvestedUnitID')->references('id')->on('unit_type')->onDelete('cascade');
      });

      Schema::table('field_cycle', function (Blueprint $table) {
        $table->renameColumn('fieldActivityID', 'fieldID');
        $table->foreign('fieldID')->references('id')->on('field')->onDelete('cascade');
      });
    }
};
