<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;

use App\Http\Controllers\MessageController;

use App\Http\Controllers\FileUploadController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


//login user
Route::post("/loginUser", [UserController::class, "loginUser"]);
//logout user
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth:sanctum');

//CRUD
Route::post("/createUser", [UserController::class, "createUser"]);
Route::get("/GetUsers", [UserController::class, "GetUsers"])->middleware('auth:sanctum');
Route::get("/getUser/{id}", [UserController::class, "GetUser"])->middleware('auth:sanctum');
Route::put("/updateUser/{id}", [UserController::class, "updateUser"])->middleware('auth:sanctum');
Route::delete("/deleteUser/{id}", [UserController::class, "DeleteUser"])->middleware('auth:sanctum');


//send message
Route::middleware('auth:sanctum')->post('/sendMessage/{id}', [MessageController::class, "sendMessage"]);
// seen all message
Route::middleware('auth:sanctum')->post('/seenMessage/{id}', [MessageController::class, "seenMessage"]);
// seen  one message
Route::post('/seenOneMessage/{id}/{N}', [MessageController::class, "seenOneMessage"])->middleware('auth:sanctum');

//get all messages
Route::get("/getallmessages/{id}", [MessageController::class, "getallmessages"])->middleware('auth:sanctum');
//get all messages reads
Route::get("/getallReads/{id}", [MessageController::class, "getallReads"])->middleware('auth:sanctum');
//get all messages not reads
Route::get("/getallNotReads/{id}", [MessageController::class, "getallNotReads"])->middleware('auth:sanctum');


//upload files
Route::post('/upload/image', [FileUploadController::class, 'uploadImage']);
Route::post('/upload/pdf', [FileUploadController::class, 'uploadPDF']);
Route::post('/upload/any', [FileUploadController::class, 'uploadAny']);

//delete files uploaded
Route::delete('/delete/file/{id}', [FileUploadController::class, 'deleteFile']);
