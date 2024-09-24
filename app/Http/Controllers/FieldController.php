<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;

use App\Helpers\APIHelper;

use App\Models\field;
use App\Models\field_activity;
use App\Models\unit_type;
use App\Models\habitat_type;
use App\Models\user_info;

class FieldController extends Controller
{

  public function getField()
  {
    $authError = APIHelper::checkAuth();
    if($authError) return APIHelper::returnJSON(false, 401, $authError);

    $headerError = APIHelper::checkHeader();
    if($headerError) return APIHelper::returnJSON(false, 401, $headerError);

    $userID = request()->header('userID');
    $habitatTypeID = request('habitatTypeID');
    $fieldName = request('fieldName');
    $status = request('status');
    $limit = request('limit');

    $fieldResults = field::with('habitatType', 'contact', 'fieldActivity', 'landSizeUnit', 'totalAreaHarvestedUnit')->whereIn('status', [1,2]);

    if($fieldName) $fieldResults = $fieldResults->where('fieldName', 'like', '%'.$fieldName.'%');
    if($habitatTypeID) $fieldResults = $fieldResults->where('habitatTypeID', $habitatTypeID);
    if($status) $fieldResults = $fieldResults->where('status', $status);

    if($userID) {

      $group = user_info::where('userID', $userID)->first()->group;
      if(is_null($group)) $fieldResults = $fieldResults->where('userID', $userID);

      if($group != 0) {
        $userIDs = user_info::where('group', $group)->pluck('userID')->toArray();
        $fieldResults = $fieldResults->whereIn('userID', $userIDs);
      }

    }

    $fieldResults = $fieldResults->orderBy('createdAt', 'asc');
    $limit >= 0 ? $fieldResults = $fieldResults->paginate($limit) : $fieldResults = $fieldResults->get();
    if(!count($fieldResults)) return APIHelper::returnJSON(true, 200, 'Data not found');

    foreach ($fieldResults as $key => $value) {

      $images = $value->image ? explode(',', $value->image) : [];

      $imageList = [];
      foreach ($images as $imageKey => $imageValue) {
        $imageValueURL = app('url')->asset('/storage/field/'.$value->id.'/'.$imageValue);
        $data = ['image' => $imageValue, 'imageURL' => $imageValueURL];
        array_push($imageList, $data);
      }

      $value->images = $imageList;

    }

    return APIHelper::returnJSON(true, 200, 'Retrieve Successfully', $fieldResults);
  }

  public function getFieldDetail($fieldID)
  {
    $authError = APIHelper::checkAuth();
    if($authError) return APIHelper::returnJSON(false, 401, $authError);

    $headerError = APIHelper::checkHeader();
    if($headerError) return APIHelper::returnJSON(false, 401, $headerError);

    $fieldResult = field::with('habitatType', 'contact', 'fieldActivity', 'landSizeUnit', 'totalAreaHarvestedUnit')->where('id', $fieldID);

    $fieldResult = $fieldResult->first();
    if(!$fieldResult) return APIHelper::returnJSON(false, 404, 'Data not found');

    $images = $fieldResult->image ? explode(',', $fieldResult->image) : [];

    $imageList = [];
    foreach ($images as $imageKey => $imageValue) {
      $imageValueURL = app('url')->asset('/storage/field/'.$fieldResult->id.'/'.$imageValue);
      $data = ['image' => $imageValue, 'imageURL' => $imageValueURL];
      array_push($imageList, $data);
    }

    $fieldResult->images = $imageList;

    return APIHelper::returnJSON(true, 200, 'Retrieve Successfully', $fieldResult);
  }

