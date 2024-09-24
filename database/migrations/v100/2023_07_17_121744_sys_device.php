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
      Schema::create('sys_device', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->uuid('userID')->nullable();
        $table->foreign('userID')->references('id')->on('user')->onDelete('cascade');
        $table->string('deviceUUID')->nullable();
        $table->integer('deviceType')->nullable()->comment('0 - Default, 1 - Handset, 2 - Tablet');
        $table->integer('osSystem')->nullable()->comment('0 - Default, 1 - iOS, 2 - Android');
        $table->string('osVersion')->nullable();
        $table->string('deviceBrand')->nullable();
        $table->string('deviceModel')->nullable();
        $table->string('appVersion')->nullable();
        $table->string('appBuildNo')->nullable();
        $table->string('pushToken')->nullable();
        $table->integer('badgeCount')->default(0);
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
      Schema::dropIfExists('sys_device');
    }
};
