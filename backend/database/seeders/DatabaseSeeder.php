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

        // Define fixed subject names
        $subjectNames = [
            'Reading Visual Arts',
            'Pagbasa at Pagsulat Tungo sa Pananaliksik',
            'Networking 2',
            'Discrete Mathematics',
            'Object-Oriented Programming',
            'Data Structure and Algorithm',
            'Fundamentals of Digital Logic Design',
            'Human Computer Interaction 2',
            'Physical Activities Towards Health & Fitness 2',
            'Graphic Design',
        ];

        // Create subjects with specific names and random grades
        echo "Seeding Subjects...\n";
        foreach ($subjectNames as $index => $name) {
            Subject::create([
                'code' => 'SUB' . str_pad($index + 1, 2, '0', STR_PAD_LEFT),    
                'name' => $name,
                'instructor_id' => $instructors[$index % $instructors->count()]->id,
                'schedule' => 'MWF 9:00 AM - 11:00 AM',
                'units' => 3,
                'year' => $this->generateYear(),
            ]);
        }

        // Create Grades for each Subject and Student
        echo "Seeding Grades...\n";
        $students = Student::all();
        Subject::all()->each(function ($subject) use ($students) {
            $students->each(function ($student) use ($subject) {
                Grade::create([
                    'subject_id' => $subject->id,
                    'student_id' => $student->id,
                    'midterm_grade' => $this->generateGrade(),
                    'final_grade' => $this->generateGrade(),
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
    public function generateYear(){
        return collect([
            '1st Year',
            '2nd Year',
            '3rd Year',
            '4th Year',
        ])->random();
    }
}

