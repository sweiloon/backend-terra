<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Auth;
use Log;

use Illuminate\Support\Facades\Validator;

use App\Models\user;

class APIHelper
{

  public static function checkAuth()
  {
    try {
      Auth::userOrFail();
    } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
      return 'Authentication Required';
    }
  }

  public static function checkHeader()
  {
    $userID = request()->header('userID');

    $headers = [ "userID" => $userID ];
    foreach ($headers as $key => $value) {
      if($value == null) return 'Authentication Required';
    }

    $userResult = user::where('id', $userID)->where('status', 1)->first();
    if(!$userResult) return 'Authentication Required';
  }

  public static function checkParameter($rules)
  {
    $validator = Validator::make(request()->all(), $rules);

    if($validator->fails()){
      $errorMsg = $validator->errors()->first();
      return $errorMsg;
    }
  }

  public static function returnJSON($success, $status, $message, $data = null)
  {
    $result['success'] = $success;
    $result['message'] = $message;
    $result['data'] = $data == null ? array() : $data;

    $path = request()->path();
    $method = request()->method();

    $companyID = request()->header('companyID');
    $userCategoryID = request()->header('userCategoryID');
    $userTypeID = request()->header('userTypeID');
    $userID = request()->header('userID');

    $headers = [ "userID" => $userID ];
    $requests = request()->all();

    $response = $result;
    if($method == "GET" && !empty($result['success'])) $response['data'] = '...';

    $exclude = [];
    $apiName = explode('/', $path);
    $apiName = end($apiName);

    if(!($method == "GET" && in_array($apiName, $exclude))) {
      Log::channel('api')->info("API : [".$method."] ".$path);
      Log::channel('api')->info("Header : ".json_encode($headers));
      Log::channel('api')->info("Request : ".json_encode($requests));
      Log::channel('api')->info("Response : ".json_encode($response)."\n");
    }

    return response()->json($result, $status);
  }

  public static function APIRequest($method, $api, $authToken=null, $header=null, $body=null)
  {
    $client = new Client();

    $headerForm = $header;
    $headerForm['Accept'] = 'application/json';
    if($authToken) $headerForm['Authorization'] = 'Bearer '.$authToken;

    $bodyForm['headers'] = $headerForm;
    strtoupper($method) == "GET" ? $bodyForm['query'] = $body : $bodyForm['form_params'] = $body;

    try {

      $response = $client->request($method, $api, $bodyForm);
      $response = preg_replace('/[[:cntrl:]]/', '', $response->getBody()->getContents());
      $response = json_decode($response, true);

      $result['success'] = true;
      $result['status'] = 200;
      $result['message'] = "Retrieve Successfully";
      $result['data'] = $response;

    } catch (RequestException $e) {

      $response = $e->getResponse();
      $response = preg_replace('/[[:cntrl:]]/', '', $response->getBody()->getContents());

      $result['success'] = false;
      $result['status'] = 500;
      $result['message'] = "Something went wrong";
      $result['data'] = json_decode($response, true) ? json_decode($response, true) : $response;

    }

    Log::channel('middleware')->info("Middleware : [".$method."] ".$api);
    Log::channel('middleware')->info("Header : ".json_encode($header));
    Log::channel('middleware')->info("Request : ".json_encode($body));
    Log::channel('middleware')->info("Response : ".json_encode($result)."\n");

    return $result;
  }

}
