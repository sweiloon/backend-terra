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
      Schema::create('sys_notification', function (Blueprint $table) {
        $table->uuid('id')->primary();
        $table->bigInteger('deviceID')->unsigned()->nullable();
        $table->foreign('deviceID')->references('id')->on('sys_device')->onDelete('cascade');
        $table->uuid('userID')->nullable();
        $table->foreign('userID')->references('id')->on('user')->onDelete('cascade');
        $table->string('title')->nullable();
        $table->text('message')->nullable();
        $table->integer('read')->default(0)->comment('0 - No, 1 - Yes');
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
      Schema::dropIfExists('sys_notification');
    }
};
