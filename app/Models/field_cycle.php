<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class field_cycle extends Model
{
  use Uuids;

  public $timestamps = false;
  protected $table = 'field_cycle';
  protected $guarded = [];

  protected $casts = [
    'createdAt' => "timestamp",
    'updatedAt' => "timestamp",
  ];

  public function fieldActivity()
  {
    return $this->belongsTo('App\Models\field_activity', 'fieldActivityID')->select('id', 'fieldID', 'fieldActivityName', 'address', 'contactID', 'landSize', 'landSizeUnitID', 'totalAreaHarvested', 'totalAreaHarvestedUnitID', 'remark')->whereIn('status', [1,2]);
  }

  public function cropVariety()
  {
    return $this->belongsTo('App\Models\crop_variety', 'cropVarietyID')->select('id', 'cropTypeID', 'cropVariety', 'task')->whereIn('status', [1,2]);
  }

  public function firstFieldCycleTask()
  {
    return $this->hasOne('App\Models\field_cycleTask', 'fieldCycleID')->select('id', 'fieldCycleID', 'taskTypeID', 'taskName', 'taskDay', 'taskDate', 'taskTime', 'isChecked')->whereIn('status', [1,2])->orderByRaw('-taskDate DESC');
  }

  public function fieldCycleTask()
  {
    return $this->hasOne('App\Models\field_cycleTask', 'fieldCycleID')->select('id', 'fieldCycleID', 'taskTypeID', 'taskName', 'taskDay', 'taskDate', 'taskTime', 'isChecked')->whereIn('status', [1,2])->orderByRaw('-taskDate DESC');
  }
}

?>
