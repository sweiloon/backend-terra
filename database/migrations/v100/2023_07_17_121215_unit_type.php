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
      Schema::create('unit_type', function (Blueprint $table) {
        $table->increments('id');
        $table->string('unitType')->nullable();
        $table->string('unit')->nullable();
        $table->string('description')->nullable();
        $table->integer('status')->default(1)->comment('0 - Default, 1 - Active, 2 - Inactive, 3 - Deleted');
        $table->string('createdBy')->default(0);
        $table->timestamp('createdAt')->useCurrent();
        $table->string('updatedBy')->default(0);
        $table->timestamp('updatedAt')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
      });

      DB::table('unit_type')->insert([
        [ 'unitType' => 'weight', 'unit' => 'g', 'description' => 'gram' ],
        [ 'unitType' => 'weight', 'unit' => 'kg', 'description' => 'kilogram' ],
        [ 'unitType' => 'weight', 'unit' => 'ton', 'description' => 'tonne' ],
        [ 'unitType' => 'weight', 'unit' => 'Mt', 'description' => 'metric ton' ],
        [ 'unitType' => 'weight', 'unit' => 'lbs', 'description' => 'pound' ],
        [ 'unitType' => 'volume', 'unit' => 'mL', 'description' => 'milliliter' ],
        [ 'unitType' => 'volume', 'unit' => 'Lt', 'description' => 'liter' ],
        [ 'unitType' => 'area', 'unit' => 'ac', 'description' => 'acre' ],
        [ 'unitType' => 'area', 'unit' => 'ha', 'description' => 'hactare' ],
        [ 'unitType' => 'area', 'unit' => 'rlgP', 'description' => 'Lerong Penang' ],
        [ 'unitType' => 'area', 'unit' => 'rlgK', 'description' => 'Lering Kedah' ],
        [ 'unitType' => 'price', 'unit' => 'RM', 'description' => 'Ringgit Malaysia' ],
        [ 'unitType' => 'yield', 'unit' => 'Mt / ha', 'description' => 'Mr per hactare' ],
      ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::dropIfExists('unit_type');
    }
};
