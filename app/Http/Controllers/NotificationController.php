<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Log;

use App\Helpers\APIHelper;

use App\Models\sys_notification;

class NotificationController extends Controller
{

  public function getNotification()
  {
    $authError = APIHelper::checkAuth();
    if($authError) return APIHelper::returnJSON(false, 401, $authError);

    $headerError = APIHelper::checkHeader();
    if($headerError) return APIHelper::returnJSON(false, 401, $headerError);

    $deviceID = request('deviceID');
    $userID = request('userID');
    $status = request('status');
    $limit = request('limit');

    $notificationResults = sys_notification::whereIn('status', [1,2]);

    if($deviceID) $notificationResults = $notificationResults->where('deviceID', $deviceID);
    if($userID) $notificationResults = $notificationResults->where('userID', $userID);
    if($status) $notificationResults = $notificationResults->where('status', $status);

    $notificationResults = $notificationResults->orderBy('createdAt', 'desc');
    $limit >= 0 ? $notificationResults = $notificationResults->paginate($limit) : $notificationResults = $notificationResults->get();
    if(!count($notificationResults)) return APIHelper::returnJSON(true, 200, 'Data not found');

    return APIHelper::returnJSON(true, 200, 'Retrieve Successfully', $notificationResults);
  }

}
