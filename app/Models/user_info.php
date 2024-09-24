<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class user_info extends Model
{
  public $timestamps = false;
  protected $table = 'user_info';
  protected $guarded = [];

  protected $casts = [
    'createdAt' => "timestamp",
    'updatedAt' => "timestamp",
  ];

  public function user()
  {
    return $this->belongsTo('App\Models\user', 'userID')->select('id', 'username')->whereIn('status', [1,2]);
  }

  public function country()
  {
    return $this->belongsTo('App\Models\country', 'countryID')->select('id', 'countryName', 'alpha2', 'callingCode', 'currency')->whereIn('status', [1,2]);
  }

}

?>
