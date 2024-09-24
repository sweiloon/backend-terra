<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class task_type extends Model
{
  public $timestamps = false;
  protected $table = 'task_type';
  protected $guarded = [];

  protected $casts = [
    'createdAt' => "timestamp",
    'updatedAt' => "timestamp",
  ];
}

?>
