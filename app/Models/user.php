<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class user extends Authenticatable implements JWTSubject
{
  use Notifiable, Uuids;

  public $timestamps = false;
  protected $table = 'user';
  protected $guarded = [];
  protected $hidden = ['password'];

  protected $casts = [
    'createdAt' => "timestamp",
    'updatedAt' => "timestamp",
  ];

  // Rest omitted for brevity

  /**
   * Get the identifier that will be stored in the subject claim of the JWT.
   *
   * @return mixed
   */
  public function getJWTIdentifier()
  {
    return $this->getKey();
  }

  /**
   * Return a key value array, containing any custom claims to be added to the JWT.
   *
   * @return array
   */
  public function getJWTCustomClaims()
  {
    return [];
  }

  public function userInfo()
  {
    return $this->hasOne('App\Models\user_info', 'userID')->select('id', 'userID', 'fullname', 'mobileNo', 'email', 'nric', 'companyName', 'bizRegNo', 'address', 'countryID', 'image')->whereIn('status', [1,2]);
  }

}

?>
