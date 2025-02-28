<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\AuthController;
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

Route::post('/login', [AuthController::class, 'login']); 


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
