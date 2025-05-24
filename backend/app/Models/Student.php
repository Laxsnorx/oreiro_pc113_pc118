<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // use Authenticatable
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Student extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;
    protected $fillable = [
        'name',
        'age',
        'email',
        'password',
        'course',
        'role'
        ];
        public function grades()
    {
        return $this->hasMany(Grade::class); // Each student can have multiple grades
    }
}
