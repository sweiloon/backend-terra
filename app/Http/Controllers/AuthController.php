<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Hash;

use App\Helpers\APIHelper;
use App\Models\user;
use App\Models\user_info;
use App\Models\sys_session;
use App\Models\sys_device;
use App\Models\contact;
use App\Models\country;

class AuthController extends Controller
{

  public function checkAccountExist()
  {
    $rules = [
      'username' => 'required',
    ];
    $parameterError = APIHelper::checkParameter($rules);
    if($parameterError) return APIHelper::returnJSON(false, 400, $parameterError);

    $username = request('username');

    $userResult = user::where('username', $username)->whereIn('status', [1,2])->first();
    if($userResult) return APIHelper::returnJSON(true, 200, 'Account already exists.', [ 'accountExist' => true ]);

    return APIHelper::returnJSON(true, 200, 'Account not exists.', [ 'accountExist' => false ]);
  }

  public function register()
  {
    $rules = [
      'countryID' => 'required',
      'username' => 'required',
      'password' => 'required',
      'fullname' => 'required',
    ];
    $parameterError = APIHelper::checkParameter($rules);
    if($parameterError) return APIHelper::returnJSON(false, 400, $parameterError);

    $countryID = request('countryID');
    $username = request('username');
    $password = request('password');
    $fullname = request('fullname');
    $email = request('email');
    $nric = request('nric');
    $companyName = request('companyName');
    $bizRegNo = request('bizRegNo');
    $address = request('address');

    $insertData = [
      'username' => $username,
      'password' => Hash::make($password),
    ];
    $query = user::create($insertData);
    $userID = $query->id;

    $insertData = [
      'userID' => $userID,
      'fullname' => $fullname,
      'mobileNo' => $username,
      'email' => $email,
      'nric' => $nric,
      'companyName' => $companyName,
      'bizRegNo' => $bizRegNo,
      'address' => $address,
      'countryID' => $countryID,
    ];
    user_info::insert($insertData);

    $insertData = [
      'userID' => $userID,
      'countryID' => $countryID,
      'contactNo' => $username,
      'contactName' => $fullname.' ( me )',
    ];

    contact::create($insertData);

    return APIHelper::returnJSON(true, 200, 'Register Successfully');
  }

  public function login()
  {
    $rules = [
      'username' => 'required',
      'password' => 'required',
      'platform' => 'required',
    ];
    $parameterError = APIHelper::checkParameter($rules);
    if($parameterError) return APIHelper::returnJSON(false, 400, $parameterError);

    $username = request('username');
    $password = request('password');
    $platform = request('platform');
    $ipInfo = request('ipInfo');
    $deviceID = request()->header('deviceID');

    $credentials = [
      'username' => $username,
      'password' => $password,
      'status' => 1,
    ];

    $token = Auth::attempt($credentials);
    if(!$token) return APIHelper::returnJSON(false, 403, 'Incorrect Username or Password');

    $insertData = [
      'userID' => Auth::user()->id,
      'platform' => $platform,
      'ipInfo' => $ipInfo ? json_encode($ipInfo) : null,
      'authToken' => $token,
    ];
    sys_session::insert($insertData);

    sys_device::where('id', $deviceID)->update(['userID' => Auth::user()->id]);

    $data = [
      'userID' => Auth::user()->id,
      'countryID' => Auth::user()->userInfo->countryID,
      'token' => $token,
    ];

    return APIHelper::returnJSON(true, 200, 'Login Successfully', $data);
  }

  public function logout()
  {
    $authError = APIHelper::checkAuth();
    if($authError) return APIHelper::returnJSON(false, 401, $authError);

    $headerError = APIHelper::checkHeader();
    if($headerError) return APIHelper::returnJSON(false, 401, $headerError);

    $userID = request()->header('userID');
    $deviceID = request()->header('deviceID');
    $token = Auth::getToken();

    $updateData = [
      'status' => 3,
      'updatedBy' => $userID
    ];
    sys_session::where('authToken', (string)$token)->update($updateData);

    sys_device::where('id', $deviceID)->update(['userID' => null]);

    Auth::logout();

    return APIHelper::returnJSON(true, 200, 'Logout Successfully');
  }

  public function resetPassword()
  {
    $rules = [
      'username' => 'required',
      'password' => 'required',
    ];
    $parameterError = APIHelper::checkParameter($rules);
    if($parameterError) return APIHelper::returnJSON(false, 400, $parameterError);

    $username = request('username');
    $password = request('password');

    $userResult = user::where('username', $username)->whereIn('status', [1,2])->first();
    if(!$userResult) return APIHelper::returnJSON(false, 404, 'Data not found');

    $userID = $userResult->id;

    $updateData = [
      'password' => Hash::make($password),
    ];

    user::where('id', $userID)->update($updateData);

    return APIHelper::returnJSON(true, 200, 'Updated Successfully');
  }

  public function getCountry()
  {
    $status = request('status');
    $limit = request('limit');

    $countryResults = country::whereIn('status', [1,2]);

    if($status) $countryResults = $countryResults->where('status', $status);

    $limit >= 0 ? $countryResults = $countryResults->paginate($limit) : $countryResults = $countryResults->get();
    if(!count($countryResults)) return APIHelper::returnJSON(true, 200, 'Data not found');

    return APIHelper::returnJSON(true, 200, 'Retrieve Successfully', $countryResults);
  }

  public function getCountryDetail($countryID)
  {
    $authError = APIHelper::checkAuth();
    if($authError) return APIHelper::returnJSON(false, 401, $authError);

    $headerError = APIHelper::checkHeader();
    if($headerError) return APIHelper::returnJSON(false, 401, $headerError);

    $countryResult = country::where('id', $countryID);

    $countryResult = $countryResult->first();
    if(!$countryResult) return APIHelper::returnJSON(false, 404, 'Data not found');

    return APIHelper::returnJSON(true, 200, 'Retrieve Successfully', $countryResult);
  }

}
