<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class sys_notification extends Model
{
  use Uuids;

  public $timestamps = false;
  protected $table = 'sys_notification';
  protected $guarded = [];

  protected $casts = [
    'payload' => "array",
    'createdAt' => "timestamp",
    'updatedAt' => "timestamp",
  ];

  public function device()
  {
    return $this->belongsTo('App\Models\sys_device', 'deviceID')->select('id', 'userID')->whereIn('status', [1,2]);
  }

  public function user()
  {
    return $this->belongsTo('App\Models\user', 'userID')->select('id', 'username')->whereIn('status', [1,2]);
  }

}

?>
