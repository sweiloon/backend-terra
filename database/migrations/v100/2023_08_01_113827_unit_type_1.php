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
      DB::table('unit_type')->where('id', 12)->update([ 'unitType' => 'yield', 'unit' => 'Mt / ha', 'description' => 'Mr per hactare' ]);
      DB::table('field_activityTask')->where('yieldUnitID', 13)->update([ 'yieldUnitID' => 12 ]);
      DB::table('unit_type')->whereIn('id', [13])->delete();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      DB::table('unit_type')->where('id', 12)->update([ 'unitType' => 'price', 'unit' => 'RM', 'description' => 'Ringgit Malaysia' ]);
      DB::table('unit_type')->insert([
        [ 'id' => 13, 'unitType' => 'yield', 'unit' => 'Mt / ha', 'description' => 'Mr per hactare' ],
      ]);
      DB::table('field_activityTask')->where('yieldUnitID', 12)->update([ 'yieldUnitID' => 13 ]);
    }
};
