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
      Schema::create('user_info', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->uuid('userID')->nullable();
        $table->foreign('userID')->references('id')->on('user')->onDelete('cascade');
        $table->string('fullname')->nullable();
        $table->string('mobileNo')->nullable();
        $table->string('email')->nullable();
        $table->string('bizRegNo')->nullable();
        $table->text('address')->nullable();
        $table->text('image')->nullable();
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
      Schema::dropIfExists('user_info');
    }
};
