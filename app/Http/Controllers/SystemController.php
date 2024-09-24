<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Image;
use Storage;

use App\Helpers\APIHelper;

use App\Models\sms_otp;

class SystemController extends Controller
{

  public function verifyEndpoint()
  {
    return APIHelper::returnJSON(true, 200, 'Verify Successfully');
  }

  public function forceUpdate()
  {
    $rules = [
      'buildNo' => 'required',
    ];
    $parameterError = APIHelper::checkParameter($rules);
    if($parameterError) return APIHelper::returnJSON(false, 400, $parameterError);

    $buildNo = request('buildNo');

    if((int)$buildNo < 20230911) return APIHelper::returnJSON(false, 400, 'Please update the newest version for a better experience with additional features.');

    return APIHelper::returnJSON(true, 200, 'Latest Version');
  }

  public function sendMessageOTP()
  {
    $rules = [
      'mobileNo' => 'required',
    ];
    $parameterError = APIHelper::checkParameter($rules);
    if($parameterError) return APIHelper::returnJSON(false, 400, $parameterError);

    $mobileNo = request('mobileNo');

    $now = Carbon::now();

    $otpResult = sms_otp::where('mobileNo', $mobileNo)->where('status', 1)->first();

    if($otpResult) {

      $otpExpired = Carbon::parse($otpResult->otpExpired);

      if($now->timestamp < $otpExpired->timestamp) {
        $resendDuration = $otpExpired->diffInSeconds($now);
        $data = [ 'resendDuration' => $resendDuration ];
        return APIHelper::returnJSON(true, 200, 'Send Successfully', $data);
      }

    }

    $updateData = [ 'status' => 3 ];
    sms_otp::where('mobileNo', $mobileNo)->update($updateData);

    $code = rand(100000, 999999);
    $resendDuration = 60;

    $otpExpired = Carbon::now()->addSeconds($resendDuration)->format('Y-m-d H:i:s');

    $smsURL = "https://sgateway.onewaysms.com/apis10.aspx";
    $smsUsername = "API14C8BES57P";
    $smsPassword = "API14C8BES57P14C8B";
    $smsSender = "GRG";
    $message = "HiTerra: Your Code is: ".$code." .";

    $param = "?apiusername=".$smsUsername."&apipassword=".$smsPassword;
    $param .= "&senderid=".$smsSender."&mobileno=".$mobileNo;
    $param .= "&message=".$message."&languagetype=1";
    $url = $smsURL.$param;

    $response = APIHelper::APIRequest('GET', $url);
    if(!$response['success']) return APIHelper::returnJSON(false, 400, 'Failed to send sms.');

    if($response['data'] < 0) {
      switch ($response['data']) {
        case -100: $message = "Invalid API username or password."; break;
        case -200: $message = "Invalid sender ID."; break;
        case -300: $message = "Invalid mobile no."; break;
        case -400: $message = "Invalid language type."; break;
        case -500: $message = "Invalid character type."; break;
        case -600: $message = "Insufficient credit balance."; break;
        default: $message = "-"; break;
      }
      return APIHelper::returnJSON(false, 400, $message);
    }

    $insertData = [
      'mobileNo' => $mobileNo,
      'otp' => $code,
      'otpExpired' => $otpExpired,
    ];
    sms_otp::insert($insertData);

    $data = [
      'resendDuration' => $resendDuration
    ];

    return APIHelper::returnJSON(true, 200, 'Send Successfully', $data);
  }

