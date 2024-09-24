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
      DB::table('crop_variety')->where('id', '!=', 1)->whereIn('cropTypeID', [1,2,4,5,6])->update(['task' => '[{"taskTypeID":"1", "taskType":"Sowing", "day":""},{"taskTypeID":"2", "taskType":"Harvest", "day":""}]']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      DB::table('crop_variety')->where('id', '!=', 1)->whereIn('cropTypeID', [1,2,4,5,6])->update(['task' => NULL]);
    }
};
