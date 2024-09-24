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
      Schema::table('sys_notification', function (Blueprint $table) {
        $table->json('payload')->nullable()->after('message');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('sys_notification', function (Blueprint $table) {
        $table->dropColumn('payload');
      });
    }
};
