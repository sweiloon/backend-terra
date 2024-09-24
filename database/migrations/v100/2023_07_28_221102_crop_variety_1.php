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
      DB::table('crop_variety')->insert([
        [ 'id' => 19, 'cropTypeID' => '3', 'cropVariety' => 'COBB500', 'task' => null ],
        [ 'id' => 20, 'cropTypeID' => '3', 'cropVariety' => 'ROSS308', 'task' => null ],
      ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      DB::table('crop_variety')->whereIn('id', [19,20])->delete();
    }
};
