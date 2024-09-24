<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class habitat_type extends Model
{
  public $timestamps = false;
  protected $table = 'habitat_type';
  protected $guarded = [];

  protected $casts = [
    'createdAt' => "timestamp",
    'updatedAt' => "timestamp",
  ];
}

?>