  public function addField()
  {
    $authError = APIHelper::checkAuth();
    if($authError) return APIHelper::returnJSON(false, 401, $authError);

    $headerError = APIHelper::checkHeader();
    if($headerError) return APIHelper::returnJSON(false, 401, $headerError);

    $rules = [
      'habitatTypeID' => 'required',
      'fieldName' => 'required',
      'latitude' => 'required',
      'longitude' => 'required',
      'address' => 'required',
      'contactID' => 'required',
      'landSize' => 'required',
      'landSizeUnitID' => 'required',
      'status' => 'required',
    ];
    $parameterError = APIHelper::checkParameter($rules);
    if($parameterError) return APIHelper::returnJSON(false, 400, $parameterError);

    $habitatTypeID = request('habitatTypeID');
    $fieldName = request('fieldName');
    $latitude = request('latitude');
    $longitude = request('longitude');
    $address = request('address');
    $contactID = request('contactID');
    $landSize = request('landSize');
    $landSizeUnitID = request('landSizeUnitID');
    $totalAreaHarvested = request('totalAreaHarvested');
    $totalAreaHarvestedUnitID = request('totalAreaHarvestedUnitID');
    $status = request('status');
    $createdBy = request()->header('userID');

    $fieldResult = field::where('userID', $createdBy)->where('fieldName', $fieldName)->whereIn('status', [1,2])->first();
    if($fieldResult) return APIHelper::returnJSON(false, 409, $fieldName.' already exists.');

    $insertData = [
      'userID' => $createdBy,
      'habitatTypeID' => $habitatTypeID,
      'fieldName' => $fieldName,
      'latitude' => $latitude,
      'longitude' => $longitude,
      'address' => $address,
      'contactID' => $contactID,
      'landSize' => $landSize,
      'landSizeUnitID' => $landSizeUnitID,
      'totalAreaHarvested' => $totalAreaHarvested,
      'totalAreaHarvestedUnitID' => $totalAreaHarvestedUnitID,
      'status' => $status,
      'createdBy' => $createdBy,
    ];
    $query = field::create($insertData);
    $fieldID = $query->id;

    $insertData = [
      'fieldID' => $fieldID,
      'fieldActivityName' => $fieldName,
      'address' => $address,
      'contactID' => $contactID,
      'landSize' => $landSize,
      'landSizeUnitID' => $landSizeUnitID,
      'totalAreaHarvested' => $totalAreaHarvested,
      'totalAreaHarvestedUnitID' => $totalAreaHarvestedUnitID,
      'status' => $status,
      'createdBy' => $createdBy,
    ];
    field_activity::create($insertData);

    return APIHelper::returnJSON(true, 200, 'Added Successfully');
  }

  public function editField($fieldID)
  {
    $authError = APIHelper::checkAuth();
    if($authError) return APIHelper::returnJSON(false, 401, $authError);

    $headerError = APIHelper::checkHeader();
    if($headerError) return APIHelper::returnJSON(false, 401, $headerError);

    $rules = [
      'habitatTypeID' => 'required',
      'fieldName' => 'required',
      'latitude' => 'required',
      'longitude' => 'required',
      'address' => 'required',
      'contactID' => 'required',
      'landSize' => 'required',
      'landSizeUnitID' => 'required',
      'status' => 'required',
    ];
    $parameterError = APIHelper::checkParameter($rules);
    if($parameterError) return APIHelper::returnJSON(false, 400, $parameterError);

    $habitatTypeID = request('habitatTypeID');
    $fieldName = request('fieldName');
    $latitude = request('latitude');
    $longitude = request('longitude');
    $address = request('address');
    $contactID = request('contactID');
    $landSize = request('landSize');
    $landSizeUnitID = request('landSizeUnitID');
    $totalAreaHarvested = request('totalAreaHarvested');
    $totalAreaHarvestedUnitID = request('totalAreaHarvestedUnitID');
    $status = request('status');
    $updatedBy = request()->header('userID');

    $fieldResult = field::where('id', $fieldID)->whereIn('status', [1,2])->first();
    if(!$fieldResult) return APIHelper::returnJSON(false, 404, 'Data not found');

    $fieldResult = field::where('userID', $updatedBy)->where('fieldName', $fieldName)->where('id', '!=', $fieldID)->whereIn('status', [1,2])->first();
    if($fieldResult) return APIHelper::returnJSON(false, 409,  $fieldName.' already exists.');

    $updateData = [
      'habitatTypeID' => $habitatTypeID,
      'fieldName' => $fieldName,
      'latitude' => $latitude,
      'longitude' => $longitude,
      'address' => $address,
      'contactID' => $contactID,
      'landSize' => $landSize,
      'landSizeUnitID' => $landSizeUnitID,
      'totalAreaHarvested' => $totalAreaHarvested,
      'totalAreaHarvestedUnitID' => $totalAreaHarvestedUnitID,
      'status' => $status,
      'updatedBy' => $updatedBy,
    ];
    field::where('id', $fieldID)->update($updateData);

    return APIHelper::returnJSON(true, 200, 'Updated Successfully');
  }

  public function deleteField($fieldID)
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

