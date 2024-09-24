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
      Schema::create('task_type', function (Blueprint $table) {
        $table->increments('id');
        $table->string('taskType')->nullable();
        $table->integer('status')->default(1)->comment('0 - Default, 1 - Active, 2 - Inactive, 3 - Deleted');
        $table->string('createdBy')->default(0);
        $table->timestamp('createdAt')->useCurrent();
        $table->string('updatedBy')->default(0);
        $table->timestamp('updatedAt')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
      });

      DB::table('task_type')->insert([
        [ 'id' => 1, 'taskType' => 'Sowing' ],
        [ 'id' => 2, 'taskType' => 'Harvest' ],
        [ 'id' => 3, 'taskType' => 'Consumption' ],
        [ 'id' => 99, 'taskType' => 'Others' ],
      ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::dropIfExists('task_type');
    }
};
