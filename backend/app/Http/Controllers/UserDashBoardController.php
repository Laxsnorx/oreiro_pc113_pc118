<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


//! new
class UserDashBoardController extends Controller
{
    public function index(){
        return response()->json([
            'message' => 'This is user dashboard',
            'user' => Auth::user(),
        ]); 
    }
}


