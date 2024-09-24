<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\APIHelper;
use Storage;

use App\Models\user;
use App\Models\user_info;

class UserController extends Controller
{

  public function getUser()
  {
    $authError = APIHelper::checkAuth();
    if($authError) return APIHelper::returnJSON(false, 401, $authError);

    $headerError = APIHelper::checkHeader();
    if($headerError) return APIHelper::returnJSON(false, 401, $headerError);

    $userID = request()->header('userID');
    $status = request('status');
    $limit = request('limit');

    $userResults = user::with('userInfo')->whereIn('status', [1,2]);

    if($status) $userResults = $userResults->where('status', $status);

    $userResults = $userResults->orderBy('createdAt', 'asc');
    $limit >= 0 ? $userResults = $userResults->paginate($limit) : $userResults = $userResults->get();
    if(!count($userResults)) return APIHelper::returnJSON(true, 200, 'Data not found');

    foreach ($userResults as $key => $value) {
      $value->userInfo->imageURL = $value->userInfo->image ? app('url')->asset('/storage/user/'.$value->id.'/'.$value->userInfo->image) : null;
    }

    return APIHelper::returnJSON(true, 200, 'Retrieve Successfully', $userResults);
  }

  public function getUserDetail($userID)
  {
    $authError = APIHelper::checkAuth();
    if($authError) return APIHelper::returnJSON(false, 401, $authError);

    $headerError = APIHelper::checkHeader();
    if($headerError) return APIHelper::returnJSON(false, 401, $headerError);

    $userResult = user::with('userInfo')->where('id', $userID);

    $userResult = $userResult->first();
    if(!$userResult) return APIHelper::returnJSON(false, 404, 'Data not found');

    $userResult->userInfo->imageURL = $userResult->userInfo->image ? app('url')->asset('/storage/user/'.$userResult->id.'/'.$userResult->userInfo->image) : null;

    return APIHelper::returnJSON(true, 200, 'Retrieve Successfully', $userResult);
  }

  public function editUser($userID)
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
    $image = request('image');
    $updatedBy = request()->header('userID');

    $userResult = user_info::where('userID', $userID)->whereIn('status', [1,2])->first();
    if(!$userResult) return APIHelper::returnJSON(false, 404, 'Data not found');

    $updateData = [
      'fullname' => $fullname,
      'updatedBy' => $updatedBy,
    ];
    user_info::where('userID', $userID)->update($updateData);

    if($image) {
      $uploadPath = 'user/'.$userID.'/';
      Storage::disk('public')->makeDirectory($uploadPath);
      $contents = Storage::disk('temp')->get($image);
      Storage::move('temp/'.$image, 'public/'.$uploadPath.$image);
      user_info::where('userID', $userID)->update([ 'image' => $image ]);
    }

    return APIHelper::returnJSON(true, 200, 'Updated Successfully');
  }

}