    $fieldResult = field::where('id', $fieldID)->whereIn('status', [1,2])->first();
    if(!$fieldResult) return APIHelper::returnJSON(false, 404, 'Data not found');

    field::where('id', $fieldID)->update($updateData);

    return APIHelper::returnJSON(true, 200, 'Deleted Successfully');
  }

  public function addFieldImage($fieldID)
  {
    $authError = APIHelper::checkAuth();
    if($authError) return APIHelper::returnJSON(false, 401, $authError);

    $headerError = APIHelper::checkHeader();
    if($headerError) return APIHelper::returnJSON(false, 401, $headerError);

    $rules = [
      'image' => 'required',
    ];
    $parameterError = APIHelper::checkParameter($rules);
    if($parameterError) return APIHelper::returnJSON(false, 400, $parameterError);

    $image = request('image');

    $fieldResult = field::where('id', $fieldID)->whereIn('status', [1,2])->first();
    if(!$fieldResult) return APIHelper::returnJSON(false, 404, 'Data not found');

    $uploadPath = 'field/'.$fieldID.'/';
    Storage::disk('public')->makeDirectory($uploadPath);
    Storage::move('temp/'.$image, 'public/'.$uploadPath.$image);

    $images = $fieldResult->image ? explode(',', $fieldResult->image) : [];
    array_push($images, $image);
    $images = implode(',', $images);

    field::where('id', $fieldID)->update([ 'image' => $images ]);

    return APIHelper::returnJSON(true, 200, 'Added Successfully');
  }

  public function deleteFieldImage($fieldID, $fieldImageID)
  {
    $authError = APIHelper::checkAuth();
    if($authError) return APIHelper::returnJSON(false, 401, $authError);

    $headerError = APIHelper::checkHeader();
    if($headerError) return APIHelper::returnJSON(false, 401, $headerError);

    $fieldResult = field::where('id', $fieldID)->whereIn('status', [1,2])->first();
    if(!$fieldResult) return APIHelper::returnJSON(false, 404, 'Data not found');

    $images = $fieldResult->image ? explode(',', $fieldResult->image) : [];

    $index = array_search($fieldImageID, $images);
    if($index === 0 || $index > 0) {
      array_splice($images, $index, 1);
    }

    $images = implode(',', $images);

    field::where('id', $fieldID)->update([ 'image' => $images ]);

    return APIHelper::returnJSON(true, 200, 'Added Successfully');
  }

  public function getUnitType()
  {
    $authError = APIHelper::checkAuth();
    if($authError) return APIHelper::returnJSON(false, 401, $authError);

    $headerError = APIHelper::checkHeader();
    if($headerError) return APIHelper::returnJSON(false, 401, $headerError);

    $unitType = request('unitType');
    $unit = request('unit') ? explode(',', request('unit')) : null;
    $status = request('status');
    $limit = request('limit');

    $unitTypeResults = unit_type::whereIn('status', [1,2]);

    if($unitType) $unitTypeResults = $unitTypeResults->where('unitType', $unitType);
    if($unit) $unitTypeResults = $unitTypeResults->whereIn('unit', $unit);
    if($status) $unitTypeResults = $unitTypeResults->where('status', $status);

    $limit >= 0 ? $unitTypeResults = $unitTypeResults->paginate($limit) : $unitTypeResults = $unitTypeResults->get();
    if(!count($unitTypeResults)) return APIHelper::returnJSON(true, 200, 'Data not found');

    return APIHelper::returnJSON(true, 200, 'Retrieve Successfully', $unitTypeResults);
  }

  public function getHabitatType()
  {
    $authError = APIHelper::checkAuth();
    if($authError) return APIHelper::returnJSON(false, 401, $authError);

    $headerError = APIHelper::checkHeader();
    if($headerError) return APIHelper::returnJSON(false, 401, $headerError);

    $status = request('status');
    $limit = request('limit');

    $habitatTypeResults = habitat_type::whereIn('status', [1,2]);

    if($status) $habitatTypeResults = $habitatTypeResults->where('status', $status);

    $limit >= 0 ? $habitatTypeResults = $habitatTypeResults->paginate($limit) : $habitatTypeResults = $habitatTypeResults->get();
    if(!count($habitatTypeResults)) return APIHelper::returnJSON(true, 200, 'Data not found');

    return APIHelper::returnJSON(true, 200, 'Retrieve Successfully', $habitatTypeResults);
  }

}
