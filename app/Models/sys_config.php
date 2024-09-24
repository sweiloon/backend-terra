<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class sys_config extends Model
{
  public $timestamps = false;
  protected $table = 'sys_config';
  protected $guarded = [];

  protected $casts = [
    'createdAt' => "timestamp",
    'updatedAt' => "timestamp",
  ];

}

?>
