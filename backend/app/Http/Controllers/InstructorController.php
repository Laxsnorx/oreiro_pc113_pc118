<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\CredentialMail;
use Illuminate\Support\Facades\Hash;
use Exception;

class InstructorController extends Controller
{
    public function index()
    {
        try {
            $instructors = Instructor::with('subjects')->get();

            foreach ($instructors as $instructor) {
                $instructor->image_url = $instructor->image ? asset('storage/' . $instructor->image) : null;
            }

            return response()->json($instructors, 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error fetching instructors',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:instructors,email',
                'password' => 'required|string|min:8|max:255',
                'age' => 'required|integer|min:18|max:100',
                'course' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'contact_number' => 'required|string|max:20',
                'subject_id' => 'required|exists:subjects,id|unique:instructors,subject_id',
            ]);

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('instructors', 'public');
                $validated['image'] = $path;
            }

            $validated['password'] = Hash::make($validated['password']);

            $instructor = Instructor::create($validated);
            $instructor = Instructor::with('subjects')->find($instructor->id);
            $instructor->image_url = $instructor->image ? asset('storage/' . $instructor->image) : null;

            $subjects = $instructor->subjects;
$deadline = '2025-06-30';

Mail::to($instructor->email)->send(new InstructorMail([
    'full_name' => $instructor->name,
    'subjects' => $subjects,
    'deadline' => $deadline,
    'instructor_id' => $instructor->id,
]));

            

            return response()->json([
                'message' => 'Instructor created successfully',
                'instructor' => $instructor
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error creating instructor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $instructor = Instructor::with('subjects')->findOrFail($id);
            $instructor->image_url = $instructor->image ? asset('storage/' . $instructor->image) : null;

            return response()->json($instructor, 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Instructor not found',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $instructor = Instructor::findOrFail($id);

            $validated = $request->validate([
                'name' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:255|unique:instructors,email,' . $instructor->id,
                'password' => 'nullable|string|min:8|max:255',
                'age' => 'nullable|integer|min:18|max:100',
                'course' => 'nullable|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'contact_number' => 'nullable|string|max:20',
                'subject_id' => 'nullable|exists:subjects,id|unique:instructors,subject_id,' . $instructor->id,
            ]);

            if ($request->hasFile('image')) {
                if ($instructor->image) {
                    Storage::disk('public')->delete($instructor->image);
                }
                $validated['image'] = $request->file('image')->store('instructors', 'public');
            }

            if (!empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            $instructor->update($validated);
            $instructor = Instructor::with('subjects')->find($instructor->id);
            $instructor->image_url = $instructor->image ? asset('storage/' . $instructor->image) : null;

            return response()->json([
                'message' => 'Instructor updated successfully',
                'instructor' => $instructor
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error updating instructor',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $instructor = Instructor::findOrFail($id);

            if ($instructor->image) {
                Storage::disk('public')->delete($instructor->image);
            }

            $instructor->delete();

            return response()->json([
                'message' => 'Instructor deleted successfully',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error deleting instructor',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
