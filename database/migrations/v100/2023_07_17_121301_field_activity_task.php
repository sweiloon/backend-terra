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
      Schema::create('field_activityTask', function (Blueprint $table) {
        $table->uuid('id')->primary();
        $table->uuid('fieldActivityID')->nullable();
        $table->foreign('fieldActivityID')->references('id')->on('field_activity')->onDelete('cascade');
        $table->integer('taskTypeID')->unsigned()->nullable();
        $table->foreign('taskTypeID')->references('id')->on('task_type')->onDelete('cascade');
        $table->string('taskName')->nullable();
        $table->string('taskDay')->nullable();
        $table->date('taskDate')->nullable();
        $table->string('supplier')->nullable();
        $table->string('yield')->nullable();
        $table->integer('yieldUnitID')->unsigned()->nullable();
        $table->foreign('yieldUnitID')->references('id')->on('unit_type')->onDelete('cascade');
        $table->string('production')->nullable();
        $table->integer('productionUnitID')->unsigned()->nullable();
        $table->foreign('productionUnitID')->references('id')->on('unit_type')->onDelete('cascade');
        $table->string('price')->nullable();
        $table->integer('priceUnitID')->unsigned()->nullable();
        $table->foreign('priceUnitID')->references('id')->on('unit_type')->onDelete('cascade');
        $table->integer('productID')->unsigned()->nullable();
        $table->foreign('productID')->references('id')->on('crop_product')->onDelete('cascade');
        $table->string('dosage')->nullable();
        $table->integer('dosageUnitID')->unsigned()->nullable();
        $table->foreign('dosageUnitID')->references('id')->on('unit_type')->onDelete('cascade');
        $table->string('cost')->nullable();
        $table->integer('costUnitID')->unsigned()->nullable();
        $table->foreign('costUnitID')->references('id')->on('unit_type')->onDelete('cascade');
        $table->string('method')->nullable();
        $table->text('remark')->nullable();
        $table->integer('isChecked')->default(0);
        $table->integer('status')->default(1)->comment('0 - Default, 1 - Active, 2 - Inactive, 3 - Deleted');
        $table->string('createdBy')->default(0);
        $table->timestamp('createdAt')->useCurrent();
        $table->string('updatedBy')->default(0);
        $table->timestamp('updatedAt')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::dropIfExists('field_activityTask');
    }
};
