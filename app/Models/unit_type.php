<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class unit_type extends Model
{
  public $timestamps = false;
  protected $table = 'unit_type';
  protected $guarded = [];

  protected $casts = [
    'createdAt' => "timestamp",
    'updatedAt' => "timestamp",
  ];
}

?>
