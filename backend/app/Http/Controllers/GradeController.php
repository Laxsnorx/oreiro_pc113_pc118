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
            'grade' => 'required|string|max:5',
        ]);

        $grade = Grade::create($validated);

        return response()->json($grade, 201);
    }

    public function show(Grade $grade)
    {
        return response()->json($grade->load(['subject', 'student']));
    }

    public function update(Request $request, Grade $grade)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'student_id' => 'required|exists:students,id',
            'grade' => 'required|string|max:5',
        ]);

        $grade->update($validated);

        return response()->json($grade);
    }

    public function destroy(Grade $grade)
    {
        $grade->delete();
        return response()->json(['message' => 'Grade deleted']);
    }
}
