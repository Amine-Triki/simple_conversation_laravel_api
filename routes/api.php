<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;

use App\Http\Controllers\MessageController;


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
// seen message
Route::middleware('auth:sanctum')->post('/seenMessage/{id}', [MessageController::class, "seenMessage"]);

//get all messages
Route::get("/getallmessages/{id}", [MessageController::class, "getallmessages"])->middleware('auth:sanctum');

