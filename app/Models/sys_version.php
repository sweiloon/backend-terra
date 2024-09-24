<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class sys_version extends Model
{
  public $timestamps = false;
  protected $table = 'sys_version';
  protected $guarded = [];

  protected $casts = [
    'createdAt' => "timestamp",
    'updatedAt' => "timestamp",
  ];

}

?>
