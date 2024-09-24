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
      Schema::table('crop_type', function (Blueprint $table) {
        $table->text('image')->nullable()->after('cropType');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('crop_type', function (Blueprint $table) {
        $table->dropColumn('image');
      });
    }
};
