<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        return response()->json(Subject::with('instructor')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10',
            'name' => 'required|string|max:255',
            'instructor_id' => 'required|exists:instructors,id',
            'year' => 'required|string|max:4',
            'units' => 'required|integer|min:1',  // added units validation
        ]);

        $subject = Subject::create($validated);

        return response()->json($subject, 201);
    }

    public function show(Subject $subject)
    {
        return response()->json($subject->load('instructor'));
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10',
            'name' => 'required|string|max:255',
            'instructor_id' => 'required|exists:instructors,id',
            'year' => 'required|string|max:4',
            'units' => 'required|integer|min:1',  // added units validation
        ]);

        $subject->update($validated);

        return response()->json($subject, 201);
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return response()->json(['message' => 'Subject deleted']);
    }
}
