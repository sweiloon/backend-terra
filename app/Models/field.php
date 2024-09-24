<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class field extends Model
{
  use Uuids;

  public $timestamps = false;
  protected $table = 'field';
  protected $guarded = [];

  protected $casts = [
    'createdAt' => "timestamp",
    'updatedAt' => "timestamp",
  ];

  public function user()
  {
    return $this->belongsTo('App\Models\user', 'userID')->select('id', 'username')->whereIn('status', [1,2]);
  }

  public function habitatType()
  {
    return $this->belongsTo('App\Models\habitat_type', 'habitatTypeID')->select('id', 'habitatType')->whereIn('status', [1,2]);
  }

  public function contact()
  {
    return $this->belongsTo('App\Models\contact', 'contactID')->select('id', 'userID', 'countryID', 'contactNo', 'contactName')->whereIn('status', [1,2]);
  }

  public function fieldActivity()
  {
    return $this->hasMany('App\Models\field_activity', 'fieldID')->select('id', 'fieldID', 'fieldActivityName', 'address', 'contactID', 'landSize', 'landSizeUnitID', 'totalAreaHarvested', 'totalAreaHarvestedUnitID', 'remark')->whereIn('status', [1,2])->orderBy('createdAt', 'asc');
  }

  public function landSizeUnit()
  {
    return $this->belongsTo('App\Models\unit_type', 'landSizeUnitID')->select('id', 'unitType', 'unit', 'description')->whereIn('status', [1,2]);
  }

  public function totalAreaHarvestedUnit()
  {
    return $this->belongsTo('App\Models\unit_type', 'totalAreaHarvestedUnitID')->select('id', 'unitType', 'unit', 'description')->whereIn('status', [1,2]);
  }
}

?>
