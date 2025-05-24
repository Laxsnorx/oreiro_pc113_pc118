<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\Instructor;
use App\Mail\InstructorMail;
use Illuminate\Support\Facades\Mail;

Artisan::command('reminder:instructor-grades', function () {
    $instructors = \App\Models\Instructor::with('subjects')->get();
    $deadline = '2025-06-30';

    foreach ($instructors as $instructor) {
        $data = [
            'full_name' => $instructor->name,
            'subjects' => $instructor->subjects,
            'deadline' => $deadline,
            'instructor_id' => $instructor->id,
        ];

        \Illuminate\Support\Facades\Mail::to($instructor->email)
            ->send(new \App\Mail\InstructorMail($data));

        sleep(1); // Add a 1-second delay to avoid Mailtrap rate limits
    }

    $this->info('Grade input reminders sent to all instructors.');
});


// Schedule the command monthly
use Illuminate\Console\Scheduling\Schedule;

app(Schedule::class)->command('reminder:instructor-grades')->everyMinute();