<?php
use App\Http\Controllers\HelloController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\UserController; //!new
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Route;


Route::get('/hello', [HelloController::class, 'index']); //!new
Route::post('/hello', [HelloController::class, 'store']); //!new



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
Route::get('/students', [StudentsController::class,'index']);
Route::get('/employees', [EmployeeController::class,'index']);
// Employees
Route::middleware('auth:sanctum')->group(function () {


    //estudyanteee
});

//!Admin Routes
Route::middleware(['auth:sanctum','role:admin'])->group(function(){
    Route::get('/user', [UserController::class, 'index']);
});

//!User Routes
Route::middleware(['auth:sanctum','role:user'])->group(function(){
    Route::get('/userdashboard', [UserDashboardController::class, 'index']);
});
Route::middleware('auth:sanctum')->put('/profile/update', [UserController::class, 'updateProfile']);
// ✅ Add this in routes/api.php
Route::middleware('auth:sanctum')->get('/profile', function (Request $request) {
    return response()->json($request->user());
});

//! Consuming API
Route::middleware('auth:sanctum')->post('/logout', function (Request $request) {
    $request->user()->tokens()->delete(); 
    return response()->json(['message' => 'Logged out successfully']);
});

        Route::post('/students', [StudentsController::class,'store']);
        Route::put('/students/{id}', [StudentsController::class, 'update']);
        Route::get('/students/{id}', [StudentsController::class,'show']);
        Route::delete('/students/{id}', [StudentsController::class, 'destroy']);


        Route::post('/employees', [EmployeeController::class,'store']);
        Route::put('/employees/{id}', [EmployeeController::class, 'update']);
        Route::get('/employees/{id}', [EmployeeController::class,'show']);
        Route::delete('/employees/{id}', [EmployeeController::class, 'destroy']);