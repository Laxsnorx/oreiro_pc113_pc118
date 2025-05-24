<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\GradingUser;
use App\Models\User;
use App\Models\Instructor;
use App\Models\Student;




class LauthController extends Controller
{
    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();
    $role = 'admin';

    if (!$user) {
        $user = Instructor::where('email', $request->email)->first();
        $role = 'instructor';
    }

    if (!$user) {
        $user = Student::where('email', $request->email)->first();
        $role = 'student';
    }

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    // Make sure your User, Instructor, and Student models all use Laravel Sanctum's HasApiTokens trait
    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'access_token' => $token,
        'token_type' => 'Bearer',
        'user' => $user,
        'role' => $role,
    ]);
}
}
