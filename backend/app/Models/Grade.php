<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    /** @use HasFactory<\Database\Factories\GradeFactory> */
    use HasFactory;
    protected $fillable = ['subject_id', 'student_id', 'midterm_grade', 'final_grade'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class); // Make sure Student model exists
    }
    public function getMidtermAttribute()
{
    return $this->attributes['midterm_grade'];
}

public function getFinalAttribute()
{
    return $this->attributes['final_grade'];
}
}
