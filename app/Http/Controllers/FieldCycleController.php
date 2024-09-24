<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\APIHelper;

use App\Models\field_cycle;
use App\Models\field_cycleTask;
use App\Models\crop_type;
use App\Models\crop_variety;

class FieldCycleController extends Controller
{

  public function getFieldCycle()
  {
    $authError = APIHelper::checkAuth();
    if($authError) return APIHelper::returnJSON(false, 401, $authError);

    $headerError = APIHelper::checkHeader();
    if($headerError) return APIHelper::returnJSON(false, 401, $headerError);

    $fieldActivityID = request('fieldActivityID');
    $status = request('status');
    $limit = request('limit');

    $fieldCycleResults = field_cycle::with('fieldActivity', 'cropVariety.cropType', 'firstFieldCycleTask')->whereIn('status', [1,2]);

    if($fieldActivityID) $fieldCycleResults = $fieldCycleResults->where('fieldActivityID', $fieldActivityID);
    if($status) $fieldCycleResults = $fieldCycleResults->where('status', $status);

    $limit >= 0 ? $fieldCycleResults = $fieldCycleResults->paginate($limit) : $fieldCycleResults = $fieldCycleResults->get();
    if(!count($fieldCycleResults)) return APIHelper::returnJSON(true, 200, 'Data not found');

    foreach ($fieldCycleResults as $key => $value) {
      $value->cropVariety->cropType->imageURL = $value->cropVariety->cropType->image ? app('url')->asset('/storage/cropType/'.$value->cropVariety->cropTypeID.'/'.$value->cropVariety->cropType->image) : null;
    }

    return APIHelper::returnJSON(true, 200, 'Retrieve Successfully', $fieldCycleResults);
  }

  public function getFieldCycleDetail($fieldCycleID)
  {
    $authError = APIHelper::checkAuth();
    if($authError) return APIHelper::returnJSON(false, 401, $authError);

    $headerError = APIHelper::checkHeader();
    if($headerError) return APIHelper::returnJSON(false, 401, $headerError);

    $fieldCycleResult = field_cycle::with('fieldActivity', 'cropVariety.cropType', 'fieldCycleTask')->where('id', $fieldCycleID);

    $fieldCycleResult = $fieldCycleResult->first();
    if(!$fieldCycleResult) return APIHelper::returnJSON(false, 404, 'Data not found');

    return APIHelper::returnJSON(true, 200, 'Retrieve Successfully', $fieldCycleResult);
  }

  public function addFieldCycle()
  {
    $authError = APIHelper::checkAuth();
    if($authError) return APIHelper::returnJSON(false, 401, $authError);

    $headerError = APIHelper::checkHeader();
    if($headerError) return APIHelper::returnJSON(false, 401, $headerError);

    $rules = [
      'fieldActivityID' => 'required',
      'cropVarietyID' => 'required',
      'status' => 'required',
    ];
    $parameterError = APIHelper::checkParameter($rules);
    if($parameterError) return APIHelper::returnJSON(false, 400, $parameterError);

    $fieldActivityID = request('fieldActivityID');
    $cropVarietyID = request('cropVarietyID');
    $status = request('status');
    $createdBy = request()->header('userID');

    $cropVarietyResult = crop_variety::where('id', $cropVarietyID)->where('status', 1)->first();
    if(!$cropVarietyResult) return APIHelper::returnJSON(false, 404, 'Data not found');

    $task = $cropVarietyResult->task ? $cropVarietyResult->task : [];

    $insertData = [
      'fieldActivityID' => $fieldActivityID,
      'cropVarietyID' => $cropVarietyID,
      'status' => $status,
      'createdBy' => $createdBy,
    ];
    $query = field_cycle::create($insertData);
    $fieldCycleID = $query->id;

    foreach ($task as $key => $value) {
      $taskTypeID = $value['taskTypeID'];
      $taskName = $value['taskType'];
      $taskDay = $value['day'] ? $value['day'] : null;
      $insertData = [
        'fieldCycleID' => $fieldCycleID,
        'taskTypeID' => $taskTypeID,
        'taskName' => $taskName,
        'taskDay' => $taskDay,
        'status' => $status,
        'createdBy' => $createdBy,
      ];
      field_cycleTask::create($insertData);
    }

    return APIHelper::returnJSON(true, 200, 'Added Successfully');
  }

  public function deleteFieldCycle($fieldCycleID)
  {
    $authError = APIHelper::checkAuth();
    if($authError) return APIHelper::returnJSON(false, 401, $authError);

    $headerError = APIHelper::checkHeader();
    if($headerError) return APIHelper::returnJSON(false, 401, $headerError);

    $updatedBy = request()->header('userID');

    $updateData = [
      'status' => 3,
      'updatedBy' => $updatedBy,
    ];

    $fieldCycleResult = field_cycle::where('id', $fieldCycleID)->whereIn('status', [1,2])->first();
    if(!$fieldCycleResult) return APIHelper::returnJSON(false, 404, 'Data not found');

    field_cycle::where('id', $fieldCycleID)->update($updateData);

    return APIHelper::returnJSON(true, 200, 'Deleted Successfully');
  }

  public function getCropType()
  {
    $authError = APIHelper::checkAuth();
    if($authError) return APIHelper::returnJSON(false, 401, $authError);

    $headerError = APIHelper::checkHeader();
    if($headerError) return APIHelper::returnJSON(false, 401, $headerError);

    $habitatTypeID = request('habitatTypeID');
    $status = request('status');
    $limit = request('limit');

    $cropTypeResults = crop_type::whereIn('status', [1,2]);

    if($habitatTypeID) $cropTypeResults = $cropTypeResults->where('habitatTypeID', $habitatTypeID);
    if($status) $cropTypeResults = $cropTypeResults->where('status', $status);

    $limit >= 0 ? $cropTypeResults = $cropTypeResults->paginate($limit) : $cropTypeResults = $cropTypeResults->get();
    if(!count($cropTypeResults)) return APIHelper::returnJSON(true, 200, 'Data not found');

    foreach ($cropTypeResults as $key => $value) {
      $value->imageURL = $value->image ? app('url')->asset('/storage/cropType/'.$value->id.'/'.$value->image) : null;
    }

    return APIHelper::returnJSON(true, 200, 'Retrieve Successfully', $cropTypeResults);
  }

  public function getCropVariety()
  {
    $authError = APIHelper::checkAuth();
    if($authError) return APIHelper::returnJSON(false, 401, $authError);

    $headerError = APIHelper::checkHeader();
    if($headerError) return APIHelper::returnJSON(false, 401, $headerError);

    $cropTypeID = request('cropTypeID');
    $status = request('status');
    $limit = request('limit');

    $cropVarietyResults = crop_variety::whereIn('status', [1,2]);

    if($cropTypeID) $cropVarietyResults = $cropVarietyResults->where('cropTypeID', $cropTypeID);
    if($status) $cropVarietyResults = $cropVarietyResults->where('status', $status);

    $limit >= 0 ? $cropVarietyResults = $cropVarietyResults->paginate($limit) : $cropVarietyResults = $cropVarietyResults->get();
    if(!count($cropVarietyResults)) return APIHelper::returnJSON(true, 200, 'Data not found');

    return APIHelper::returnJSON(true, 200, 'Retrieve Successfully', $cropVarietyResults);
  }

}
