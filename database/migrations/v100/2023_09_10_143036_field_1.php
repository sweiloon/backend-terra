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
      Schema::table('field_activity', function (Blueprint $table) {
        $table->dropForeign(['farmID']);
        $table->dropIndex('field_activity_farmid_foreign');
      });

      Schema::table('farm', function (Blueprint $table) {
        $table->dropForeign(['userID']);
        $table->dropForeign(['habitatTypeID']);
        $table->dropForeign(['contactID']);
        $table->dropForeign(['landSizeUnitID']);
        $table->dropForeign(['totalAreaHarvestedUnitID']);
        $table->dropIndex('farm_userid_foreign');
        $table->dropIndex('farm_contactid_foreign');
        $table->dropIndex('farm_landsizeunitid_foreign');
        $table->dropIndex('farm_totalareaharvestedunitid_foreign');
        $table->dropIndex('farm_habitattypeid_foreign');
      });

      Schema::rename('farm', 'field');

      Schema::table('field', function (Blueprint $table) {
        $table->renameColumn('farmName', 'fieldName');
        $table->foreign('userID')->references('id')->on('user')->onDelete('cascade');
        $table->foreign('habitatTypeID')->references('id')->on('habitat_type')->onDelete('cascade');
        $table->foreign('contactID')->references('id')->on('contact')->onDelete('cascade');
        $table->foreign('landSizeUnitID')->references('id')->on('unit_type')->onDelete('cascade');
        $table->foreign('totalAreaHarvestedUnitID')->references('id')->on('unit_type')->onDelete('cascade');
      });

      Schema::table('field_activity', function (Blueprint $table) {
        $table->renameColumn('farmID', 'fieldID');
        $table->foreign('fieldID')->references('id')->on('field')->onDelete('cascade');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('field_activity', function (Blueprint $table) {
        $table->dropForeign(['fieldID']);
        $table->dropIndex('field_activity_fieldid_foreign');
      });

      Schema::table('field', function (Blueprint $table) {
        $table->dropForeign(['userID']);
        $table->dropForeign(['habitatTypeID']);
        $table->dropForeign(['contactID']);
        $table->dropForeign(['landSizeUnitID']);
        $table->dropForeign(['totalAreaHarvestedUnitID']);
        $table->dropIndex('field_userid_foreign');
        $table->dropIndex('field_habitattypeid_foreign');
        $table->dropIndex('field_contactid_foreign');
        $table->dropIndex('field_landsizeunitid_foreign');
        $table->dropIndex('field_totalareaharvestedunitid_foreign');
      });

      Schema::rename('field', 'farm');

      Schema::table('farm', function (Blueprint $table) {
        $table->renameColumn('fieldName', 'farmName');
        $table->foreign('userID')->references('id')->on('user')->onDelete('cascade');
        $table->foreign('habitatTypeID')->references('id')->on('habitat_type')->onDelete('cascade');
        $table->foreign('contactID')->references('id')->on('contact')->onDelete('cascade');
        $table->foreign('landSizeUnitID')->references('id')->on('unit_type')->onDelete('cascade');
        $table->foreign('totalAreaHarvestedUnitID')->references('id')->on('unit_type')->onDelete('cascade');
      });

      Schema::table('field_activity', function (Blueprint $table) {
        $table->renameColumn('fieldID', 'farmID');
        $table->foreign('farmID')->references('id')->on('farm')->onDelete('cascade');
      });
    }
};
