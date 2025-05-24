<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index()
    {
        return response()->json(Grade::with(['subject', 'student'])->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'student_id' => 'required|exists:students,id',
            'midterm_grade' => 'nullable|numeric|min:0|max:100',
            'final_grade' => 'nullable|numeric|min:0|max:100',
        ]);

        $grade = Grade::create($validated);

        return response()->json($grade, 201);
    }

    public function show(Grade $grade)
    {
        return response()->json($grade->load(['subject', 'student']));
    }

    public function update(Request $request, $id)
    {
        $grade = Grade::findOrFail($id);
        $validated = $request->validate([
            'midterm_grade' => 'nullable|numeric|min:0|max:100',
            'final_grade' => 'nullable|numeric|min:0|max:100',
        ]);

        $grade->update($validated);

        return response()->json(['message' => 'Grade updated successfully.']);
    }

    public function destroy(Grade $grade)
    {
        $grade->delete();
        return response()->json(['message' => 'Grade deleted']);
    }

    public function getGradesByStudent($id)
{
    try {
        $grades = Grade::where('student_id', $id)->get();

        if ($grades->isEmpty()) {
            return response()->json(['message' => 'No grades found for the student'], 404);
        }

        return response()->json($grades);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


}
