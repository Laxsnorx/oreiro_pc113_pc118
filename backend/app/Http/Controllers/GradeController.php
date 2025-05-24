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
}
