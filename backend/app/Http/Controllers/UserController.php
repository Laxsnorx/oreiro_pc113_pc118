<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Exception;


//! new
class UserController extends Controller
{
    public function index(Request $request)
    {
        try {
            return response()->json(User::all(), 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error fetching users',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json($user, 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'User not found',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    public function store(Request $request)
{
    $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|string',
            'phone' => 'required|string',
            'password' => 'required|string|min:8',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'file' => 'nullable|mimes:pdf,doc,docx|max:10240',
        ]);

        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->role = $request->role;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('images', 'public');
                $user->image_url = $imagePath;
            }

            if ($request->hasFile('file')) {
                $filePath = $request->file('file')->store('files', 'public');
                $user->file_url = $filePath;
            }

            $user->save();

            return response()->json(['data' => $user], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    

    public function updateProfile(Request $request, $id)
    {
        $user = $request->user();

    $request->validate([
        'name' => 'sometimes|string|max:255',
        'email' => 'sometimes|email|max:255',
        'phone' => 'sometimes|string|max:20',
        'file' => 'nullable|file|max:10240',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
    ]);

    $filePath = null;
    $imagePath = null;
    
    if ($request->hasFile('uploaded_file')) {
        if ($user->uploaded_file && Storage::disk('public')->exists($user->uploaded_file)) {
            Storage::disk('public')->delete($user->uploaded_file);
        }

        $filePath = $request->file('uploaded_file')->store('uploaded_files', 'public');
        $user->uploaded_file = $filePath;
    }
    if ($request->hasFile('uploaded_image')) {
        if ($user->uploaded_image && Storage::disk('public')->exists($user->uploaded_image)) {
            Storage::disk('public')->delete($user->uploaded_image);
        }

        $imagePath = $request->file('uploaded_image')->store('uploaded_images', 'public');
        $user->uploaded_image = $imagePath;
    }

    $user->name = $request->input('name', $user->name);
    $user->email = $request->input('email', $user->email);
    $user->phone = $request->input('phone', $user->phone);


    $user->save();

    return response()->json([
        'message' => 'Profile updated successfully',
        'user' => $user,
        'file_url' => $filePath ? asset('storage/' . $filePath) : null,
        'image_url' => $imagePath ? asset('storage/' . $imagePath) : null,
    ]);
    }
    
    public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email,' . $id,
        'role' => 'required|string',
        'phone' => 'nullable|string|max:20',
    ]);

    try {
        $user = User::findOrFail($id);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->phone = $request->phone;

        $user->save();

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user,
        ], 200);
    } catch (Exception $e) {
        return response()->json([
            'message' => 'Error updating user',
            'error' => $e->getMessage(),
        ], 500);
    }
}

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json([
                'message' => 'User deleted successfully',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error deleting user',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            if (Auth::attempt([
                'email' => $request->email,
                'password' => $request->password,
            ])) {
                $user = Auth::user();
                $token = $user->createToken('myToken')->plainTextToken;
                return response()->json([
                    'user' => $user,
                    'token' => $token,
                ], 200);
            }
            return response()->json([
                'message' => 'Invalid Credentials',
            ], 401);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred during login',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
