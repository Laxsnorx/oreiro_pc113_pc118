<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    /** @use HasFactory<\Database\Factories\InstructorFactory> */
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'age',
        'course',
        'image',
        'contact_number',

    ];

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }
}
