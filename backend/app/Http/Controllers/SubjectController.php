<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{

    public function index()
    {
        try {
            $subjects = Subject::with('instructor')->get();
            return response()->json($subjects);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch subjects: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'code' => 'required|string|max:10',
                'description' => 'required|string|max:255',
                'instructor_id' => 'required|exists:instructors,id',
                'year' => 'required|string|max:4',
                'units' => 'required|integer|min:1',
            ]);

            $subject = Subject::create($validated);

            return response()->json($subject, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create subject: ' . $e->getMessage()], 500);
        }
    }

    public function show(Subject $subject)
    {
        try {
            return response()->json($subject->load('instructor'));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch subject: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, Subject $subject)
    {
        try {
            $validated = $request->validate([
                'code' => 'required|string|max:10',
                'description' => 'required|string|max:255',
                'instructor_id' => 'required|exists:instructors,id',
                'year' => 'required|string|max:4',
                'units' => 'required|integer|min:1',
            ]);

            $subject->update($validated);

            return response()->json($subject, 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update subject: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Subject $subject)
    {
        try {
            $subject->delete();
            return response()->json(['message' => 'Subject deleted']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete subject: ' . $e->getMessage()], 500);
        }
    }
}
