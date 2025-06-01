<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GradeController extends Controller
{
    public function index()
    {
        try {
            $grades = Grade::with(['subject', 'student'])->get();
            return response()->json($grades);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'subject_id' => 'required|exists:subjects,id',
                'student_id' => 'required|exists:students,id',
                'midterm_grade' => 'nullable|numeric|min:0|max:100',
                'final_grade' => 'nullable|numeric|min:0|max:100',
            ]);

            $grade = Grade::create($validated);
            return response()->json($grade, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show(Grade $grade)
    {
        try {
            return response()->json($grade->load(['subject', 'student']));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $grade = Grade::findOrFail($id);

            $validated = $request->validate([
                'midterm_grade' => 'nullable|numeric|min:0|max:100',
                'final_grade' => 'nullable|numeric|min:0|max:100',
            ]);

            $grade->update($validated);

            return response()->json(['message' => 'Grade updated successfully.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Grade not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(Grade $grade)
    {
        try {
            $grade->delete();
            return response()->json(['message' => 'Grade deleted']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
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

    public function getGradesStudent($id)
{
    try {
        $grades = Grade::with(['subject.instructor', 'student'])
            ->where('student_id', $id)
            ->get();

        if ($grades->isEmpty()) {
            return response()->json(['message' => 'No grades found for the student'], 404);
        }

        $student = $grades->first()->student ?? \App\Models\Student::find($id);

        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        $subjects = $grades->pluck('subject')->filter()->unique('id')->values();
        $instructors = $subjects->pluck('instructor')->filter()->unique('id')->values();

        $qrData = [
            'student' => $student->only(['id', 'name']),
            'grades' => $grades->map(function ($grade) {
                return [
                    'subject' => optional($grade->subject)->name ?? 'N/A',
                    'midterm' => $grade->midterm_grade,
                    'final' => $grade->final_grade,
                ];
            }),
        ];

        $qrCode = base64_encode(
            QrCode::format('png')
                ->size(200)
                ->generate(json_encode($qrData))
        );
        

        return response()->json([
            'student' => $student,
            'grades' => $grades,
            'subjects' => $subjects,
            'instructors' => $instructors,
            'qr_code' => 'data:image/png;base64,' . $qrCode,
        ]);
    } catch (\Exception $e) {
        \Log::error('Error in getGradesStudent: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
}