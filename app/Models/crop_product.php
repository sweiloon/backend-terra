<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class crop_product extends Model
{
  public $timestamps = false;
  protected $table = 'crop_product';

  protected $casts = [
    'createdAt' => "timestamp",
    'updatedAt' => "timestamp",
  ];
}

?>
