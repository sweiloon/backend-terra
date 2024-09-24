<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\APIHelper;

use App\Models\field_cycleTask;
use App\Models\crop_product;
use App\Models\task_type;

class FieldCycleTaskController extends Controller
{

  public function getFieldCycleTask()
  {
    $authError = APIHelper::checkAuth();
    if($authError) return APIHelper::returnJSON(false, 401, $authError);

    $headerError = APIHelper::checkHeader();
    if($headerError) return APIHelper::returnJSON(false, 401, $headerError);

    $fieldCycleID = request('fieldCycleID');
    $status = request('status');
    $limit = request('limit');

    $fieldCycleTaskResults = field_cycleTask::with('fieldCycle', 'taskType', 'contact', 'yieldUnit', 'productionUnit', 'priceUnit', 'dosageUnit', 'costUnit', 'product')->whereIn('status', [1,2]);

    if($fieldCycleID) $fieldCycleTaskResults = $fieldCycleTaskResults->where('fieldCycleID', $fieldCycleID);
    if($status) $fieldCycleTaskResults = $fieldCycleTaskResults->where('status', $status);

    $fieldCycleTaskResults = $fieldCycleTaskResults->orderByRaw('-taskDate DESC');
    $limit >= 0 ? $fieldCycleTaskResults = $fieldCycleTaskResults->paginate($limit) : $fieldCycleTaskResults = $fieldCycleTaskResults->get();
    if(!count($fieldCycleTaskResults)) return APIHelper::returnJSON(true, 200, 'Data not found');

    return APIHelper::returnJSON(true, 200, 'Retrieve Successfully', $fieldCycleTaskResults);
  }

  public function getFieldCycleTaskDetail($fieldCycleTaskID)
  {
    $authError = APIHelper::checkAuth();
    if($authError) return APIHelper::returnJSON(false, 401, $authError);

    $headerError = APIHelper::checkHeader();
    if($headerError) return APIHelper::returnJSON(false, 401, $headerError);

    $fieldCycleTaskResult = field_cycleTask::with('fieldCycle', 'taskType', 'contact', 'yieldUnit', 'productionUnit', 'priceUnit', 'dosageUnit', 'costUnit', 'product')->where('id', $fieldCycleTaskID);

    $fieldCycleTaskResult = $fieldCycleTaskResult->first();
    if(!$fieldCycleTaskResult) return APIHelper::returnJSON(false, 404, 'Data not found');

    return APIHelper::returnJSON(true, 200, 'Retrieve Successfully', $fieldCycleTaskResult);
  }

  public function addFieldCycleTask()
  {
    $authError = APIHelper::checkAuth();
    if($authError) return APIHelper::returnJSON(false, 401, $authError);

    $headerError = APIHelper::checkHeader();
    if($headerError) return APIHelper::returnJSON(false, 401, $headerError);

    $rules = [
      'fieldCycleID' => 'required',
      'taskTypeID' => 'required',
      'taskName' => 'required',
      'status' => 'required',
    ];
    $parameterError = APIHelper::checkParameter($rules);
    if($parameterError) return APIHelper::returnJSON(false, 400, $parameterError);

    $fieldCycleID = request('fieldCycleID');
    $taskTypeID = request('taskTypeID');
    $taskName = request('taskName');
    $taskDate = request('taskDate');
    $contactID = request('contactID');
    $supplier = request('supplier');
    $yield = request('yield');
    $yieldUnitID = request('yieldUnitID');
    $production = request('production');
    $productionUnitID = request('productionUnitID');
    $price = request('price');
    $priceUnitID = request('priceUnitID');
    $productID = request('productID');
    $dosage = request('dosage');
    $dosageUnitID = request('dosageUnitID');
    $cost = request('cost');
    $costUnitID = request('costUnitID');
    $method = request('method');
    $remark = request('remark');
    $status = request('status');
    $createdBy = request()->header('userID');

    $insertData = [
      'fieldCycleID' => $fieldCycleID,
      'taskTypeID' => $taskTypeID,
      'taskName' => $taskName,
      'taskDate' => $taskDate,
      'taskTime' => $taskDate ? "00:00:00" : null,
      'contactID' => $contactID,
      'supplier' => $supplier,
      'yield' => $yield ? $yield : null,
      'yieldUnitID' => $yieldUnitID,
      'production' => $production,
      'productionUnitID' => $productionUnitID,
      'price' => $price,
      'priceUnitID' => $priceUnitID,
      'productID' => $productID,
      'dosage' => $dosage,
      'dosageUnitID' => $dosageUnitID,
      'cost' => $cost,
      'costUnitID' => $costUnitID,
      'method' => $method,
      'remark' => $remark,
      'status' => $status,
      'createdBy' => $createdBy,
    ];
    field_cycleTask::create($insertData);

    return APIHelper::returnJSON(true, 200, 'Added Successfully');
  }

