<?php

namespace Database\Factories;
use App\Models\Subject;

use App\Models\Instructor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Instructor>
 */
class InstructorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Instructor::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name, // Random name
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('password'),
            'age' => $this->faker->numberBetween(25, 60), // Random age between 25 and 60
            'course' => $this->faker->jobTitle(),
            'image' => $this->faker->imageUrl(300, 300, 'people'), // Random image URL
            'contact_number' => $this->faker->phoneNumber, // Random phone number
            'role' => 'instructor', // Default role
            'subject_id' => Subject::inRandomOrder()->first()?->id ?? 1, 
        ];
    }
}
