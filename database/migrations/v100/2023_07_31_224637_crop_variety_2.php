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
        [ 'id' => 21, 'cropTypeID' => '4', 'cropVariety' => 'Arabica', 'task' => null ],
        [ 'id' => 22, 'cropTypeID' => '4', 'cropVariety' => 'Arabica USDA', 'task' => null ],
        [ 'id' => 23, 'cropTypeID' => '4', 'cropVariety' => 'Arabica Katuai', 'task' => null ],
      ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      DB::table('crop_variety')->whereIn('id', [21,22,23])->delete();
    }
};
