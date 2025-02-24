<?php

namespace App\Http\Controllers;

use App\Models\Student;
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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

    $student= Student::create($validatedData);
    
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
        if (!$student){
        return response()->json(['message' => 'Student  not found'], 404);
        }
            return response()->json($student, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Students $students)
    {
        $student= Student::find($id);
        if (!$student) {
        return response()->json(['message' => 'Student not found'], 404);
        }
        return response()->json($student, 200);
        }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $student = Student::find($id);
        if(!$student){
            return response()->json(['message'=>'Student not found'], 404);
        }
        $validateData= $request->validate([
            'name' =>'required|string|max:255',
            'age' =>'required|integer',
            'email' =>'required|email|unique:students,email,'.$student->id,
            'course' =>'required|string|max:255',   
        ]);
        $student->update($request->all());
        return response()->json([
            'message'=>'Student updated successfully',
            'student'=>$student
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $student = Student::find($id);
    if (empty($student)) {
        return response()->json(['message' => 'Student not found'], 404);
    }
    return response()->json([
        'deleted' => $student->delete(),
        'message' => 'Student successfully deleted'
    ], 200);
    }
    
}
