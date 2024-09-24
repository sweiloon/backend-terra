<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\APIHelper;

use App\Models\field_cycleTask;

class TaskController extends Controller
{

  public function getTask()
  {
    $authError = APIHelper::checkAuth();
    if($authError) return APIHelper::returnJSON(false, 401, $authError);

    $headerError = APIHelper::checkHeader();
    if($headerError) return APIHelper::returnJSON(false, 401, $headerError);

    $taskDate = request('taskDate');
    $status = request('status');
    $limit = request('limit');

    $cycleTaskResults = field_cycleTask::with('fieldCycle.fieldActivity.field', 'taskType', 'contact', 'yieldUnit', 'productionUnit', 'priceUnit', 'dosageUnit', 'costUnit', 'product')->whereNotNull('fieldCycleID')->whereIn('status', [1,2]);

    $cycleTaskResults = $cycleTaskResults->whereHas('fieldCycle', function($query) { $query->whereIn('status', [1,2]); });
    $cycleTaskResults = $cycleTaskResults->whereHas('fieldCycle.fieldActivity', function($query) { $query->whereIn('status', [1,2]); });
    $cycleTaskResults = $cycleTaskResults->whereHas('fieldCycle.fieldActivity.field', function($query) { $query->whereIn('status', [1,2]); });

    if($taskDate) $cycleTaskResults = $cycleTaskResults->where('taskDate', $taskDate);
    if($status) $cycleTaskResults = $cycleTaskResults->where('status', $status);

    $taskResults = field_cycleTask::with('fieldCycle.fieldActivity.field', 'taskType', 'contact', 'yieldUnit', 'productionUnit', 'priceUnit', 'dosageUnit', 'costUnit', 'product')->whereNull('fieldCycleID')->whereIn('status', [1,2]);

    if($taskDate) $taskResults = $taskResults->where('taskDate', $taskDate);
    if($status) $taskResults = $taskResults->where('status', $status);

    $taskResults = $taskResults->union($cycleTaskResults);
    $taskResults = $taskResults->orderBy('taskTime', 'asc');
    $limit >= 0 ? $taskResults = $taskResults->paginate($limit) : $taskResults = $taskResults->get();
    if(!count($taskResults)) return APIHelper::returnJSON(true, 200, 'Data not found');

    return APIHelper::returnJSON(true, 200, 'Retrieve Successfully', $taskResults);
  }

  public function getTaskDetail($taskID)
  {
    $authError = APIHelper::checkAuth();
    if($authError) return APIHelper::returnJSON(false, 401, $authError);

    $headerError = APIHelper::checkHeader();
    if($headerError) return APIHelper::returnJSON(false, 401, $headerError);

    $taskResult = field_cycleTask::with('fieldCycle', 'taskType', 'contact', 'yieldUnit', 'productionUnit', 'priceUnit', 'dosageUnit', 'costUnit', 'product')->where('id', $taskID);

    $taskResult = $taskResult->first();
    if(!$taskResult) return APIHelper::returnJSON(false, 404, 'Data not found');

    return APIHelper::returnJSON(true, 200, 'Retrieve Successfully', $taskResult);
  }

  public function addTask()
  {
    $authError = APIHelper::checkAuth();
    if($authError) return APIHelper::returnJSON(false, 401, $authError);

    $headerError = APIHelper::checkHeader();
    if($headerError) return APIHelper::returnJSON(false, 401, $headerError);

    $rules = [
      'taskTypeID' => 'required',
      'taskName' => 'required',
      'taskDate' => 'required',
      'taskTime' => 'required',
      'status' => 'required',
    ];
    $parameterError = APIHelper::checkParameter($rules);
    if($parameterError) return APIHelper::returnJSON(false, 400, $parameterError);

    $taskTypeID = request('taskTypeID');
    $taskName = request('taskName');
    $taskDate = request('taskDate');
    $taskTime = request('taskTime');
    $contactID = request('contactID');
    $remark = request('remark');
    $status = request('status');
    $createdBy = request()->header('userID');

    $insertData = [
      'taskTypeID' => $taskTypeID,
      'taskName' => $taskName,
      'taskDate' => $taskDate,
      'taskTime' => $taskTime,
      'contactID' => $contactID,
      'remark' => $remark,
      'status' => $status,
      'createdBy' => $createdBy,
    ];
    field_cycleTask::create($insertData);

    return APIHelper::returnJSON(true, 200, 'Added Successfully');
  }

  public function editTask($taskID)
  {
    $authError = APIHelper::checkAuth();
    if($authError) return APIHelper::returnJSON(false, 401, $authError);

    $headerError = APIHelper::checkHeader();
    if($headerError) return APIHelper::returnJSON(false, 401, $headerError);

    $rules = [
      'taskTypeID' => 'required',
      'taskName' => 'required',
      'taskDate' => 'required',
      'taskTime' => 'required',
      'status' => 'required',
    ];
    $parameterError = APIHelper::checkParameter($rules);
    if($parameterError) return APIHelper::returnJSON(false, 400, $parameterError);

    $taskTypeID = request('taskTypeID');
    $taskName = request('taskName');
    $taskDate = request('taskDate');
    $taskTime = request('taskTime');
    $contactID = request('contactID');
    $remark = request('remark');
    $isChecked = request('isChecked');
    $status = request('status');
    $updatedBy = request()->header('userID');

    $taskResult = field_cycleTask::where('id', $taskID)->whereIn('status', [1,2])->first();
    if(!$taskResult) return APIHelper::returnJSON(false, 404, 'Data not found');

    $updateData = [
      'taskTypeID' => $taskTypeID,
      'taskName' => $taskName,
      'taskDate' => $taskDate,
      'taskTime' => $taskTime,
      'contactID' => $contactID,
      'remark' => $remark,
      'isChecked' => $isChecked,
      'status' => $status,
      'updatedBy' => $updatedBy,
    ];
    field_cycleTask::where('id', $taskID)->update($updateData);

    return APIHelper::returnJSON(true, 200, 'Updated Successfully');
  }

  public function deleteTask($taskID)
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

    $taskResult = field_cycleTask::where('id', $taskID)->whereIn('status', [1,2])->first();
    if(!$taskResult) return APIHelper::returnJSON(false, 404, 'Data not found');

    field_cycleTask::where('id', $taskID)->update($updateData);

    return APIHelper::returnJSON(true, 200, 'Deleted Successfully');
  }

}
