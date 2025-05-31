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
            'email' => 'required|email|unique:students,email',
            'password' => 'required|string|min:6',
            'age' => 'required|integer|min:1',
            'course' => 'required|string|max:255',
            'role' => 'required|string|in:student',
        ]);

        // Hash the password before saving
        $validatedData['password'] = bcrypt($validatedData['password']);

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
            'email' => 'required|email|unique:students,email,' . $student->id,
            'role' => 'required|in:student',
            'age' => 'required|integer',
            'course' => 'required|string|max:255',
            'password' => 'nullable|string|min:6', // password optional on update
        ]);

        if (!empty($validatedData['password'])) {
            // Hash the password if provided
            $validatedData['password'] = bcrypt($validatedData['password']);
        } else {
            // Remove password if not provided to avoid overwriting with null
            unset($validatedData['password']);
        }

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
        $grades = Grade::where('student_id', $id)
                       ->with('subject.instructor') // ✅ include instructor inside subject
                        ->get();
    
        return response()->json($grades);
    }
    // Show grades for a student
public function showByStudent($id)
{
    $grades = Grade::where('student_id', $id)->get();
    if ($grades->isEmpty()) {
        return response()->json(['message' => 'Grades not found'], 404);
    }
    return response()->json($grades);
}

// Store or update grades for a student
public function storeForStudent(Request $request, $id)
{
    $validated = $request->validate([
        'midterm_grade' => 'required|numeric|min:0|max:100',
        'final_grade' => 'required|numeric|min:0|max:100',
    ]);

    $grade = Grade::updateOrCreate(
        ['student_id' => $id],
        [
            'midterm_grade' => $validated['midterm_grade'],
            'final_grade' => $validated['final_grade'],
        ]
    );

    return response()->json($grade, 201);
}

}
