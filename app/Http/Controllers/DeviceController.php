<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Helpers\APIHelper;

use App\Models\sys_device;

class DeviceController extends Controller
{

  public function addDevice()
  {
    $rules = [
      'deviceUUID' => 'required',
      'deviceType' => 'required',
      'osSystem' => 'required',
      'osVersion' => 'required',
      'deviceBrand' => 'required',
      'deviceModel' => 'required',
      'appVersion' => 'required',
      'appBuildNo' => 'required',
      'status' => 'required',
    ];
    $parameterError = APIHelper::checkParameter($rules);
    if($parameterError) return APIHelper::returnJSON(false, 400, $parameterError);

    $deviceUUID = request('deviceUUID');
    $deviceType = request('deviceType');
    $osSystem = request('osSystem');
    $osVersion = request('osVersion');
    $deviceBrand = request('deviceBrand');
    $deviceModel = request('deviceModel');
    $appVersion = request('appVersion');
    $appBuildNo = request('appBuildNo');
    $status = request('status');

    $deviceResult = sys_device::where('deviceUUID', $deviceUUID)->whereIn('status', [1,2])->first();

    if(!$deviceResult) {

      $insertData = [
        'deviceUUID' => $deviceUUID,
        'deviceType' => $deviceType,
        'osSystem' => $osSystem,
        'osVersion' => $osVersion,
        'deviceBrand' => $deviceBrand,
        'deviceModel' => $deviceModel,
        'appVersion' => $appVersion,
        'appBuildNo' => $appBuildNo,
        'status' => $status,
      ];
      $deviceID = sys_device::insertGetId($insertData);

    }else{

      $deviceID = $deviceResult->id;

      $updateData = [
        'deviceType' => $deviceType,
        'osSystem' => $osSystem,
        'osVersion' => $osVersion,
        'deviceBrand' => $deviceBrand,
        'deviceModel' => $deviceModel,
        'appVersion' => $appVersion,
        'appBuildNo' => $appBuildNo,
      ];
      sys_device::where('id', $deviceID)->update($updateData);

    }

    $data = [
      'deviceID' => $deviceID
    ];

    return APIHelper::returnJSON(true, 200, 'Updated Successfully', $data);
  }

  public function updatePushToken(Request $request)
  {
    $rules = [
      'deviceID' => 'required',
      'pushToken' => 'required',
    ];
    $parameterError = APIHelper::checkParameter($rules);
    if($parameterError) return APIHelper::returnJSON(false, 400, $parameterError);

    $deviceID = $request->input('deviceID');
    $pushToken = $request->input('pushToken');

    $updateData = [
      'pushToken' => $pushToken,
    ];
    sys_device::where('id', $deviceID)->update($updateData);

    return APIHelper::returnJSON(true, 200, 'Updated Successfully');
  }

}
