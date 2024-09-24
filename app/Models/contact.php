<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class contact extends Model
{
  use Uuids;

  public $timestamps = false;
  protected $table = 'contact';
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
