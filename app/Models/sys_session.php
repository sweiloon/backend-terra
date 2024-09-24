<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class sys_session extends Model
{
  public $timestamps = false;
  protected $table = 'sys_session';
  protected $guarded = [];

  protected $casts = [
    'ipInfo' => "array",
    'createdAt' => "timestamp",
    'updatedAt' => "timestamp",
  ];

  public function user()
  {
    return $this->belongsTo('App\Models\user', 'userID')->select('id', 'username')->whereIn('status', [1,2]);
  }
}

?>
