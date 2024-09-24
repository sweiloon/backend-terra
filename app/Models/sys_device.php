<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class sys_device extends Model
{
  public $timestamps = false;
  protected $table = 'sys_device';
  protected $guarded = [];

  protected $casts = [
    'createdAt' => "timestamp",
    'updatedAt' => "timestamp",
  ];

}

?>
