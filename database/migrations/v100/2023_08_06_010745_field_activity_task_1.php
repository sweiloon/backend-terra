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
        $table->time('taskTime')->nullable()->after('taskDate');
      });

      DB::table('field_activityTask')->whereNotNull('taskDate')->update(['taskTime' => '00:00:00']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('field_activityTask', function (Blueprint $table) {
        $table->dropColumn('taskTime');
      });
    }
};
