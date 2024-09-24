<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class sms_otp extends Model
{
  public $timestamps = false;
  protected $table = 'sms_otp';
  protected $guarded = [];

  protected $casts = [
    'createdAt' => "timestamp",
    'updatedAt' => "timestamp",
  ];

}

?>