  public function sendWhatsappOTP()
  {
    $rules = [
      'mobileNo' => 'required',
    ];
    $parameterError = APIHelper::checkParameter($rules);
    if($parameterError) return APIHelper::returnJSON(false, 400, $parameterError);

    $mobileNo = request('mobileNo');

    $now = Carbon::now();

    $otpResult = sms_otp::where('mobileNo', $mobileNo)->where('status', 1)->first();

    if($otpResult) {

      $otpExpired = Carbon::parse($otpResult->otpExpired);

      if($now->timestamp < $otpExpired->timestamp) {
        $resendDuration = $otpExpired->diffInSeconds($now);
        $data = [ 'resendDuration' => $resendDuration ];
        return APIHelper::returnJSON(true, 200, 'Send Successfully', $data);
      }

    }

    $updateData = [ 'status' => 3 ];
    sms_otp::where('mobileNo', $mobileNo)->update($updateData);

    $code = rand(100000, 999999);
    $resendDuration = 60;

    $otpExpired = Carbon::now()->addSeconds($resendDuration)->format('Y-m-d H:i:s');

    $smsURL = "https://wba-api.onewaysms.com/api.aspx";
    $smsUsername = "APIMISLDJXO";
    $smsPassword = "APIMISLDJXOMISL";
    $message = "*T219|".$code;

    $param = "?apiusername=".$smsUsername."&apipassword=".$smsPassword;
    $param .= "&mobile=".$mobileNo."&message=".$message;
    $url = $smsURL.$param;

    $response = APIHelper::APIRequest('GET', $url);
    if(!$response['success']) return APIHelper::returnJSON(false, 400, 'Failed to send sms.');

    if($response['data'] < 0) {
      switch ($response['data']) {
        case -1: $message = "Invalid API username or password."; break;
        case -2: $message = "Mobile number is empty."; break;
        case -3: $message = "Message is empty."; break;
        case -4: $message = "Invalid flow."; break;
        case -5: $message = "Invalid template."; break;
        case -6: $message = "Template parameter does not match."; break;
        case -7: $message = "IP does not match."; break;
        default: $message = "-"; break;
      }
      return APIHelper::returnJSON(false, 400, $message);
    }

    $insertData = [
      'mobileNo' => $mobileNo,
      'otp' => $code,
      'otpExpired' => $otpExpired,
    ];
    sms_otp::insert($insertData);

    $data = [
      'resendDuration' => $resendDuration
    ];

    return APIHelper::returnJSON(true, 200, 'Send Successfully', $data);
  }

  public function otpVerify()
  {
    $rules = [
      'mobileNo' => 'required',
      'code' => 'required',
    ];
    $parameterError = APIHelper::checkParameter($rules);
    if($parameterError) return APIHelper::returnJSON(false, 400, $parameterError);

    $mobileNo = request('mobileNo');
    $code = request('code');

    $now = Carbon::now();

    $otpResult = sms_otp::where('mobileNo', $mobileNo)->where('otp', $code)->where('otpExpired', '>=', $now)->where('status', 1)->first();
    if(!$otpResult) return APIHelper::returnJSON(false, 400, 'Invalid Code');

    $updateData = [ 'status' => 3 ];
    sms_otp::where('mobileNo', $mobileNo)->update($updateData);

    return APIHelper::returnJSON(true, 200, 'Verify Successfully');
  }

  public function uploadFile()
  {
    $authError = APIHelper::checkAuth();
    if($authError) return APIHelper::returnJSON(false, 401, $authError);

    $headerError = APIHelper::checkHeader();
    if($headerError) return APIHelper::returnJSON(false, 401, $headerError);

    $rules = [
      'file' => 'required',
    ];
    $parameterError = APIHelper::checkParameter($rules);
    if($parameterError) return APIHelper::returnJSON(false, 400, $parameterError);

    $file = request()->file('file');
    $fileType = explode('.', $file->getClientOriginalName());
    $filename = Str::uuid().'.'.end($fileType);
    $extension = $file->extension();

    if($extension == 'jpeg' || $extension == 'jpg' || $extension == 'png') {
      $img = Image::make($file->path());
      if($img->width() > 1080) {
        $file = $img->orientate()->resize(1080, null, function ($constraint) {
          $constraint->aspectRatio();
        })->save(storage_path('app/temp/').$filename);
      }else{
        Storage::putFileAs('temp', $file, $filename);
      }
    }

    $data = [
      'filename' => $filename
    ];

    return APIHelper::returnJSON(true, 200, 'Upload Successfully', $data);
  }

}
