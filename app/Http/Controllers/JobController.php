<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Log;

use App\Helpers\APIHelper;

use App\Models\user;
use App\Models\contact;
use App\Models\sys_device;
use App\Models\sys_notification;
use App\Models\field_cycleTask;

class JobController extends Controller
{

  public function sendTaskReminder()
  {
    $userID = request()->header('userID');
    $userResult = user::where('id', $userID)->where('status', 1)->first();
    if(!$userResult) return APIHelper::returnJSON(false, 400, 'Authentication Required');

    $currentDate = Carbon::now()->format('Y-m-d');
    $currentTime = Carbon::now()->format('H:i:00');

    $reminderDate1 = Carbon::now()->addDay()->format('Y-m-d');
    $reminderTime1 = Carbon::now()->format('H:i:00');

    $reminderDate2 = Carbon::now()->addHour()->format('Y-m-d');
    $reminderTime2 = Carbon::now()->addHour()->format('H:i:00');

    $taskResults = field_cycleTask::with('fieldCycle', 'taskType', 'contact', 'yieldUnit', 'productionUnit', 'priceUnit', 'dosageUnit', 'costUnit', 'product')->whereNull('fieldCycleID')->whereNotNull('contactID')->where('status', 1);
    $taskResults2 = field_cycleTask::with('fieldCycle', 'taskType', 'contact', 'yieldUnit', 'productionUnit', 'priceUnit', 'dosageUnit', 'costUnit', 'product')->whereNull('fieldCycleID')->whereNotNull('contactID')->where('status', 1);
    $fieldCycleTaskResults = field_cycleTask::with('fieldCycle', 'taskType', 'contact', 'yieldUnit', 'productionUnit', 'priceUnit', 'dosageUnit', 'costUnit', 'product')->whereNotNull('fieldCycleID')->whereNotNull('contactID')->where('status', 1);

    $taskResults1 = $taskResults->where('taskDate', $reminderDate1)->where('taskTime', $reminderTime1);
    $taskResults2 = $taskResults2->where('taskDate', $reminderDate2)->where('taskTime', $reminderTime2);

    $taskResults = $taskResults->union($taskResults2);

    if($currentTime == "18:00:00") {
      $fieldCycleTaskResults = $fieldCycleTaskResults->where('taskDate', $reminderDate1);
      $taskResults = $taskResults->union($fieldCycleTaskResults);
    }

    if($currentTime == "07:00:00") {
      $fieldCycleTaskResults = $fieldCycleTaskResults->where('taskDate', $reminderDate2);
      $taskResults = $taskResults->union($fieldCycleTaskResults);
    }

    $taskResults = $taskResults->get();

    foreach ($taskResults as $key => $value) {

      $fieldCycleID = $value->fieldCycleID;
      $taskDate = $value->taskDate;
      $taskTime = $value->taskTime;
      $taskTypeID = $value->taskTypeID;
      $taskName = $value->taskName;
      $product = $value->productID ? $value->product->product : '';
      $remark = $value->remark;
      $contactNo = $value->contact->contactNo;

      $title = $taskTypeID == 3 ? $product : $taskName;
      $message = $fieldCycleID ? $value->fieldCycle->fieldActivity->field->fieldName.' - '.$value->fieldCycle->fieldActivity->fieldActivityName : Carbon::parse($taskTime)->format('H:i a');

      $userResult = user::where('username', $contactNo)->where('status', 1)->first();

      if($userResult) {

        $data = [
          'notificationType' => 'toDoTask',
          'taskDate' => $taskDate
        ];

        self::sendNotification($userResult->id, $title, $message, $data);

        Log::channel('job')->info("API : [POST] sendTaskReminder, userID : ".$userResult->id);
        Log::channel('job')->info("Send Successfully.\n");

      }

    }

    return APIHelper::returnJSON(true, 200, 'Send Successfully');
  }

  public function sendNotification($userID, $title, $message, $data)
  {
    $fcmURL = "https://exp.host/--/api/v2/push/send";

    $pushTokenList = [];

    $deviceResults = sys_device::where('userID', $userID)->where('status', 1)->get();

    foreach ($deviceResults as $key => $value) {

      $deviceID = $value->id;
      $pushToken = $value->pushToken;
      $badgeCount = $value->badgeCount + 1;

      $insertData = [
        'deviceID' => $deviceID,
        'userID' => $userID,
        'title' => $title,
        'message' => $message,
        'payload' => $data,
        'status' => 1,
      ];
      sys_notification::create($insertData);

      sys_device::where('id', $deviceID)->update([ 'badgeCount' => $badgeCount ]);

      if($pushToken) array_push($pushTokenList, $pushToken);

    }

    if(count($pushTokenList)) {

      $pushTokenList = array_unique($pushTokenList);

      $param = [
        'to' => $pushTokenList,
        'title' => $title,
        'body' => $message,
        'data' => $data,
        "sound" => "default"
      ];

      $response = APIHelper::APIRequest('POST', $fcmURL, null, [], $param);
      if(!$response['success']) return APIHelper::returnJSON(false, 400, 'Failed to send notification.');

    }

    return true;
  }

}
