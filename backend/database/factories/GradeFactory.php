<?php

namespace Database\Factories;

use App\Models\Grade;
use App\Models\Subject;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Grade>
 */
class GradeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Grade::class;

    public function definition(): array
    {
        return [
            'subject_id' => Subject::factory(),
            'student_id' => Student::factory(),
            'grade' => $this->faker->randomElement([
            '1.0', '1.25', '1.5', '1.75', '2.0', '2.25', '2.5', '2.75', '3.0', '4.0', '5.0'
            ]),
        ];
    }
}