  public function editFieldCycleTask($fieldCycleTaskID)
  {
    $authError = APIHelper::checkAuth();
    if($authError) return APIHelper::returnJSON(false, 401, $authError);

    $headerError = APIHelper::checkHeader();
    if($headerError) return APIHelper::returnJSON(false, 401, $headerError);

    $rules = [
      'status' => 'required',
    ];
    $parameterError = APIHelper::checkParameter($rules);
    if($parameterError) return APIHelper::returnJSON(false, 400, $parameterError);

    $taskDate = request('taskDate');
    $contactID = request('contactID');
    $supplier = request('supplier');
    $yield = request('yield');
    $yieldUnitID = request('yieldUnitID');
    $production = request('production');
    $productionUnitID = request('productionUnitID');
    $price = request('price');
    $priceUnitID = request('priceUnitID');
    $productID = request('productID');
    $dosage = request('dosage');
    $dosageUnitID = request('dosageUnitID');
    $cost = request('cost');
    $costUnitID = request('costUnitID');
    $method = request('method');
    $remark = request('remark');
    $isChecked = request('isChecked');
    $status = request('status');
    $updatedBy = request()->header('userID');

    $fieldCycleTaskResult = field_cycleTask::where('id', $fieldCycleTaskID)->whereIn('status', [1,2])->first();
    if(!$fieldCycleTaskResult) return APIHelper::returnJSON(false, 404, 'Data not found');

    $updateData = [
      'taskDate' => $taskDate,
      'taskTime' => $taskDate ? "00:00:00" : null,
      'contactID' => $contactID,
      'supplier' => $supplier,
      'yield' => $yield ? $yield : null,
      'yieldUnitID' => $yieldUnitID,
      'production' => $production,
      'productionUnitID' => $productionUnitID,
      'price' => $price,
      'priceUnitID' => $priceUnitID,
      'productID' => $productID,
      'dosage' => $dosage,
      'dosageUnitID' => $dosageUnitID,
      'cost' => $cost,
      'costUnitID' => $costUnitID,
      'method' => $method,
      'remark' => $remark,
      'isChecked' => $isChecked,
      'status' => $status,
      'updatedBy' => $updatedBy,
    ];
    field_cycleTask::where('id', $fieldCycleTaskID)->update($updateData);

    return APIHelper::returnJSON(true, 200, 'Updated Successfully');
  }

  public function deleteFieldCycleTask($fieldCycleTaskID)
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

    $fieldCycleTaskResult = field_cycleTask::where('id', $fieldCycleTaskID)->whereIn('status', [1,2])->first();
    if(!$fieldCycleTaskResult) return APIHelper::returnJSON(false, 404, 'Data not found');

    field_cycleTask::where('id', $fieldCycleTaskID)->update($updateData);

    return APIHelper::returnJSON(true, 200, 'Deleted Successfully');
  }

  public function getCropProduct()
  {
    $authError = APIHelper::checkAuth();
    if($authError) return APIHelper::returnJSON(false, 401, $authError);

    $headerError = APIHelper::checkHeader();
    if($headerError) return APIHelper::returnJSON(false, 401, $headerError);

    $status = request('status');
    $limit = request('limit');

    $cropProductResults = crop_product::whereIn('status', [1,2]);

    if($status) $cropProductResults = $cropProductResults->where('status', $status);

    $limit >= 0 ? $cropProductResults = $cropProductResults->paginate($limit) : $cropProductResults = $cropProductResults->get();
    if(!count($cropProductResults)) return APIHelper::returnJSON(true, 200, 'Data not found');

    return APIHelper::returnJSON(true, 200, 'Retrieve Successfully', $cropProductResults);
  }

  public function getTaskType()
  {
    $authError = APIHelper::checkAuth();
    if($authError) return APIHelper::returnJSON(false, 401, $authError);

    $headerError = APIHelper::checkHeader();
    if($headerError) return APIHelper::returnJSON(false, 401, $headerError);

    $status = request('status');
    $limit = request('limit');

    $taskTypeResults = task_type::whereIn('status', [1,2]);

    if($status) $taskTypeResults = $taskTypeResults->where('status', $status);

    $limit >= 0 ? $taskTypeResults = $taskTypeResults->paginate($limit) : $taskTypeResults = $taskTypeResults->get();
    if(!count($taskTypeResults)) return APIHelper::returnJSON(true, 200, 'Data not found');

    return APIHelper::returnJSON(true, 200, 'Retrieve Successfully', $taskTypeResults);
  }

}
