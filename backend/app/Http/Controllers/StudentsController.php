<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Grade;
use Illuminate\Http\Request;

class StudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Student::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%")
                ->orWhere('email', 'LIKE', "%$search%");
            });
        }

        return response()->json($query->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer',
            'email' => 'required|email|unique:students,email',
            'course' => 'required|string|max:255',
        ]);

        $student = Student::create($validatedData);

        return response()->json([
            'message' => 'Student created successfully!',
            'student' => $student
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $student = Student::find($id);
        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        // Optionally include grades with the student
        $student->load('grades'); 

        return response()->json($student, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $student = Student::find($id);
        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'course' => 'required|string|max:255',
        ]);

        $student->update($validatedData);

        return response()->json([
            'message' => 'Student updated successfully',
            'student' => $student
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $student = Student::find($id);
        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        $student->delete();

        return response()->json([
            'message' => 'Student successfully deleted'
        ], 200);
    }

    public function getGrades($id)
{
    // Assuming you have a Grade model with a student_id field
    $grades = Grade::where('student_id', $id)
                    ->with('subject') // Assuming 'subject' is a relation on the Grade model
                    ->get();

    // Return grades as JSON
    return response()->json($grades);
}
}
