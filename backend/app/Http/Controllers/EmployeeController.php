<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Exception;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
    
    $query = Employee::query();

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
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:employees,email',
        'position' => 'required|string|max:255',
    ]);

    $employee = Employee::create($validatedData);
    
    return response()->json([
        'message' => 'Employee created successfully!',
        'employee' => $employee
    ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show ($id)
    {
    $employee = Employee::find($id);
    if (!$employee) {
        return response()->json(['message' => 'Employee not found'], 404);
        }
    return response()->json($employee, 200);
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        $employee = Employee::find($id);
        if (!$employee) {
        return response()->json(['message' => 'Employee not found'], 404);
        }
        return response()->json($employee, 200);
        }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $employee = Employee::find($id);
        if(!$employee){
            return response()->json(['message'=>'Employee not found'], 404);
        }
        $validateData= $request->validate([
            'name' =>'required|string|max:255',
            'email' =>'required|email|unique:employees,email,'.$employee->id,
            'position' =>'required|string|max:255',
        ]);
        $employee->update($request->all());
        return response()->json([
            'message'=>'Employee updated successfully',
            'employee'=>$employee
        ],200);             
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
    $employee = Employee::find($id);
    if (empty($employee)) {
        return response()->json(['message' => 'Employee not found'], 404);
    }
    return response()->json([
        'deleted' => $employee->delete(),
        'message' => 'Employee successfully deleted'
    ], 200);
    }
    
}


