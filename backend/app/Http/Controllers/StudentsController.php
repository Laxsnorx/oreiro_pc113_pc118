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
        try {
            $query = Student::query();

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%$search%")
                        ->orWhere('email', 'LIKE', "%$search%");
                });
            }

            return response()->json($query->get(), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch students: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:students,email',
                'password' => 'required|string|min:6',
                'age' => 'required|integer|min:1',
                'course' => 'required|string|max:255',
                'role' => 'required|string|in:student',
            ]);

            $validatedData['password'] = bcrypt($validatedData['password']);

            $student = Student::create($validatedData);

            return response()->json([
                'message' => 'Student created successfully!',
                'student' => $student
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create student: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $student = Student::find($id);
            if (!$student) {
                return response()->json(['message' => 'Student not found'], 404);
            }

            $student->load('grades');

            return response()->json($student, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch student: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
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
                'password' => 'nullable|string|min:6',
            ]);

            if (!empty($validatedData['password'])) {
                $validatedData['password'] = bcrypt($validatedData['password']);
            } else {
                unset($validatedData['password']);
            }

            $student->update($validatedData);

            return response()->json([
                'message' => 'Student updated successfully',
                'student' => $student
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update student: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $student = Student::find($id);
            if (!$student) {
                return response()->json(['message' => 'Student not found'], 404);
            }

            $student->delete();

            return response()->json(['message' => 'Student successfully deleted'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete student: ' . $e->getMessage()], 500);
        }
    }

    public function getGrades($id)
    {
        try {
            $grades = Grade::where('student_id', $id)
                ->with('subject.instructor')
                ->get();

            return response()->json($grades);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch grades: ' . $e->getMessage()], 500);
        }
    }

    public function showByStudent($id)
    {
        try {
            $grades = Grade::where('student_id', $id)->get();

            if ($grades->isEmpty()) {
                return response()->json(['message' => 'Grades not found'], 404);
            }

            return response()->json($grades);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch grades: ' . $e->getMessage()], 500);
        }
    }

    public function storeForStudent(Request $request, $id)
    {
        try {
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
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to save grade: ' . $e->getMessage()], 500);
        }
    }
}