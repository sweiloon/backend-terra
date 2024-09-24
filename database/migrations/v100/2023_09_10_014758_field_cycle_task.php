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

      Schema::table('field_activityTask', function (Blueprint $table) {

        $table->dropForeign(['fieldActivityID']);
        $table->dropForeign(['taskTypeID']);
        $table->dropForeign(['contactID']);
        $table->dropForeign(['yieldUnitID']);
        $table->dropForeign(['productionUnitID']);
        $table->dropForeign(['priceUnitID']);
        $table->dropForeign(['productID']);
        $table->dropForeign(['dosageUnitID']);
        $table->dropForeign(['costUnitID']);

        $table->dropIndex('field_activitytask_fieldactivityid_foreign');
        $table->dropIndex('field_activitytask_tasktypeid_foreign');
        $table->dropIndex('field_activitytask_contactid_foreign');
        $table->dropIndex('field_activitytask_yieldunitid_foreign');
        $table->dropIndex('field_activitytask_productionunitid_foreign');
        $table->dropIndex('field_activitytask_priceunitid_foreign');
        $table->dropIndex('field_activitytask_productid_foreign');
        $table->dropIndex('field_activitytask_dosageunitid_foreign');
        $table->dropIndex('field_activitytask_costunitid_foreign');

      });

      Schema::rename('field_activityTask', 'field_cycleTask');

      Schema::table('field_cycleTask', function (Blueprint $table) {
        $table->foreign('fieldActivityID')->references('id')->on('field_activity')->onDelete('cascade');
        $table->foreign('taskTypeID')->references('id')->on('task_type')->onDelete('cascade');
        $table->foreign('contactID')->references('id')->on('contact')->onDelete('cascade');
        $table->foreign('yieldUnitID')->references('id')->on('unit_type')->onDelete('cascade');
        $table->foreign('productionUnitID')->references('id')->on('unit_type')->onDelete('cascade');
        $table->foreign('priceUnitID')->references('id')->on('unit_type')->onDelete('cascade');
        $table->foreign('productID')->references('id')->on('crop_product')->onDelete('cascade');
        $table->foreign('dosageUnitID')->references('id')->on('unit_type')->onDelete('cascade');
        $table->foreign('costUnitID')->references('id')->on('unit_type')->onDelete('cascade');
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

        $table->dropForeign(['fieldActivityID']);
        $table->dropForeign(['taskTypeID']);
        $table->dropForeign(['contactID']);
        $table->dropForeign(['yieldUnitID']);
        $table->dropForeign(['productionUnitID']);
        $table->dropForeign(['priceUnitID']);
        $table->dropForeign(['productID']);
        $table->dropForeign(['dosageUnitID']);
        $table->dropForeign(['costUnitID']);

        $table->dropIndex('field_cycletask_fieldactivityid_foreign');
        $table->dropIndex('field_cycletask_tasktypeid_foreign');
        $table->dropIndex('field_cycletask_contactid_foreign');
        $table->dropIndex('field_cycletask_yieldunitid_foreign');
        $table->dropIndex('field_cycletask_productionunitid_foreign');
        $table->dropIndex('field_cycletask_priceunitid_foreign');
        $table->dropIndex('field_cycletask_productid_foreign');
        $table->dropIndex('field_cycletask_dosageunitid_foreign');
        $table->dropIndex('field_cycletask_costunitid_foreign');

      });

      Schema::rename('field_cycleTask', 'field_activityTask');

      Schema::table('field_activityTask', function (Blueprint $table) {
        $table->foreign('fieldActivityID')->references('id')->on('field_activity')->onDelete('cascade');
        $table->foreign('taskTypeID')->references('id')->on('task_type')->onDelete('cascade');
        $table->foreign('contactID')->references('id')->on('contact')->onDelete('cascade');
        $table->foreign('yieldUnitID')->references('id')->on('unit_type')->onDelete('cascade');
        $table->foreign('productionUnitID')->references('id')->on('unit_type')->onDelete('cascade');
        $table->foreign('priceUnitID')->references('id')->on('unit_type')->onDelete('cascade');
        $table->foreign('productID')->references('id')->on('crop_product')->onDelete('cascade');
        $table->foreign('dosageUnitID')->references('id')->on('unit_type')->onDelete('cascade');
        $table->foreign('costUnitID')->references('id')->on('unit_type')->onDelete('cascade');
      });

    }
};
