<?php

namespace Database\Factories;

use App\Models\Subject;
use App\Models\Instructor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subject>
 */
class SubjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Subject::class;


    public function definition(): array
    {
        return [
            'name' => $this->faker->word, // Random subject name
            'instructor_id' => Instructor::factory(), // Link to an instructor
            'year' => $this->faker->year, // Random year (e.g., "2025")
            'schedule' => $this->faker->randomElement(['MWF 9:00 AM - 11:00 AM', 'TTH 1:00 PM - 3:00 PM', 'SAT 10:00 AM - 1:00 PM']), // Random schedule
        ];
    }
}
