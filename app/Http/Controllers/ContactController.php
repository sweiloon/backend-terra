<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;

use App\Helpers\APIHelper;

use App\Models\contact;
use App\Models\user;
use App\Models\field;
use App\Models\field_activity;
use App\Models\field_cycleTask;

class ContactController extends Controller
{

  public function getContact()
  {
    $authError = APIHelper::checkAuth();
    if($authError) return APIHelper::returnJSON(false, 401, $authError);

    $headerError = APIHelper::checkHeader();
    if($headerError) return APIHelper::returnJSON(false, 401, $headerError);

    $userID = request()->header('userID');
    $includeMe = request('includeMe');
    $contactNo = request('contactNo');
    $contactName = request('contactName');
    $status = request('status');
    $limit = request('limit');

    $contactResults = contact::with('user', 'country')->whereIn('status', [1,2]);

    if($contactNo) $contactResults = $contactResults->where('contactNo', 'like', '%'.$contactNo.'%');
    if($contactName) $contactResults = $contactResults->where('contactName', 'like', '%'.$contactName.'%');
    if($userID) $contactResults = $contactResults->where('userID', $userID);
    if($status) $contactResults = $contactResults->where('status', $status);

    if(!$includeMe) {
      $selfContactNo = user::where('id', $userID)->first()->username;
      $contactResults = $contactResults->where('contactNo', '!=', $selfContactNo);
    }

    $contactResults = $contactResults->orderBy('createdAt', 'asc');
    $limit >= 0 ? $contactResults = $contactResults->paginate($limit) : $contactResults = $contactResults->get();
    if(!count($contactResults)) return APIHelper::returnJSON(true, 200, 'Data not found');

    return APIHelper::returnJSON(true, 200, 'Retrieve Successfully', $contactResults);
  }

  public function getContactDetail($contactID)
  {
    $authError = APIHelper::checkAuth();
    if($authError) return APIHelper::returnJSON(false, 401, $authError);

    $headerError = APIHelper::checkHeader();
    if($headerError) return APIHelper::returnJSON(false, 401, $headerError);

    $contactResult = contact::with('user', 'country')->where('id', $contactID);

    $contactResult = $contactResult->first();
    if(!$contactResult) return APIHelper::returnJSON(false, 404, 'Data not found');

    return APIHelper::returnJSON(true, 200, 'Retrieve Successfully', $contactResult);
  }

  public function addContact()
  {
    $authError = APIHelper::checkAuth();
    if($authError) return APIHelper::returnJSON(false, 401, $authError);

    $headerError = APIHelper::checkHeader();
    if($headerError) return APIHelper::returnJSON(false, 401, $headerError);

    $rules = [
      'countryID' => 'required',
      'contactNo' => 'required',
      'contactName' => 'required',
      'status' => 'required',
    ];
    $parameterError = APIHelper::checkParameter($rules);
    if($parameterError) return APIHelper::returnJSON(false, 400, $parameterError);

    $countryID = request('countryID');
    $contactNo = request('contactNo');
    $contactName = request('contactName');
    $status = request('status');
    $createdBy = request()->header('userID');

    $insertData = [
      'userID' => $createdBy,
      'countryID' => $countryID,
      'contactNo' => $contactNo,
      'contactName' => $contactName,
      'status' => $status,
      'createdBy' => $createdBy,
    ];
    contact::create($insertData);

    return APIHelper::returnJSON(true, 200, 'Added Successfully');
  }

  public function editContact($contactID)
  {
    $authError = APIHelper::checkAuth();
    if($authError) return APIHelper::returnJSON(false, 401, $authError);

    $headerError = APIHelper::checkHeader();
    if($headerError) return APIHelper::returnJSON(false, 401, $headerError);

    $rules = [
      'countryID' => 'required',
      'contactNo' => 'required',
      'contactName' => 'required',
      'status' => 'required',
    ];
    $parameterError = APIHelper::checkParameter($rules);
    if($parameterError) return APIHelper::returnJSON(false, 400, $parameterError);

    $countryID = request('countryID');
    $contactNo = request('contactNo');
    $contactName = request('contactName');
    $status = request('status');
    $updatedBy = request()->header('userID');

    $contactResult = contact::where('id', $contactID)->whereIn('status', [1,2])->first();
    if(!$contactResult) return APIHelper::returnJSON(false, 404, 'Data not found');

    $updateData = [
      'countryID' => $countryID,
      'contactNo' => $contactNo,
      'contactName' => $contactName,
      'status' => $status,
      'updatedBy' => $updatedBy,
    ];
    contact::where('id', $contactID)->update($updateData);

    return APIHelper::returnJSON(true, 200, 'Updated Successfully');
  }

  public function deleteContact($contactID)
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

    $contactResult = contact::where('id', $contactID)->whereIn('status', [1,2])->first();
    if(!$contactResult) return APIHelper::returnJSON(false, 404, 'Data not found');

    $fieldResult = field::where('contactID', $contactID)->whereIn('status', [1,2])->first();
    if($fieldResult) return APIHelper::returnJSON(false, 409, 'Failed to delete. Item is in use');

    $fieldActivityResult = field_activity::where('contactID', $contactID)->whereIn('status', [1,2])->first();
    if($fieldActivityResult) return APIHelper::returnJSON(false, 409, 'Failed to delete. Item is in use');

    $fieldCycleTaskResult = field_cycleTask::where('contactID', $contactID)->whereIn('status', [1,2])->first();
    if($fieldCycleTaskResult) return APIHelper::returnJSON(false, 409, 'Failed to delete. Item is in use');

    contact::where('id', $contactID)->update($updateData);

    return APIHelper::returnJSON(true, 200, 'Deleted Successfully');
  }

}
