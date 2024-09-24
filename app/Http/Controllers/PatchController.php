<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Helpers\APIHelper;

use App\Models\user;
use App\Models\contact;

class PatchController extends Controller
{

  public function dataPatch()
  {
    $headerError = APIHelper::checkHeader();
    if($headerError) return APIHelper::returnJSON(false, 401, $headerError);

    $results = user::with('userInfo')->whereIn('status', [1,2])->get();

    foreach ($results as $key => $value) {

      $userID = $value->id;
      $contactNo = $value->username;
      $fullname = $value->userInfo->fullname;
      $countryID = $value->userInfo->countryID;
      $createdAt = $value->createdAt;

      $insertData = [
        'userID' => $userID,
        'countryID' => $countryID,
        'contactNo' => $contactNo,
        'contactName' => $fullname.' ( me )',
        'createdAt' => Carbon::parse($createdAt)->format('Y-m-d H:i:s'),
      ];

      contact::create($insertData);

    }

    return APIHelper::returnJSON(true, 200, 'Update Successfully');
  }

}
