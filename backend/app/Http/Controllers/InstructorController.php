<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InstructorController extends Controller
{
    public function index()
    {
        // Return all instructors with full image URL
        return response()->json(
            Instructor::all()->map(function ($instructor) {
                $instructor->image_url = $instructor->image 
                    ? asset('storage/' . $instructor->image)
                    : null;
                return $instructor;
            })
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',              // Added email validation
            'age' => 'required|integer|min:18|max:100',
            'course' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'contact_number' => 'required|string|max:20',
        ]);

        if ($request->hasFile('image')) {
            // Store image in storage/app/public and only save filename
            $validated['image'] = $request->file('image')->store('storage', 'public');
        }

        $instructor = Instructor::create($validated);

        // Add image URL for frontend convenience
        $instructor->image_url = $instructor->image ? asset('storage/' . $instructor->image) : null;

        return response()->json($instructor, 201);
    }

    public function show(Instructor $instructor)
    {
        $instructor->image_url = $instructor->image ? asset('storage/' . $instructor->image) : null;
        return response()->json($instructor);
    }

    public function update(Request $request, $id)
{
    $instructor = Instructor::findOrFail($id); // ← Get the instructor by ID

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'age' => 'required|integer|min:18|max:100',
        'course' => 'required|string|max:255',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'contact_number' => 'required|string|max:20',
    ]);

    if ($request->hasFile('image')) {
        if ($instructor->image) {
            Storage::disk('public')->delete($instructor->image);
        }
        $validated['image'] = $request->file('image')->store('storage', 'public');
    }

    $instructor->update($validated);

    $instructor->image_url = $instructor->image ? asset('storage/' . $instructor->image) : null;

    return response()->json($instructor);
}


    public function destroy(Request $request, $id)
    {
        $instructor = Instructor::findOrFail($id);
        if (!$instructor){
            return response()->json(['message' => 'Instructor not found'],404);
        }
        if ($instructor->image) {
            Storage::disk('public')->delete($instructor->image);
        }

        $instructor->delete();

        return response()->json(['message' => 'Instructor deleted']);
    }
}
