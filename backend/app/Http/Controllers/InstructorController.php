<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InstructorController extends Controller
{
    public function index()
    {
        try {
            $instructors = Instructor::with('subjects')->get();

            foreach ($instructors as $instructor) {
                $instructor->image_url = $instructor->image ? asset('storage/' . $instructor->image) : null;
            }

            return response()->json($instructors);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch instructors', 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
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

        try {
            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('instructors', 'public');
            }
            $validated['password'] = bcrypt($validated['password']);
            $validated['subject_id'] = $request->input('subject_id');

            $instructor = Instructor::create($validated);

            // Reload instructor with subject relation
            $instructor = Instructor::with('subjects')->find($instructor->id);
            $instructor->image_url = $instructor->image ? asset('storage/' . $instructor->image) : null;

            return response()->json($instructor, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create instructor', 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $instructor = Instructor::with('subjects')->findOrFail($id);
            $instructor->image_url = $instructor->image ? asset('storage/' . $instructor->image) : null;

            return response()->json($instructor);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Instructor not found', 'message' => $e->getMessage()], 404);
        }
    }

    public function update(Request $request, $id)
{
    try {
        $instructor = Instructor::findOrFail($id);

        // Validation rules
        $validated = $request->validate([
            'name' => 'string|max:255',
            'email' => 'email|max:255|unique:instructors,email,' . $instructor->id,
            'password' => 'nullable|string|min:8|max:255',
            'age' => 'integer|min:18|max:100',
            'course' => 'string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'contact_number' => 'string|max:20',
            'subject_id' => 'exists:subjects,id|unique:instructors,subject_id,' . $instructor->id,
            'role' => 'string|max:255',  // Fixed the key from 'role ' to 'role'
        ]);

        // Handle image file upload if present
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($instructor->image) {
                Storage::disk('public')->delete($instructor->image);
            }
            $validated['image'] = $request->file('image')->store('instructors', 'public');
        }

        // Hash password only if provided
        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Update instructor with validated data
        $instructor->update($validated);

        // Reload instructor with subjects relation and add image URL
        $instructor = Instructor::with('subjects')->find($instructor->id);
        $instructor->image_url = $instructor->image ? asset('storage/' . $instructor->image) : null;

        return response()->json($instructor);
    } catch (\Exception $e) {
        \Log::error('Instructor update failed: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
        return response()->json([
            'error' => 'Failed to update instructor',
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
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

            return response()->json(['message' => 'Instructor deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete instructor', 'message' => $e->getMessage()], 500);
        }
    }
}
