<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class field_activity extends Model
{
  use Uuids;

  public $timestamps = false;
  protected $table = 'field_activity';
  protected $guarded = [];

  protected $casts = [
    'createdAt' => "timestamp",
    'updatedAt' => "timestamp",
  ];

  public function field()
  {
    return $this->belongsTo('App\Models\field', 'fieldID')->select('id', 'userID', 'habitatTypeID', 'fieldName', 'latitude', 'longitude', 'address', 'contactID', 'landSize', 'landSizeUnitID', 'totalAreaHarvested', 'totalAreaHarvestedUnitID', 'image')->whereIn('status', [1,2]);
  }

  public function contact()
  {
    return $this->belongsTo('App\Models\contact', 'contactID')->select('id', 'userID', 'countryID', 'contactNo', 'contactName')->whereIn('status', [1,2]);
  }

  public function landSizeUnit()
  {
    return $this->belongsTo('App\Models\unit_type', 'landSizeUnitID')->select('id', 'unitType', 'unit', 'description')->whereIn('status', [1,2]);
  }

  public function totalAreaHarvestedUnit()
  {
    return $this->belongsTo('App\Models\unit_type', 'totalAreaHarvestedUnitID')->select('id', 'unitType', 'unit', 'description')->whereIn('status', [1,2]);
  }

  public function fieldCycle()
  {
    return $this->hasMany('App\Models\field_cycle', 'fieldActivityID')->select('id', 'fieldActivityID', 'cropVarietyID')->whereIn('status', [1,2]);
  }
}

?>
