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
      Schema::create('crop_product', function (Blueprint $table) {
        $table->increments('id');
        $table->string('productType')->nullable();
        $table->string('brand')->nullable();
        $table->string('product')->nullable();
        $table->string('description')->nullable();
        $table->integer('status')->default(1)->comment('0 - Default, 1 - Active, 2 - Inactive, 3 - Deleted');
        $table->string('createdBy')->default(0);
        $table->timestamp('createdAt')->useCurrent();
        $table->string('updatedBy')->default(0);
        $table->timestamp('updatedAt')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
      });

      DB::table('crop_product')->insert([
        [ 'productType' => 'volume', 'brand' => 'Best', 'product' => 'TM Cereal', 'description' => 'Best TM Cereal'  ],
        [ 'productType' => 'volume', 'brand' => 'Best', 'product' => 'TM', 'description' => 'Best TM'  ],
        [ 'productType' => 'volume', 'brand' => 'Best', 'product' => 'Ai73', 'description' => 'Best Ai73'  ],
        [ 'productType' => 'weight', 'brand' => 'Yara', 'product' => 'NPK Fertilizer', 'description' => 'Yara Fertilizer'  ],
      ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::dropIfExists('crop_product');
    }
};
