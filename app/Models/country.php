<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class country extends Model
{
  public $timestamps = false;
  protected $table = 'country';
  protected $guarded = [];

  protected $casts = [
    'createdAt' => "timestamp",
    'updatedAt' => "timestamp",
  ];
}

?>
