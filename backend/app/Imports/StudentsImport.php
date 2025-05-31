<?php

namespace App\Imports;

use Illuminate\Support\Facades\Hash;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Check if the student already exists based on email
        $existingStudent = Student::where('email', $row['email'])->first();

        if ($existingStudent) {
            return null; // Skip if student already exists
        }

        return new Student([
            'name' => $row['name'],
            'email' => $row['email'],
            'course' => $row['course'],
            'age' => 18, // Default value
            'password' => Hash::make('password'),
        ]);
        
        
    }
}
