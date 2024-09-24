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
      Schema::create('field_activity', function (Blueprint $table) {
        $table->uuid('id')->primary();
        $table->uuid('fieldID')->nullable();
        $table->foreign('fieldID')->references('id')->on('field')->onDelete('cascade');
        $table->integer('cropVarietyID')->unsigned()->nullable();
        $table->foreign('cropVarietyID')->references('id')->on('crop_variety')->onDelete('cascade');
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
      Schema::dropIfExists('field_activity');
    }
};
