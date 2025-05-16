<?php
//////////////////////////////////////////////////////! PC117 - Application Development & Emerging Technologies Routes
use App\Http\Controllers\Auth\LauthController; 
use App\Http\Controllers\GradeController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\InstructorController;


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard/admin', fn() => response()->json(['message' => 'Admin Dashboard']))->middleware('role:admin');
    Route::get('/dashboard/teacher', fn() => response()->json(['message' => 'Teacher Dashboard']))->middleware('role:teacher');
    Route::get('/dashboard/student', fn() => response()->json(['message' => 'Student Dashboard']))->middleware('role:student');
});

//* Grades
Route::get('/grades', [GradeController::class, 'index']);
Route::post('/grades', [GradeController::class, 'store']);
Route::get('/grades/{id}', [GradeController::class, 'show']);
Route::put('/grades/{id}', [GradeController::class, 'update']);
Route::delete('/grades/{id}', [GradeController::class, 'destroy']);

//* Subjects
Route::get('/subjects', [SubjectController::class, 'index']);
Route::post('/subjects', [SubjectController::class, 'store']);
Route::get('/subjects/{id}', [SubjectController::class, 'show']);
Route::put('/subjects/{id}', [SubjectController::class, 'update']);
Route::delete('/subjects/{id}', [SubjectController::class, 'destroy']);

//* Instructors
Route::get('/instructors', [InstructorController::class, 'index']);
Route::post('/instructors', [InstructorController::class, 'store']);
Route::get('/instructors/{id}', [InstructorController::class, 'show']);
Route::post('/instructors/{id}', [InstructorController::class, 'update']);
Route::delete('/instructors/{id}', [InstructorController::class, 'destroy']);


//////////////////////////////////////////////////////!
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



});

//!Admin Routes
Route::middleware(['auth:sanctum','role:admin'])->group(function(){

});
    Route::get('/user', [UserController::class, 'index']);
    Route::put('/user/{id}', [UserController::class, 'update']); 
    Route::post('/user', [UserController::class, 'store']); 
    Route::get('/user/{id}', [UserController::class, 'show']); 
    Route::delete('/user/{id}', [UserController::class, 'destroy']); 

//!User Routes
Route::middleware(['auth:sanctum','role:user'])->group(function(){
    Route::get('/userdashboard', [UserDashboardController::class, 'index']);
});
Route::middleware('auth:sanctum')->put('/profile/update', [UserController::class, 'updateProfile']);

Route::middleware('auth:sanctum')->get('/profile', function (Request $request) {
    return response()->json($request->user());
});


//! Consuming API
Route::middleware('auth:sanctum')->post('/logout', function (Request $request) {
    $request->user()->tokens()->delete(); 
    return response()->json(['message' => 'Logged out successfully']);
});
        
        Route::get('/students', [StudentsController::class,'index']);
        Route::post('/students', [StudentsController::class,'store']);
        Route::put('/students/{id}', [StudentsController::class, 'update']);
        Route::get('/students/{id}', [StudentsController::class,'show']);
        Route::delete('/students/{id}', [StudentsController::class, 'destroy']);
        Route::get('/students/{id}/grades', [StudentsController::class, 'getGrades']);


        Route::post('/employees', [EmployeeController::class,'store']);
        Route::put('/employees/{id}', [EmployeeController::class, 'update']);
        Route::get('/employees/{id}', [EmployeeController::class,'show']);
        Route::delete('/employees/{id}', [EmployeeController::class, 'destroy']);