<?php

use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\UserController; //!new
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json([
        'message' => 'Authenticated User',
        'user' => $request->user()
    ]);
});


Route::post('/login', [UserController::class, 'login']); //!new


// Employees
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/employees', [EmployeeController::class,'index']);
    Route::post('/employees', [EmployeeController::class,'store']);
    Route::put('/employees/{id}', [EmployeeController::class, 'update']);
    Route::get('/employees/{id}', [EmployeeController::class,'show']);
    Route::delete('/employees/{id}', [EmployeeController::class, 'destroy']);

    //estudyanteee
    Route::get('/students', [StudentsController::class,'index']);
    Route::post('/students', [StudentsController::class,'store']);
    Route::put('/students/{id}', [StudentsController::class, 'update']);
    Route::get('/students/{id}', [StudentsController::class,'show']);
    Route::delete('/students/{id}', [StudentsController::class, 'destroy']);
});

//!Admin Routes
Route::middleware(['auth:sanctum','role:admin'])->group(function(){
    Route::get('/user', [UserController::class, 'index']);
});

//!User Routes
Route::middleware(['auth:sanctum','role:user'])->group(function(){
    Route::get('/userdashboard', [UserDashboardController::class, 'index']);
});

//! Consuming API
Route::middleware('auth:sanctum')->post('/logout', function (Request $request) {
    $request->user()->tokens()->delete(); // Revoke all tokens
    return response()->json(['message' => 'Logged out successfully']);
});
