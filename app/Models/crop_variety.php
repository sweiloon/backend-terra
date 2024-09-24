<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class crop_variety extends Model
{
  public $timestamps = false;
  protected $table = 'crop_variety';
  protected $guarded = [];

  protected $casts = [
    'task' => 'array',
    'createdAt' => "timestamp",
    'updatedAt' => "timestamp",
  ];

  public function cropType()
  {
    return $this->belongsTo('App\Models\crop_type', 'cropTypeID')->select('id', 'habitatTypeID', 'cropType', 'image')->whereIn('status', [1,2]);
  }
}

?>
