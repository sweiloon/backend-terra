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
      Schema::create('crop_variety', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('cropTypeID')->unsigned()->nullable();
        $table->foreign('cropTypeID')->references('id')->on('crop_type')->onDelete('cascade');
        $table->string('cropVariety')->nullable();
        $table->json('task')->nullable();
        $table->integer('status')->default(1)->comment('0 - Default, 1 - Active, 2 - Inactive, 3 - Deleted');
        $table->string('createdBy')->default(0);
        $table->timestamp('createdAt')->useCurrent();
        $table->string('updatedBy')->default(0);
        $table->timestamp('updatedAt')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
      });

      DB::table('crop_variety')->insert([
        [ 'cropTypeID' => '1', 'cropVariety' => 'MR297', 'task' => '[{"taskTypeID":"99", "taskType":"Plowing", "day":"-15d ~ -10d"},{"taskTypeID":"99", "taskType":"Soil Leveling", "day":"-10d ~ -1d"},{"taskTypeID":"1", "taskType":"Sowing", "day":"0d"},{"taskTypeID":"2", "taskType":"Harvest", "day":"120d"}]' ],
        [ 'cropTypeID' => '1', 'cropVariety' => 'CL02', 'task' => null ],
        [ 'cropTypeID' => '1', 'cropVariety' => 'CU282', 'task' => null ],
        [ 'cropTypeID' => '1', 'cropVariety' => 'CL02 Sah', 'task' => null ],
        [ 'cropTypeID' => '1', 'cropVariety' => 'MR297 Daftar', 'task' => null ],
        [ 'cropTypeID' => '2', 'cropVariety' => 'MSK', 'task' => null ],
        [ 'cropTypeID' => '2', 'cropVariety' => 'D24', 'task' => null ],
        [ 'cropTypeID' => '2', 'cropVariety' => 'D101', 'task' => null ],
        [ 'cropTypeID' => '1', 'cropVariety' => 'MR303 sah', 'task' => null ],
        [ 'cropTypeID' => '1', 'cropVariety' => 'MR303 daftar', 'task' => null ],
        [ 'cropTypeID' => '1', 'cropVariety' => 'MR307 sah', 'task' => null ],
        [ 'cropTypeID' => '1', 'cropVariety' => 'MR307 daftar', 'task' => null ],
        [ 'cropTypeID' => '1', 'cropVariety' => 'MR269 sah', 'task' => null ],
        [ 'cropTypeID' => '1', 'cropVariety' => 'MR269 daftar', 'task' => null ],
        [ 'cropTypeID' => '1', 'cropVariety' => 'MR297 sah', 'task' => null ],
        [ 'cropTypeID' => '1', 'cropVariety' => 'MR297 daftar', 'task' => null ],
        [ 'cropTypeID' => '1', 'cropVariety' => 'MRQ76 sah', 'task' => null ],
        [ 'cropTypeID' => '1', 'cropVariety' => 'MRQ76 daftar', 'task' => null ],
      ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::dropIfExists('crop_variety');
    }
};
