<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Important: use Authenticatable
use Laravel\Sanctum\HasApiTokens; // for token creation
use Illuminate\Notifications\Notifiable;


class Instructor extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\InstructorFactory> */
    use HasFactory, HasApiTokens, Notifiable; // add these traits
    protected $fillable = [
        'name',
        'email',
        'password',
        'age',
        'course',
        'image',
        'contact_number',
        'role',
        'subject_id' // Foreign key to link with subjects
    ];

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }
}
