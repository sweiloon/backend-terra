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
        $table->uuid('contactID')->nullable()->after('taskTime');
        $table->foreign('contactID')->references('id')->on('contact')->onDelete('cascade');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('field_activityTask', function (Blueprint $table) {
        $table->dropForeign(['countryID']);
        $table->dropColumn('countryID');
      });
    }
};
