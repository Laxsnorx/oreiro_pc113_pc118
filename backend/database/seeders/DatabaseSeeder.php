<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Log;
use App\Models\Instructor;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Grade;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
{
    User::factory(10)->create(); // Optional: Seeding 10 users

    // Create 10 Instructors
    echo "Seeding Instructors...\n";
    $instructors = Instructor::factory()->count(10)->create();

    // Each Instructor has 1 Subject only
    echo "Seeding Subjects...\n";
    $instructors->each(function ($instructor) {
        Subject::factory()->create([
            'instructor_id' => $instructor->id,
            'schedule' => 'MWF 9:00 AM - 11:00 AM', // Example schedule
            'units' => 3, // Ensure 3 units per subject
        ]);
    });

    // Create Grades for each Subject and Student
    echo "Seeding Grades...\n";
    $students = Student::all();
    Subject::all()->each(function ($subject) use ($students) {
        $students->each(function ($student) use ($subject) {
            Grade::create([
                'subject_id' => $subject->id,
                'student_id' => $student->id,
                'grade' => $this->generateGrade(),
            ]);
        });
    });

    echo "Sample data generated successfully!\n";
    }
    private function generateGrade()
    {
    return collect([1.0, 1.25, 1.5, 1.75, 2.0, 2.25, 2.5, 2.75, 3.0, 4.0, 5.0])
        ->random();
    }
}

