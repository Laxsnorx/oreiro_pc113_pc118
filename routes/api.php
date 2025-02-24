<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\StudentsController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/employees', [EmployeeController::class,'index']);
Route::post('/employees', [EmployeeController::class,'store']);
Route::put('/employees/{id}', [EmployeeController::class, 'update']);
Route::get('/employees/{id}/', [EmployeeController::class,'show']);
Route::delete('/employees/{id}', [EmployeeController::class, 'destroy']);


//estudyanteeeee
Route::get('/students', [StudentsController::class,'index']);
Route::post('/students', [StudentsController::class,'store']);
Route::put('/students/{id}', [StudentsController::class, 'update']);
Route::get('/students/{id}/', [StudentsController::class,'show']);
Route::delete('/students/{id}', [StudentsController::class, 'destroy']);
