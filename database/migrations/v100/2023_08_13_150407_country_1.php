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
      Schema::table('country', function (Blueprint $table) {
        $table->string('callingCode')->nullable()->after('alpha2');
      });

      DB::table('country')->where('id', 80)->update(['callingCode' => '62']);
      DB::table('country')->where('id', 108)->update(['callingCode' => '60']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('country', function (Blueprint $table) {
        $table->dropColumn('callingCode');
      });
    }
};
