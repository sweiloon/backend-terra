<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\APIHelper;

use App\Models\field_activity;

class FieldActivityController extends Controller
{

  public function getFieldActivity()
  {
    $authError = APIHelper::checkAuth();
    if($authError) return APIHelper::returnJSON(false, 401, $authError);

    $headerError = APIHelper::checkHeader();
    if($headerError) return APIHelper::returnJSON(false, 401, $headerError);

    $fieldID = request('fieldID');
    $status = request('status');
    $limit = request('limit');

    $fieldActivityResults = field_activity::with('contact', 'field', 'landSizeUnit', 'totalAreaHarvestedUnit')->whereIn('status', [1,2]);

    if($fieldID) $fieldActivityResults = $fieldActivityResults->where('fieldID', $fieldID);
    if($status) $fieldActivityResults = $fieldActivityResults->where('status', $status);

    $fieldActivityResults = $fieldActivityResults->orderBy('createdAt', 'asc');
    $limit >= 0 ? $fieldActivityResults = $fieldActivityResults->paginate($limit) : $fieldActivityResults = $fieldActivityResults->get();
    if(!count($fieldActivityResults)) return APIHelper::returnJSON(true, 200, 'Data not found');

    return APIHelper::returnJSON(true, 200, 'Retrieve Successfully', $fieldActivityResults);
  }

  public function getFieldActivityDetail($fieldActivityID)
  {
    $authError = APIHelper::checkAuth();
    if($authError) return APIHelper::returnJSON(false, 401, $authError);

    $headerError = APIHelper::checkHeader();
    if($headerError) return APIHelper::returnJSON(false, 401, $headerError);

    $fieldActivityResult = field_activity::with('contact', 'field', 'landSizeUnit', 'totalAreaHarvestedUnit', 'fieldCycle.cropVariety.cropType')->where('id', $fieldActivityID);

    $fieldActivityResult = $fieldActivityResult->first();
    if(!$fieldActivityResult) return APIHelper::returnJSON(false, 404, 'Data not found');

    return APIHelper::returnJSON(true, 200, 'Retrieve Successfully', $fieldActivityResult);
  }

  public function addFieldActivity()
  {
    $authError = APIHelper::checkAuth();
    if($authError) return APIHelper::returnJSON(false, 401, $authError);

    $headerError = APIHelper::checkHeader();
    if($headerError) return APIHelper::returnJSON(false, 401, $headerError);

    $rules = [
      'fieldID' => 'required',
      'fieldActivityName' => 'required',
      'address' => 'required',
      'contactID' => 'required',
      'landSize' => 'required',
      'landSizeUnitID' => 'required',
      'status' => 'required',
    ];
    $parameterError = APIHelper::checkParameter($rules);
    if($parameterError) return APIHelper::returnJSON(false, 400, $parameterError);

    $fieldID = request('fieldID');
    $fieldActivityName = request('fieldActivityName');
    $address = request('address');
    $contactID = request('contactID');
    $landSize = request('landSize');
    $landSizeUnitID = request('landSizeUnitID');
    $totalAreaHarvested = request('totalAreaHarvested');
    $totalAreaHarvestedUnitID = request('totalAreaHarvestedUnitID');
    $remark = request('remark');
    $status = request('status');
    $createdBy = request()->header('userID');

    $fieldActivityResult = field_activity::where('fieldID', $fieldID)->where('fieldActivityName', $fieldActivityName)->whereIn('status', [1,2])->first();
    if($fieldActivityResult) return APIHelper::returnJSON(false, 409, $fieldActivityName.' already exists.');

    $insertData = [
      'fieldID' => $fieldID,
      'fieldActivityName' => $fieldActivityName,
      'address' => $address,
      'contactID' => $contactID,
      'landSize' => $landSize,
      'landSizeUnitID' => $landSizeUnitID,
      'totalAreaHarvested' => $totalAreaHarvested,
      'totalAreaHarvestedUnitID' => $totalAreaHarvestedUnitID,
      'remark' => $remark,
      'status' => $status,
      'createdBy' => $createdBy,
    ];
    field_activity::create($insertData);

    return APIHelper::returnJSON(true, 200, 'Added Successfully');
  }

  public function editFieldActivity($fieldActivityID)
  {
    $authError = APIHelper::checkAuth();
    if($authError) return APIHelper::returnJSON(false, 401, $authError);

    $headerError = APIHelper::checkHeader();
    if($headerError) return APIHelper::returnJSON(false, 401, $headerError);

    $rules = [
      'fieldActivityName' => 'required',
      'address' => 'required',
      'contactID' => 'required',
      'landSize' => 'required',
      'landSizeUnitID' => 'required',
      'status' => 'required',
    ];
    $parameterError = APIHelper::checkParameter($rules);
    if($parameterError) return APIHelper::returnJSON(false, 400, $parameterError);

    $fieldActivityName = request('fieldActivityName');
    $address = request('address');
    $contactID = request('contactID');
    $landSize = request('landSize');
    $landSizeUnitID = request('landSizeUnitID');
    $totalAreaHarvested = request('totalAreaHarvested');
    $totalAreaHarvestedUnitID = request('totalAreaHarvestedUnitID');
    $remark = request('remark');
    $status = request('status');
    $updatedBy = request()->header('userID');

    $fieldActivityResult = field_activity::where('id', $fieldActivityID)->whereIn('status', [1,2])->first();
    if(!$fieldActivityResult) return APIHelper::returnJSON(false, 404, 'Data not found');

    $fieldID = $fieldActivityResult->fieldID;

    $fieldActivityResult = field_activity::where('fieldID', $fieldID)->where('fieldActivityName', $fieldActivityName)->where('id', '!=', $fieldActivityID)->whereIn('status', [1,2])->first();
    if($fieldActivityResult) return APIHelper::returnJSON(false, 409,  $fieldActivityName.' already exists.');

    $updateData = [
      'fieldActivityName' => $fieldActivityName,
      'address' => $address,
      'contactID' => $contactID,
      'landSize' => $landSize,
      'landSizeUnitID' => $landSizeUnitID,
      'totalAreaHarvested' => $totalAreaHarvested,
      'totalAreaHarvestedUnitID' => $totalAreaHarvestedUnitID,
      'remark' => $remark,
      'status' => $status,
      'updatedBy' => $updatedBy,
    ];
    field_activity::where('id', $fieldActivityID)->update($updateData);

    return APIHelper::returnJSON(true, 200, 'Updated Successfully');
  }

  public function deleteFieldActivity($fieldActivityID)
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

    $fieldActivityResult = field_activity::where('id', $fieldActivityID)->whereIn('status', [1,2])->first();
    if(!$fieldActivityResult) return APIHelper::returnJSON(false, 404, 'Data not found');

    field_activity::where('id', $fieldActivityID)->update($updateData);

    return APIHelper::returnJSON(true, 200, 'Deleted Successfully');
  }

}
