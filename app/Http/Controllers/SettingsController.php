<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Hash;

use App\Helpers\APIHelper;
use App\Models\user;
use App\Models\user_info;

class SettingsController extends Controller
{

  public function editProfile()
  {
    $authError = APIHelper::checkAuth();
    if($authError) return APIHelper::returnJSON(false, 401, $authError);

    $headerError = APIHelper::checkHeader();
    if($headerError) return APIHelper::returnJSON(false, 401, $headerError);

    $rules = [
      'fullname' => 'required',
    ];
    $parameterError = APIHelper::checkParameter($rules);
    if($parameterError) return APIHelper::returnJSON(false, 400, $parameterError);

    $fullname = request('fullname');
    $email = request('email');
    $nric = request('nric');
    $companyName = request('companyName');
    $bizRegNo = request('bizRegNo');
    $address = request('address');
    $userID = request()->header('userID');

    $userResult = user_info::where('userID', $userID)->whereIn('status', [1,2])->first();
    if(!$userResult) return APIHelper::returnJSON(false, 404, 'Data not found');

    $updateData = [
      'fullname' => $fullname,
      'email' => $email,
      'nric' => $nric,
      'companyName' => $companyName,
      'bizRegNo' => $bizRegNo,
      'address' => $address,
      'updatedBy' => $userID,
    ];
    user_info::where('userID', $userID)->first()->update($updateData);

    return APIHelper::returnJSON(true, 200, 'Updated Successfully');
  }

  public function changePassword()
  {
    $authError = APIHelper::checkAuth();
    if($authError) return APIHelper::returnJSON(false, 401, $authError);

    $headerError = APIHelper::checkHeader();
    if($headerError) return APIHelper::returnJSON(false, 401, $headerError);

    $rules = [
      'oldPassword' => 'required',
      'newPassword' => 'required',
    ];
    $parameterError = APIHelper::checkParameter($rules);
    if($parameterError) return APIHelper::returnJSON(false, 400, $parameterError);

    $oldPassword = request('oldPassword');
    $newPassword = request('newPassword');
    $userID = request()->header('userID');

    $userResult = user::where('id', $userID)->whereIn('status', [1,2])->first();
    if(!$userResult) return APIHelper::returnJSON(false, 404, 'Data not found');

    if(!Hash::check($oldPassword, $userResult->password)){
      return APIHelper::returnJSON(false, 400, 'Incorrect Old Password');
    }

    $updateData = [
      'password' => Hash::make($newPassword),
      'updatedBy' => $userID,
    ];
    user::where('id', $userID)->update($updateData);

    return APIHelper::returnJSON(true, 200, 'Updated Successfully');
  }

}
