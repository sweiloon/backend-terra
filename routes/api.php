<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SystemController;
use App\Http\Controllers\PatchController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\FieldActivityController;
use App\Http\Controllers\FieldCycleController;
use App\Http\Controllers\FieldCycleTaskController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/verifyEndpoint', [ SystemController::class, 'verifyEndpoint' ]);
Route::post('/forceUpdate', [ SystemController::class, 'forceUpdate' ]);
Route::post('/sendMessageOTP', [ SystemController::class, 'sendMessageOTP' ]);
Route::post('/sendWhatsappOTP', [ SystemController::class, 'sendWhatsappOTP' ]);
Route::post('/otpVerify', [ SystemController::class, 'otpVerify' ]);
Route::post('/uploadFile', [ SystemController::class, 'uploadFile' ]);


Route::post('/dataPatch', [ PatchController::class, 'dataPatch' ]);


Route::post('/sendTaskReminder', [ JobController::class, 'sendTaskReminder' ]);


Route::get('/notification', [ NotificationController::class, 'getNotification' ]);


Route::post('/device', [ DeviceController::class, 'addDevice' ]);
Route::put('/updatePushToken', [ DeviceController::class, 'updatePushToken' ]);


Route::post('/checkAccountExist', [ AuthController::class, 'checkAccountExist' ]);
Route::post('/register', [ AuthController::class, 'register' ]);
Route::post('/login', [ AuthController::class, 'login' ]);
Route::post('/logout', [ AuthController::class, 'logout' ]);
Route::post('/resetPassword', [ AuthController::class, 'resetPassword' ]);

Route::get('/country', [ AuthController::class, 'getCountry' ]);
Route::get('/country/{countryID}', [ AuthController::class, 'getCountryDetail' ]);


Route::put('/editProfile', [ SettingsController::class, 'editProfile' ]);
Route::put('/changePassword', [ SettingsController::class, 'changePassword' ]);


Route::get('/field', [ FieldController::class, 'getField' ]);
Route::get('/field/{fieldID}', [ FieldController::class, 'getFieldDetail' ]);
Route::post('/field', [ FieldController::class, 'addField' ]);
Route::put('/field/{fieldID}', [ FieldController::class, 'editField' ]);
Route::delete('/field/{fieldID}', [ FieldController::class, 'deleteField' ]);

Route::post('/fieldImage/{fieldID}', [ FieldController::class, 'addFieldImage' ]);
Route::delete('/fieldImage/{fieldID}/{fieldImageID}', [ FieldController::class, 'deleteFieldImage' ]);

Route::get('/unitType', [ FieldController::class, 'getUnitType' ]);
Route::get('/habitatType', [ FieldController::class, 'getHabitatType' ]);


Route::get('/fieldActivity', [ FieldActivityController::class, 'getFieldActivity' ]);
Route::get('/fieldActivity/{fieldActivityID}', [ FieldActivityController::class, 'getFieldActivityDetail' ]);
Route::post('/fieldActivity', [ FieldActivityController::class, 'addFieldActivity' ]);
Route::put('/fieldActivity/{fieldActivityID}', [ FieldActivityController::class, 'editFieldActivity' ]);
Route::delete('/fieldActivity/{fieldActivityID}', [ FieldActivityController::class, 'deleteFieldActivity' ]);


Route::get('/fieldCycle', [ FieldCycleController::class, 'getFieldCycle' ]);
Route::get('/fieldCycle/{fieldCycleID}', [ FieldCycleController::class, 'getFieldCycleDetail' ]);
Route::post('/fieldCycle', [ FieldCycleController::class, 'addFieldCycle' ]);
Route::delete('/fieldCycle/{fieldCycleID}', [ FieldCycleController::class, 'deleteFieldCycle' ]);

Route::get('/cropType', [ FieldCycleController::class, 'getCropType' ]);
Route::get('/cropVariety', [ FieldCycleController::class, 'getCropVariety' ]);


Route::get('/fieldCycleTask', [ FieldCycleTaskController::class, 'getFieldCycleTask' ]);
Route::get('/fieldCycleTask/{fieldCycleTaskID}', [ FieldCycleTaskController::class, 'getFieldCycleTaskDetail' ]);
Route::post('/fieldCycleTask', [ FieldCycleTaskController::class, 'addFieldCycleTask' ]);
Route::put('/fieldCycleTask/{fieldCycleTaskID}', [ FieldCycleTaskController::class, 'editFieldCycleTask' ]);
Route::delete('/fieldCycleTask/{fieldCycleTaskID}', [ FieldCycleTaskController::class, 'deleteFieldCycleTask' ]);

Route::get('/cropProduct', [ FieldCycleTaskController::class, 'getCropProduct' ]);
Route::get('/taskType', [ FieldCycleTaskController::class, 'getTaskType' ]);


Route::get('/task', [ TaskController::class, 'getTask' ]);
Route::get('/task/{taskID}', [ TaskController::class, 'getTaskDetail' ]);
Route::post('/task', [ TaskController::class, 'addTask' ]);
Route::put('/task/{taskID}', [ TaskController::class, 'editTask' ]);
Route::delete('/task/{taskID}', [ TaskController::class, 'deleteTask' ]);


Route::get('/contact', [ ContactController::class, 'getContact' ]);
Route::get('/contact/{contactID}', [ ContactController::class, 'getContactDetail' ]);
Route::post('/contact', [ ContactController::class, 'addContact' ]);
Route::put('/contact/{contactID}', [ ContactController::class, 'editContact' ]);
Route::delete('/contact/{contactID}', [ ContactController::class, 'deleteContact' ]);


Route::get('/user', [ UserController::class, 'getUser' ]);
Route::get('/user/{userID}', [ UserController::class, 'getUserDetail' ]);
Route::put('/user/{userID}', [ UserController::class, 'editUser' ]);
