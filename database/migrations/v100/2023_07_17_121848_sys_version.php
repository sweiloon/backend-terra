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
      Schema::create('sys_version', function (Blueprint $table) {
        $table->increments('id');
        $table->string('releaseVersion')->nullable();
        $table->string('releaseBuildNo')->nullable();
        $table->text('releaseNote')->nullable();
        $table->date('releaseDate')->nullable();
        $table->integer('status')->default(1)->comment('0 - Default, 1 - Active, 2 - Inactive, 3 - Deleted');
        $table->string('createdBy')->default(0);
        $table->timestamp('createdAt')->useCurrent();
        $table->string('updatedBy')->default(0);
        $table->timestamp('updatedAt')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
      });

      DB::table('sys_version')->insert([
        [ 'releaseVersion' => '1.0.0', 'releaseBuildNo' => '20230801', 'releaseNote' => 'Hi terra', 'releaseDate' => '2023-08-01' ],
      ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::dropIfExists('sys_version');
    }
};
