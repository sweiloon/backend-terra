<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class crop_type extends Model
{
  public $timestamps = false;
  protected $table = 'crop_type';
  protected $guarded = [];

  protected $casts = [
    'createdAt' => "timestamp",
    'updatedAt' => "timestamp",
  ];

  public function habitatType()
  {
    return $this->belongsTo('App\Models\habitat_type', 'habitatTypeID')->select('id', 'habitatType')->whereIn('status', [1,2]);
  }
}

?>
