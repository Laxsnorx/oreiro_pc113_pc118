<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Employee;


class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('employees')->insert([
            [
                'name' => 'John Doe',
                'email' => 'johndoe@example.com',
                'position' => 'Software Engineer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'janesmith@example.com',
                'position' => 'Project Manager',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Alice Johnson',
                'email' => 'alicejohnson@example.com',
                'position' => 'UX Designer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}



